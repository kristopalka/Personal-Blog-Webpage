<?php
session_start();

//ini_set('display_errors', 1);
//error_reporting(E_ALL);

include("functions/functions.php");
include("functions/database.php");
$GLOBALS["dbh"] = getDatabaseObj();


// ------------------ POST - login ------------------
if (isset($_POST['password']) && isset($_POST['login'])) {
    $stmt = $GLOBALS["dbh"]->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute([':email' => $_POST['login']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $message = '';
    if ($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $message = 'success';
    } else $message = 'error';
    $_SESSION['message'] = 'login.' . $message;
    reload();
}

// ------------------ POST - logout ------------------
if (isset($_POST['logout'])) {
    unset($_SESSION['id']);
    unset($_SESSION['email']);
    reload();
}
// ------------------ last active ------------------

if (isset($_SESSION['id'])) {
    $stmt = $GLOBALS["dbh"]->prepare("UPDATE users SET last_seen = NOW() WHERE id = :id");
    $stmt->execute([':id' => $_SESSION['id']]);
}

// ------------------ message ------------------
if (isset($_SESSION['login_message'])) {
    $login_message = $_SESSION['login_message'];
    unset($_SESSION['login_message']);
}


// ------------------ choice proper page ------------------
$pages = ['articles_list', 'register', 'guest_book', 'all_comments', 'article'];
$pages_for_logged = ['articles_add', 'edit_article', 'delete_article'];

if (in_array($_GET['page'], $pages_for_logged)) {
    if (is_loggin()) $page = $_GET['page'];
    else $page = 'not_logged_user';
} else if (in_array($_GET['page'], $pages)) {
    $page = $_GET['page'];
} else {
    $page = 'main';
}


// ------------------ render content------------------
include($page . '.php');
$parameters_page = get_page_parameters();
$parameters = [
    'email' => $_SESSION['email'],
    'message' => session_message(),
    'is_loggin' => is_loggin() ? 'true' : 'false',
    'domain' => get_domain(),
    'date' => date('Y-m-d'),
    'content_file' => $page . '.html.twig'
];


include 'vendor/autoload.php';
$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader, ['debug' => true]);
echo $twig->render('index.html.twig', array_merge($parameters, $parameters_page));

