<?php
class Database {
    public $host = 'localhost';
    public $dbName = 'taskmanager';
    public $user = 'root';
    public $password = 'secret';
    public $conexao;

    protected $varType = array(
        'boolean' => PDO::PARAM_BOOL,
        'integer' => PDO::PARAM_INT,
        'string' => PDO::PARAM_STR,
    );

    public function __construct()
    {
        $this->conexao = $this->connect();
    }

    public function connect()
    {
        try {
            $connection = new PDO(
                "mysql:host=$this->host;dbname=$this->dbName",
                "$this->user",
                "$this->password"
            );

            $connection->exec('SET NAMES utf8');
            return $connection;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function save($table, $data)
    {
        try {
            $this->conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $arrayKeysData = array_keys($data);
            $endArray = end($arrayKeysData);
            $columnsToInsert = '';
            $valuesToInsert = '';

            foreach ($data as $key => $value) {
                $columnsToInsert .= "$key" . ($endArray !== $key ? ', ' : '');
                $valuesToInsert .= ":$key" . ($endArray !== $key ? ', ' : '');
            }

            $query = "INSERT INTO $table ($columnsToInsert, created) VALUES ($valuesToInsert, NOW())";

            $query = $this->conexao->prepare($query);
            foreach ($data as $key => $value) {
                $query->bindValue(":$key", $value);
            }

            $query->execute();
            return true;
        } catch (\PDOException $exception) {
            return $exception->getMessage();
        }
    }

    public function makeQuery($query)
    {
        try {
            $query = $this->conexao->prepare($query);
            $query->execute();
            $this->queryResult = $query->fetchAll(PDO::FETCH_ASSOC);
            return $this->queryResult;
        } catch (\PDOException $exception) {
            return $exception->getMessage();
        }
    }

    public function delete($table, $id)
    {
        try {
            $stmt = $this->conexao->prepare("DELETE FROM $table WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            return $stmt->rowCount();
        } catch (\PDOException $exception) {
            return $exception->getMessage();
        }
    }
}