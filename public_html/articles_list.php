<?php
function get_page_parameters()
{
    include("functions/config.php");
    /* @var config $config */



    $stmt = $GLOBALS["dbh"]->prepare("SELECT * FROM articles ORDER BY id DESC");
    $stmt->execute();
    $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($articles as &$article){
        $article['is_owner'] = ($article['user_id'] == $_SESSION['id']);
    }


    return [
        'articles' => $articles
    ];
}