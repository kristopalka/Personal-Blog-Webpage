<?php
function get_page_parameters()
{
    include("functions/config.php");
    /* @var config $config */


    // ------------------ GET - get article ------------------
    $is_owner = false;
    if (isset($_GET['id'])) {
        $stmt = $GLOBALS["dbh"]->prepare("SELECT * FROM articles WHERE id = :id");
        $stmt->execute([':id' => $_GET['id']]);
        $article = $stmt->fetch(PDO::FETCH_ASSOC);

        if($article['id'] != null)
        {
            $article_founded = true;
            $is_owner = ($article['user_id'] == $_SESSION['id']);
        }
        else $article_founded = false;
    }
    else $article_founded = false;


    // ------------------ POST - edit article ------------------
    if (isset($_POST['title']) && isset($_POST['content']) && isset($_POST['id'])) {

        $id = $_POST['id'];
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
            $stmt = $GLOBALS["dbh"]->prepare("UPDATE articles SET title = :title, content = :content, updated = NOW() WHERE id = :id AND user_id = :user_id");
            $res = $stmt->execute([':user_id' => (isset($_SESSION['id']) ? $_SESSION['id'] : 0),
                            ':id' => $id,
                            ':title' => $title,
                            ':content' => $content]);

            if($res) $message = 'success';
            else $message = 'error';


            header("Location: https://s040.labagh.pl/edit_article?id=".$id);
            exit();
        }
        $_SESSION['message'] = 'edit_article.' . $message;
        header("Location: https://s040.labagh.pl/edit_article?id=".$id);
        exit();
    }


    return [
        'is_owner' => $is_owner,
        'article_founded' => $article_founded,
        'article' => $article,
        'message' => session_message()
    ];
}