<?php
    require_once './helpers/MemberDAO.php';
    
    //セッションの開始
    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }

    //ログイン中の時
    if(!empty($_SESSION['member'])){
        //セッション変数の会員情報を取得する
        
        $member = $_SESSION['member'];
    }
    
?>
<header>
    <link href ="css/HeaderStyle.css" rel="stylesheet">

    <div id="logo">
        <a href="index.php">
            <img src="images/JecShoppingLogo.jpg" alt="JecShoppintロゴ">

        </a>
    </div>
    <div id="link">
        <form action="./index.php"method="GET">   
            <input type="text"name="keyword" value="<?php echo htmlspecialchars($keyword, ENT_QUOTES, "UTF-8") ?>">
            <input type="submit"value="検索">
        </form>
        <?php if (isset($member)):?>
            <?= $member->membername ?>さん
            
            <a href="cart.php">カート</a>
            <a href="logout.php">ログアウト</a>
            
        <?php else: ?>
            <a href="./login.php">ログイン</a>
        <?php endif; ?>
    </div>
    <div id="clear">
        <hr>
    </div>
</header>
