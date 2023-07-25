<?php
    session_start();
    //セッション変数を初期化
    $_SESSION = [];
    $session_name = session_name();

    //cookieを削除
    if(isset($_COOKIE[$session_name])){
        setcookie($session_name, '',time()-3600);
    }
    //セッションデータを破棄する
    session_destroy();

    header('Location:index.php');
    exit;
?>
