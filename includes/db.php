<?php
require_once 'config.php';

class Database {
    private $connection;

    public function __construct() {
        $this->connect();
    }

    private function connect() {
        try {
            $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
            
            if ($this->connection->connect_error) {
                throw new Exception("Connection failed: " . $this->connection->connect_error);
            }
            
            $this->connection->set_charset("utf8mb4");
        } catch (Exception $e) {
            error_log("Database connection error: " . $e->getMessage());
            throw $e;
        }
    }

    public function query($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            
            if (!$stmt) {
                throw new Exception("Error in query preparation: " . $this->connection->error);
            }
            
            if (!empty($params)) {
                $types = '';
                foreach ($params as $param) {
                    if (is_int($param)) {
                        $types .= 'i';
                    } elseif (is_float($param)) {
                        $types .= 'd';
                    } else {
                        $types .= 's';
                    }
                }
                $stmt->bind_param($types, ...$params);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            
            return $result;
        } catch (Exception $e) {
            error_log("Database query error: " . $e->getMessage());
            throw $e;
        }
    }

    public function getRow($sql, $params = []) {
        $result = $this->query($sql, $params);
        return $result ? $result->fetch_assoc() : null;
    }

    public function getRows($sql, $params = []) {
        $result = $this->query($sql, $params);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function insert($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            
            if (!$stmt) {
                throw new Exception("Error in insert preparation: " . $this->connection->error . " | SQL: " . $sql);
            }
            
            if (!empty($params)) {
                $types = '';
                foreach ($params as $param) {
                    if (is_int($param)) {
                        $types .= 'i';
                    } elseif (is_float($param)) {
                        $types .= 'd';
                    } else {
                        $types .= 's';
                    }
                }
                $stmt->bind_param($types, ...$params);
            }
            
            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }
            
            $insertId = $stmt->insert_id;
            $stmt->close();
            
            return $insertId;
        } catch (Exception $e) {
            error_log("Database insert error: " . $e->getMessage());
            throw $e;
        }
    }

    public function update($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            
            if (!$stmt) {
                throw new Exception("Error in update preparation: " . $this->connection->error);
            }
            
            if (!empty($params)) {
                $types = '';
                foreach ($params as $param) {
                    if (is_int($param)) {
                        $types .= 'i';
                    } elseif (is_float($param)) {
                        $types .= 'd';
                    } else {
                        $types .= 's';
                    }
                }
                $stmt->bind_param($types, ...$params);
            }
            
            $stmt->execute();
            $affectedRows = $stmt->affected_rows;
            $stmt->close();
            
            return $affectedRows;
        } catch (Exception $e) {
            error_log("Database update error: " . $e->getMessage());
            throw $e;
        }
    }

    public function close() {
        if ($this->connection) {
            $this->connection->close();
        }
    }

    public function __destruct() {
        $this->close();
    }
}

// Create database instance
try {
    $db = new Database();
} catch (Exception $e) {
    die("Database connection error. Please try again later.");
}
?>