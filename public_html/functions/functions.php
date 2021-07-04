<?php
function get_domain() {
    $domena = preg_replace('/[^a-zA-Z0-9\.]/', '', $_SERVER['HTTP_HOST']);
    return $domena;
}

function reload(){
    $actual_link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    header("Location: " . $actual_link);
    exit();
}

function session_message()
{
    $login_message = '';
    if (isset($_SESSION['message'])) {
        $login_message = $_SESSION['message'];
        unset($_SESSION['message']);
    }
    return $login_message;
}

function is_loggin()
{
    return isset($_SESSION['id']);
}