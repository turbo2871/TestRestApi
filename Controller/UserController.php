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

                if(count($result) < 1) {
                    $strErrorDesc = json_encode(array('error' => 'Invalid User'));
                } else {
                    $row = current($result);

                    $headers = array('alg'=>'HS256','typ'=>'JWT');
                    $payload = array('username' => $row['username'], 'user_email' => $row['user_email'], 'exp' => (time() + 60));
                    $jwt = JwtService::generate_jwt($headers, $payload);
                    $cacheService->set('token_'.md5($row['username'].$row['user_email']), $jwt);
                    $responseData = json_encode(array('token' => $jwt));
                }
            } catch (Error $e) {
                $strErrorDesc = $e->getMessage().'Something went wrong! Please contact support.';
                $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';
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

    /**
     * "/user/account" Endpoint - Returning account information identified by authorisation token
     */
    public function accountAction()
    {

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
                    $strErrorDesc = json_encode(array('error' => 'Something went wrong, please contact administrator'));
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