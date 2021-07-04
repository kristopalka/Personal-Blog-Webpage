<?php
function getDatabaseObj()
{
    $db_host = 'localhost';
    $db_name = 's040';
    $db_user = 's040';
    $db_password = 'q9ta5jcx';


    try {
        $dbh = new PDO('mysql:host=' . $db_host . ';dbname=' . $db_name . ';charset=utf8mb4', $db_user, $db_password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        print "Nie mozna polaczyc sie z baza danych: " . $e->getMessage();
        exit();
    }

    return $dbh;
}