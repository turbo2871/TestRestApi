<?php

class Database
{
    protected $connection = null;

    public function __construct()
    {
        try {
            $this->connection = new PDO("mysql:host=database_test_rest_api;dbname=api_docker", DB_USERNAME, DB_PASSWORD);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function select($query = "" , $params = [])
    {
        try {
            $stmt = $this->executeStatement($query, $params);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            throw New Exception( $e->getMessage() );
        }
        return false;
    }

    public function insert($query = "" , $params = [])
    {
        try {
            return $this->executeStatement($query, $params);
        }catch(Exception $e) {
            throw New Exception( $e->getMessage() );
        }
        return false;
    }

    private function executeStatement($query = "", $params = [])
    {
        try {
            $stmt = $this->connection->prepare($query);

            if($stmt === false) {
                throw New Exception("Unable to do prepared statement: " . $query);
            }

            if( $params ) {
                foreach ( $params as $item) {
                    $type = PDO::PARAM_STR;
                    if ((int) $item['value'] != 0) {
                        $type = PDO::PARAM_INT;
                    }
                    $stmt->bindParam($item['param'], $item['value'], $type);
                }
            }

            $stmt->execute();

            return $stmt;
        } catch(Exception $e) {
            throw New Exception( $e->getMessage() );
        }
    }
}