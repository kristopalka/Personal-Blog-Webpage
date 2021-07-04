<?php
function get_page_parameters()
{
    // ---------------------------------- example 1 ----------------------------------
    $firstName = 'Karina';
    $secondName = 'Adam';

    $stmt = $GLOBALS["dbh"]->prepare("SELECT id, imie, nazwisko FROM test WHERE imie = :firstName OR imie = :secondName");
    $stmt->execute([':firstName' => $firstName, ':secondName' => $secondName]);
    $table1 = $stmt->fetchAll();


    // ---------------------------------- example 2 ----------------------------------
    $stmt = $GLOBALS["dbh"]->prepare("SELECT id, imie, nazwisko FROM test WHERE imie = 'Jim'");
    $stmt->execute();
    $table2 = $stmt->fetchAll();


    // ---------------------------------- example 3 ----------------------------------
    $stmt = $GLOBALS["dbh"]->prepare("SELECT id, imie, nazwisko FROM test");
    $stmt->execute();
    $table3 = $stmt->fetchAll();


    // ---------------------------------- example 3 - POST ----------------------------------
    if (isset($_POST['imie']) && isset($_POST['nazwisko'])) {
        $imie = $_POST['imie'];
        $nazwisko = $_POST['nazwisko'];

        if (mb_strlen($imie) >= 2 && mb_strlen($imie) < 50 && mb_strlen($nazwisko) >= 2 &mb_strlen($nazwisko) < 50) {
            $stmt = $GLOBALS["dbh"]->prepare("INSERT INTO test (imie, nazwisko) VALUES (:imie, :nazwisko)");
            $stmt->execute([':imie' => $imie, ':nazwisko' => $nazwisko]);

            header("Location: https://s040.labagh.pl/main");
            exit();
        }
    }



    return [
        'table1' => $table1,
        'table2' => $table2,
        'table3' => $table3
    ];
}











