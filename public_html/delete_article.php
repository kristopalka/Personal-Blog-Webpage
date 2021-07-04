<?php
function get_page_parameters()
{
    include("functions/config.php");
    /* @var config $config */

    if (isset($_GET['id'])){
        $stmt = $GLOBALS["dbh"]->prepare("DELETE FROM articles WHERE id = :id AND user_id = :user_id");
        $res = $stmt->execute([':id' => $_GET['id'], ':user_id' => $_SESSION['id']]);
        if($res){
            $message = 'success';
        }
        else $message = 'you_cannot';


    }
    $_SESSION['message'] = 'delete_article.' . $message;




    return [
        'message' => session_message()
    ];
}