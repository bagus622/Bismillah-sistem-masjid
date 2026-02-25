<?php
/**
 * Database Class
 * MySQL Database connection and query builder
 */

class Database {
    private static $instance = null;
    private $pdo;
    private $statement;
    
    // Constructor
    public function __construct() {
        $this->connect();
    }
    
    // Get singleton instance
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    // Connect to database
    private function connect() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            if (DEBUG) {
                die("Database Connection Error: " . $e->getMessage());
            } else {
                die("Database connection error. Please try again later.");
            }
        }
    }
    
    // Get PDO instance
    public function getPdo() {
        return $this->pdo;
    }
    
    // Prepare statement
    public function prepare($sql) {
        $this->statement = $this->pdo->prepare($sql);
        return $this;
    }
    
    // Execute query
    public function query($sql, $params = []) {
        try {
            $this->statement = $this->pdo->prepare($sql);
            $this->statement->execute($params);
            return $this->statement->fetchAll();
        } catch (PDOException $e) {
            // Throw exception instead of die
            throw new Exception("Database Query Error: " . $e->getMessage());
        }
    }
    
    // Execute statement
    public function execute($sql, $params = []) {
        try {
            $this->statement = $this->pdo->prepare($sql);
            return $this->statement->execute($params);
        } catch (PDOException $e) {
            // Throw exception instead of die
            throw new Exception("Database Error: " . $e->getMessage());
        }
    }
    
    // Get single row
    public function first($sql, $params = []) {
        $results = $this->query($sql, $params);
        return $results ? $results[0] : null;
    }
    
    // Get last insert ID
    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }
    
    // Begin transaction
    public function beginTransaction() {
        return $this->pdo->beginTransaction();
    }
    
    // Commit transaction
    public function commit() {
        return $this->pdo->commit();
    }
    
    // Rollback transaction
    public function rollback() {
        return $this->pdo->rollBack();
    }
    
    // Insert record
    public function insert($table, $data) {
        try {
            $keys = array_keys($data);
            
            // Wrap column names with backticks to handle reserved keywords
            $fields = implode(', ', array_map(function($key) {
                return "`{$key}`";
            }, $keys));
            
            $placeholders = implode(', ', array_fill(0, count($keys), '?'));
            
            $sql = "INSERT INTO {$table} ({$fields}) VALUES ({$placeholders})";
            
            $this->execute($sql, array_values($data));
            
            return $this->lastInsertId();
        } catch (Exception $e) {
            throw new Exception("Insert Error in table {$table}: " . $e->getMessage());
        }
    }
    
    // Update record
    public function update($table, $data, $where, $whereParams = []) {
        $set = [];
        foreach (array_keys($data) as $key) {
            // Wrap column names with backticks to handle reserved keywords
            $set[] = "`{$key}` = ?";
        }
        $setClause = implode(', ', $set);
        
        $sql = "UPDATE {$table} SET {$setClause} WHERE {$where}";
        
        return $this->execute($sql, array_merge(array_values($data), $whereParams));
    }
    
    // Delete record
    public function delete($table, $where, $params = []) {
        $sql = "DELETE FROM {$table} WHERE {$where}";
        return $this->execute($sql, $params);
    }
    
    // Count records
    public function count($table, $where = '', $params = []) {
        $sql = "SELECT COUNT(*) as total FROM {$table}";
        
        if (!empty($where)) {
            $sql .= " WHERE {$where}";
        }
        
        $result = $this->first($sql, $params);
        return $result ? (int) $result['total'] : 0;
    }
    
    // Get all records
    public function get($table, $where = '', $params = [], $orderBy = '', $limit = '') {
        $sql = "SELECT * FROM {$table}";
        
        if (!empty($where)) {
            $sql .= " WHERE {$where}";
        }
        
        if (!empty($orderBy)) {
            $sql .= " ORDER BY {$orderBy}";
        }
        
        if (!empty($limit)) {
            $sql .= " LIMIT {$limit}";
        }
        
        return $this->query($sql, $params);
    }
    
    // Get single record
    public function getOne($table, $where, $params = []) {
        $results = $this->get($table, $where, $params, '', '1');
        return $results ? $results[0] : null;
    }
    
    // Paginate
    public function paginate($sql, $params, $page = 1, $perPage = PER_PAGE) {
        $page = max(1, (int) $page);
        $offset = ($page - 1) * $perPage;
        
        // Get total count - try the original approach first
        $countSql = preg_replace('/^SELECT.*?FROM/is', 'SELECT COUNT(*) as total FROM', $sql, 1);
        $countSql = preg_replace('/ORDER BY.*$/i', '', $countSql);
        
        $total = 0;
        try {
            $totalResult = $this->first($countSql, $params);
            if ($totalResult && is_array($totalResult) && isset($totalResult['total'])) {
                $total = (int) $totalResult['total'];
            }
        } catch (Exception $e) {
            // If count query fails, try simple query
            try {
                $allData = $this->query($sql, $params);
                $total = count($allData);
            } catch (Exception $e2) {
                $total = 0;
            }
        }
        
        // Get data
        $dataSql = $sql . " LIMIT {$offset}, {$perPage}";
        $data = $this->query($dataSql, $params);
        
        return [
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => $total > 0 ? ceil($total / $perPage) : 0
        ];
    }
}
