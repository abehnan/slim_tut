<?php

    use \Psr\Http\Message\ServerRequestInterface as Request;

    require 'db.php';
    require './vendor/autoload.php';
    $app = new \Slim\App;

    $app->get('/users','getUsers');
    $app->run();

    // GET /slim_tut/users
    function getUsers(Request $request) {
        
        try {
            $db = getDB();
            $userInput = $request->getQueryParams();
            $sqlInsert = "INSERT INTO Customers (username, password) VALUES ('" . $userInput['name'] . "','" . $userInput['password'] . "')";
            $stmt = $db->prepare($sqlInsert);
            $stmt->execute();
            $db = null;
        } catch(PDOException $e) {
            //error_log($e->getMessage(), 3, '/var/tmp/phperror.log'); //Write error log
            echo '{"error":{"text":'. $e->getMessage() .'}}';
        }

        try {
            $db = getDB();
            $sqlSelect = "SELECT username,password FROM Customers";
            $stmt = $db->query($sqlSelect);
            
            $users = $stmt->fetchAll(PDO::FETCH_OBJ);
            $db = null;
            echo '{"users": ' . json_encode($users) . '}';
        } catch(PDOException $e) {
            //error_log($e->getMessage(), 3, '/var/tmp/phperror.log'); //Write error log
            echo '{"error":{"text":'. $e->getMessage() .'}}';
        }
    }

?>