<?php
function connDB() 
{
        //CONNECTION ESTABLISHMENT TO LOCAL HOST
        $username = "root";
        $password = "MMB3189@A";
        $dsn = 'mysql:dbname=LPCRMP;host=127.0.0.1;port=3306;socket=/tmp/mysql.sock';  
        //try and catch block to connect to MySQL, or throw an error
        try {
            $conn = new PDO($dsn, $username, $password);
        } catch (PDOException $e) {
        echo 'Connection Failed: ' . $e -> getMessage();
        } // end of try and catch
        return $conn;
    }
?>