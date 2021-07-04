<?php
function get_page_parameters()
{
    include("functions/config.php");
    /* @var config $config */


    // ------------------ POST - add article ------------------
    if (isset($_POST['title']) && isset($_POST['content'])) {


        $title = $_POST['title'];
        $content = $_POST['content'];

        $message = '';
        if (mb_strlen($title) < 1) {
            $message = 'empty_title';
        } elseif (mb_strlen($title) > 50) {
            $message = 'too_long_title';
        } elseif (mb_strlen($content) < 1) {
            $message = 'empty_content';
        } else {
            $stmt = $GLOBALS["dbh"]->prepare("INSERT INTO articles (user_id, title, content, created) VALUES (:user_id, :title, :content, NOW())");
            $stmt->execute([':user_id' => $_SESSION['id'],
                            ':title' => $title,
                            ':content' => $content]);


            header("Location: https://s040.labagh.pl/articles_list");
            exit();
        }
        $_SESSION['message'] = 'article_add.' . $message;
        reload();
    }


    return [
        'message' => session_message()
    ];
}