<?php

class UserController extends BaseController
{
    /**
     * "/user/list" Endpoint - Get list of users
     */
    public function listAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];
        $arrQueryStringParams = $this->getQueryStringParams();

        if (strtoupper($requestMethod) == 'GET') {
            try {
                $userModel = new UserModel();

                $intLimit = 10;
                if (isset($arrQueryStringParams['limit']) && $arrQueryStringParams['limit']) {
                    $intLimit = $arrQueryStringParams['limit'];
                }

                $arrUsers = $userModel->getUsers($intLimit);
                $responseData = json_encode($arrUsers);
            } catch (Error $e) {
                $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        // send output
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)),
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }

    /**
     * "/user/token" Endpoint - Create authorisation token using email and password
     */
    public function tokenAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if (strtoupper($requestMethod) == 'POST') {
            // get posted data
            $data = json_decode(file_get_contents("php://input", true));
            try {
                $userModel = new UserModel();
                $cacheService = new RedisCacheService();

                $result = $userModel->getUser($data, 1);

                if (count($result) < 1) {
                    $strErrorDesc = 'Invalid User';
                    $strErrorHeader = 'HTTP/1.1 401 Unauthorized';
                } else {
                    $row = current($result);

                    $headers = array('alg' => 'HS256', 'typ' => 'JWT');
                    $payload = array('username' => $row['username'], 'user_email' => $row['user_email'], 'exp' => (time() + 60));
                    $jwt = JwtService::generateJwt($headers, $payload);
                    $cacheService->set(REDIS_PREFIX_TOKEN.$jwt, json_encode(array('username' => $row['username'], 'user_email' => $row['user_email'])));
                    $responseData = json_encode(array('token' => $jwt));
                }
            } catch (Error $e) {
                $strErrorDesc = $e->getMessage() . 'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        // send output
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)),
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }

    /**
     * "/user/account" Endpoint - Returning account information identified by authorisation token
     */
    public function accountAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if (strtoupper($requestMethod) == 'POST') {
            // get posted data
            $data = json_decode(file_get_contents("php://input", true));
            try {
                $userModel = new UserModel();
                $cacheService = new RedisCacheService();

                //Check is valid token
                if (JwtService::isJwtValid($data->token)) {
                    //Check Redis
                    $resultCacheJson = $cacheService->get(REDIS_PREFIX_TOKEN . $data->token);
                    if ($resultCacheJson) {
                        $resultDbArr = $userModel->getUserByEmail(json_decode($resultCacheJson), 1);
                        if (count($resultDbArr) < 1) {
                            $strErrorDesc = 'Invalid User';
                            $strErrorHeader = 'HTTP/1.1 401 Unauthorized';
                        } else {
                            $responseData = json_encode($resultDbArr);
                        }
                    } else {
                        $strErrorDesc = 'You token expired or not exist';
                        $strErrorHeader = 'HTTP/1.1 403 Forbidden';
                    }
                } else {
                    $strErrorDesc = 'Access denied';
                    $strErrorHeader = 'HTTP/1.1 403 Forbidden';
                }
            } catch (Error $e) {
                $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        // send output
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)),
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }

    /**
     * "/user/register" Endpoint - User registered by creating username and password
     */
    public function registerAction()
    {
        $strErrorDesc = '';
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if (strtoupper($requestMethod) == 'POST') {
            // get posted data
            $data = json_decode(file_get_contents("php://input", true));
            try {
                $userModel = new UserModel();

                $res = $userModel->registerUser($data);
                if($res) {
                    $responseData = json_encode(array('success' => 'You registered successfully'));
                } else {
                    $strErrorDesc = 'Something went wrong, please contact administrator';
                    $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
                }
            } catch (Error $e) {
                $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
            }
        } else {
            $strErrorDesc = 'Method not supported';
            $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity';
        }

        // send output
        if (!$strErrorDesc) {
            $this->sendOutput(
                $responseData,
                array('Content-Type: application/json', 'HTTP/1.1 200 OK')
            );
        } else {
            $this->sendOutput(json_encode(array('error' => $strErrorDesc)),
                array('Content-Type: application/json', $strErrorHeader)
            );
        }
    }
}