<?php
function get_page_parameters()
{
    include("functions/config.php");
    /* @var config $config
     */

    // ------------------ POST - add user ------------------
    if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['g-recaptcha-response'])) {
        require_once __DIR__ . '/vendor/autoload.php';
        $recaptcha = new \ReCaptcha\ReCaptcha($config['recaptcha_private']);
        $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);

        $email = $_POST['email'];
        $password = $_POST['password'];

        $message = '';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = 'email';
        } elseif (mb_strlen($password) < 5) {
            $message = 'password';
        } elseif (!$resp->isSuccess()) {
            $message = 'recaptcha';
        } else {
            try {
                $stmt = $GLOBALS["dbh"]->prepare('INSERT INTO users (id, email, password, created) VALUES (null, :email, :password, NOW())');
                $stmt->execute([':email' => $email, ':password' => password_hash($password, PASSWORD_DEFAULT)]);

                $message = 'success';
            } catch (PDOException $e) {
                $message = 'email_occupied';
            }
        }
        $_SESSION['message'] = 'register.' . $message;
        reload();
    }



    return [
        'recaptcha_public' => $config['recaptcha_public']
    ];
}