<?php

namespace Framework\Core;

use \Framework\Core\Drivers\DriverFactory;

class Database
{
    private static $inst;

    /*
     * @var \PDO
     */
    private $db;

    private function __construct(\PDO $db) {
        $this->db = $db;
    }

    /*
     * @return Database
     */
    public static function getIntance($instanceName = 'default') {
        if (!isset(self::$inst[$instanceName])) {
            throw new \Exception('Instance with that name was not set.');
        }

        return self::$inst[$instanceName];
    }

    public static function setInstance(
        $instanceName,
        $driver,
        $user,
        $pass,
        $dbName,
        $host = null
    ) {
        $driver = DriverFactory::create($driver, $user, $pass, $dbName, $host);

        $pdo = new \PDO(
            $driver->getDsn(),
            $user,
            $pass
        );

        self::$inst[$instanceName] = new self($pdo);
    }


    /*
     * @param string $statement
     * @paramt array $driverOptions
     * @return Statement
     */
    public function prepare($statement, array $driverOptions = []) {
        $statement = $this->db->prepare($statement, $driverOptions);

        return new Statement($statement);
    }

    public function query($query) {
        return $this->db->query($query);
    }

    public function lastId($name = null) {
        return $this->db->lastInsertId($name);
    }
}

class Statement {

    /*
     * @var \PDOStatement
     */
    private $stmt;

    public function __construct($stmt) {
        $this->stmt = $stmt;
    }

    public function fetch($fetchstyle = \PDO::FETCH_ASSOC) {
        return $this->stmt->fetch($fetchstyle);
    }

    public function fetchAll($fetchStyle = \PDO::FETCH_ASSOC) {
        return $this->stmt->fetchAll($fetchStyle);
    }

    public function bindParam($parameter, &$variable, $dataType=\PDO::PARAM_STR, $length, $driver_options) {
        return $this->stmt->bindParam($parameter, $variable, $dataType, $length, $driver_options);
    }

    public function execute($input_parameters = null) {
        return $this->stmt->execute($input_parameters);
    }

    public function rowCount() {
        return $this->stmt->rowCount();
    }

}