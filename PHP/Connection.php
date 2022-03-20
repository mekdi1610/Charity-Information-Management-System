<?php
include_once('Config.php');

class Connection
{
    private $serverName, $userName, $password, $database;
    private $charset = "utf8mb4";
    function __construct()
    {
        //Call the Config class
        $conObj = new Config();
        $this->serverName = $conObj->getServerName();
        $this->userName = $conObj->getUserName();
        $this->password = $conObj->getPassword();
        $this->database = $conObj->getDatabase();
    }
    //Create a PDO Connection with MySQL Database
    public function connect()
    {
        try {
            $dsn = "mysql:host=" . $this->serverName . ";dbname=" . $this->database . ";charset=" . $this->charset;
            $PDO = new PDO($dsn, $this->userName, $this->password);
            $PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $PDO;
        } catch (PDOException $e) {
            //Throw an expection if there is a problem with the PDO Connection
            echo "Connection Failed" . $e->getMessage();
        }
    }
}
