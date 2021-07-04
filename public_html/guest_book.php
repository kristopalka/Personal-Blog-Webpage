<?php
function get_page_parameters()
{
    include("functions/config.php");
    /* @var config $config */

    // ------------------ POST - add opinion ------------------
    if (isset($_POST['opinion']) && isset($_POST['g-recaptcha-response'])) {
        require_once __DIR__ . '/vendor/autoload.php';
        $recaptcha = new \ReCaptcha\ReCaptcha($config['recaptcha_private']);
        $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);

        $opinion = $_POST['opinion'];

        $message = '';
        if (mb_strlen($opinion) < 1 || mb_strlen($opinion) > 100) {
            $message = 'empty';
        } elseif (!$resp->isSuccess()) {
            $message = 'recaptcha';
        } else {
            $stmt = $GLOBALS["dbh"]->prepare("INSERT INTO guest_book (opinion, ip, created) VALUES (:opinion, :ip, NOW())");
            $stmt->execute([':opinion' => $opinion, ':ip' => $_SERVER['REMOTE_ADDR']]);

            $message = 'success';
        }
        $_SESSION['message'] = 'opinion.' . $message;
        reload();
    }


    // ------------------ GET - print table ------------------
    $stmt = $GLOBALS["dbh"]->prepare("SELECT created, opinion, id, ip FROM guest_book");
    $stmt->execute();
    $opinions = $stmt->fetchAll(PDO::FETCH_ASSOC);


    foreach ($opinions as $key => $row) {
        if ($row['ip'] == $_SERVER['REMOTE_ADDR'])
            $opinions[$key]['can_delete'] = 'true';
        else
            $opinions[$key]['can_delete'] = 'false';
    }


    // ------------------ POST - remove element ------------------
    if (isset($_POST['id'])) {
        $idToDelete = $_POST['id'];

        $stmt = $GLOBALS["dbh"]->prepare("DELETE FROM guest_book WHERE id = :id AND ip = :ip");
        $stmt->execute([':id' => $idToDelete, ':ip' => $_SERVER['REMOTE_ADDR']]);
    }


    return [
        'message' => session_message(),
        'recaptcha_public' => $config['recaptcha_public'],
        'opinions' => $opinions
    ];
}