<?php

namespace App;

use \PDO;

class Database{

    public $connection;

    public function __construct()
    {
        // Create a new PDO connection to the specified database using the provided credentials
        try {
            $connection = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS, [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (\PDOException $e) {
            die("Database connection failed: ". $e->getMessage());
        }

        // Store the database connection object for future use
        $this->connection = $connection;
        
    }
        
    /**
     * Get All data from database
     *
     * @param  String $tableName
     * @return Array
     */
    public function getAll(String $tableName): Array
    {
        // Example
        // $database->getAll('plugins');
        
        try {
            $stmt = $this->connection->prepare("SELECT * FROM {$tableName}");
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            throw new \PDOException("Error: ". $e->getMessage());
        }

    }

    
    /**
     * Select data from database
     *
     * @param  String $tableName Name of table
     * @param  Array $params - e.g ['id' => 1]
     * @param  Array $order - e.g ['id' => 'ASC']
     * @param  Int $limit - e.g 10
     * @return Array
     */
    public function select(String $tableName, Array $params, Array $order = ['id' => 'ASC'], Int $limit = null): Array
    {
        // Example
        // $database->select('plugins', ['id' => 1, 'name' => 'Plugin'], ['id' => 'ASC'], 10);
        
        // If array $params if empty return empty array
        if(empty($params)){
            return [];
        }

        // An empty string is assigned to the $conditions variable and empty array to $values
        $conditions = '';
        $values = [];


        // Create string of conditions and array of values
        foreach($params as $key => $value){
            $conditions .= $key . ' = :' . $key . ' AND ';
            $values[':' . $key] = $value;
        }
        // Remove final AND operator
        $conditions = rtrim($conditions, ' AND ');
        
        // The SQL query is built, including the table name and any additional conditions
        if(empty($order)){
            $orderSQL = 'id ASC';
        }else{
            $orderSQL = key($order) . ' ' . current($order);
        }

        $sql = sprintf('SELECT * FROM %s WHERE %s ORDER BY %s', $tableName, $conditions, $orderSQL);
        
        if($limit){
            $sql .= sprintf(' LIMIT %d', $limit);
        }
        
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($values);
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            throw new \PDOException("Error: ". $e->getMessage());
        }

    }    
    /**
     * Finds data in database
     *
     * @param  Array $params
     * @return Array With one element
     */
    public function find(String $tableName, Array $params): Array
    {
        // Example
        // $database->find('plugins', ['name' => 'Plugin'])

        // An empty string is assigned to the $conditions variable and empty array to $values
        $conditions = '';
        $values = [];

        // Create string of conditions and array of values
        foreach($params as $key => $value){
            $conditions .= $key . ' = :' . $key . ' AND ';
            $values[':' . $key] = $value;
        }
        // Remove final AND operator
        $conditions = rtrim($conditions, ' AND ');
        
        // Construct the SQL query
        $sql = sprintf('SELECT * FROM %s WHERE %s', $tableName, $conditions);

        // Executes the SQL query and returns the result as an associative array
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($values);
            $result = $stmt->fetchAll();
            if($result){
                return $result[0];
            }else{
                return [];
            }
        } catch (\PDOException $e) {
            throw new \PDOException("Error: ". $e->getMessage());
        }
        
    }    

    
    /**
     * Update record/-s in database
     *
     * @param  String $tableName
     * @param  Array $set
     * @param  Array $where
     * @return void
     */
    public function update(String $tableName, Array $set, Array $where)
    {

        // Example
        // $database->update('plugins', ['name' => 'Plugin'], ['id' => 1]);

        // An empty string is assigned to the $assignment_list variable and $conditions variable
        $assignment_list = '';
        $conditions = '';

        // Assign empty array to $values
        $values = [];

        // Create string of assignment_list and array of values
        foreach($set as $key => $value){
            $assignment_list .= $key . ' = :' . $key . ' AND ';
            $values[':' . $key] = $value;
        }
        // Create string of conditions and array of values
        foreach($where as $key => $value){
            $conditions .= $key . ' = :' . $key . ' AND ';
            $values[':' . $key] = $value;
        }
        // Remove final AND operator
        $assignment_list = rtrim($assignment_list, ' AND ');
        $conditions = rtrim($conditions, ' AND ');

        // Build the SQL query string using the table name and parameter data
        $sql = sprintf('UPDATE %s SET %s WHERE %s', $tableName, $assignment_list, $conditions);

        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($values);
        } catch (\PDOException $e) {
            throw new \PDOException("Error: ". $e->getMessage());
        }
    }    

        
    /**
     * Store data in database
     *
     * @param  String $tableName
     * @param  Array $params
     * @return void
     */
    public function store(String $tableName, Array $params)
    {  
        // Example
        // $database->store('plugins', ['name' => 'Plugin']);

        // An empty string is assigned to the $columns variable and $valuesNames variable
        $columns = '';
        $valuesNames = '';

        // Assign empty array to $values
        $values = [];

        // Create string of columns, with corresponding valuesNames and array of values
        foreach($params as $key => $value){
            $columns .= $key . ', ';
            $valuesNames .= ':' . $key . ', ';
            $values[':' . $key] = $value;
        }
        // Remove final AND operator
        $columns = rtrim($columns, ', ');
        $valuesNames = rtrim($valuesNames, ', ');
        
        // Creates the SQL query to insert data into the specified table
        $sql = sprintf('INSERT INTO %s (%s) VALUES (%s)', $tableName, $columns, $valuesNames);

        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($values);
        } catch (\PDOException $e) {
            throw new \PDOException("Error: ". $e->getMessage());
        }
    }    
    
    /**
     * Delete data from database
     *
     * @param  String $tableName
     * @param  Array $params
     * @return bool
     */
    public function delete(String $tableName, Array $params): bool
    { 
        // Example
        // $database->delete('plugins', ['name' => 'Plugin']);

        // An empty string is assigned to the $conditions variable and empty array to $values
        $conditions = '';
        $values = [];

        // Create string of conditions and array of values
        foreach($params as $key => $value){
            $conditions .= $key . ' = :' . $key . ' AND ';
            $values[':' . $key] = $value;
        }
        // Remove final AND operator
        $conditions = rtrim($conditions, ' AND ');
        
        // Builds a SQL DELETE statement with the table name and WHERE clause
        $sql = sprintf('DELETE FROM %s WHERE %s', $tableName, $conditions);

        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($values);
            return true;
        } catch (\PDOException $e) {
            throw new \PDOException("Error: ". $e->getMessage());
        }

        // Returns false if the query was unsuccessful
        return false;
    }

    public function __destruct()
    {
        // Close database connection
        $this->connection = null;
    }
}

?>