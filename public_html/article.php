<?php
function get_page_parameters()
{
    include("functions/config.php");
    /* @var config $config */

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



    return [
        'is_owner' => $is_owner,
        'article_founded' => $article_founded,
        'article' => $article
    ];
}
