<?php

error_reporting(E_ALL);
$dirPath = realpath(dirname(__DIR__));

/**
* Class Name : ContactForm
* 
* All processing related to contact form should be done from this class.
*/
class DbConf
{
    protected $pdo;
    
    protected function setUp()
    {
        $this->pdo = new \PDO('mysql:host=127.0.0.1; dbname=testdb', 'root', '');
    }

    public function getUserData($user_id, $user_pass)
    {
        $pdo = new \PDO('mysql:host=127.0.0.1; dbname=testdb', 'root', '');
        $stmt = $pdo->prepare("SELECT * FROM `user_phs` WHERE `name` = '$user_id' && `pass` = '$user_pass'");
        $stmt->execute();
        $user = $stmt->fetch();

        return $user;
    }
}
?>

