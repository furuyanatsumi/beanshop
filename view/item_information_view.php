<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>item_information</title>
    <link rel="stylesheet" href="../view/css/style.css">
</head>
<body>
<header class="page_header2">
        <div class="icons">
        <h1><a href="./index.php"><img class="logo" src="../view/images/logo.png" alt="logo">beanshop</a></h1>
        <p class='username'>ようこそ、<span class='name'><?php print $username; ?></span> さん</p>
        </div>
        <nav>
            <ul class="header_nav">
                <li><a href="./cart.php">カート</a></li>
                <li><a href="./history.php">購入履歴</a></li>
            </ul>
        </nav>        
    </header>
<main>
    <div class="products">
    <?php foreach($rows as $read){ ?>
        <div class ="bean_info">
            <table>
                <tr>
                    <td>
                    <!--商品画像-->
                    <p><img src ="<?php print htmlspecialchars($img_dir. $read['img'], ENT_QUOTES, 'utf-8'); ?>"></p>
                    </td>
                    <td>
                    <div class="info">
                    <!--商品名-->
                    <p>商品名:<span><?php print htmlspecialchars($read['itemname'], ENT_QUOTES, 'utf-8'); ?></span></p>                        
                    <!--値段-->
                    <p>値段(100g):<span class="price_font"><?php print htmlspecialchars($read['price'], ENT_QUOTES, 'utf-8'); ?></span>円</p>                        
                    <!--「カートに追加」ボタン-->
                    <?php
                    if($read['stock'] === 0){ ?>
                        <p><?php print '売り切れ'; ?></p>
                    <?php } else{ ?>
                        <form method="post" action="cart.php">
                            <input type="submit" name="cart" value="カートに追加">
                            <input type="hidden" name="itemid" value="<?php print $read['itemid']; ?>">
                        </form>
                    <?php } ?>
                    </div>
                    </td>
                </tr>
            </table>                
            <!--特徴-->
            <p>商品コメント:<?php print htmlspecialchars($read['comment'], ENT_QUOTES, 'utf-8'); ?></p>
            <?php $roast_info = '';
            if ($read['roast'] == 'ライトロースト'){
                $roast_info = 'ライトロースト';
            }else if($read['roast'] == 'シナモンロースト'){
                $roast_info = 'シナモンロースト' ;
            }else if($read['roast'] == 'ミディアムロースト'){
                $roast_info = 'ミディアムロースト' ;
            }else if($read['roast'] == 'ハイロースト'){
                $roast_info = 'ハイロースト' ;
            }else if($read['roast'] == 'シティロースト'){
                $roast_info = 'シティロースト' ;
            }else if($read['roast'] == 'フルシティロースト'){
                $roast_info = 'フルシティロースト' ;
            }else if($read['roast'] == 'フレンチロースト'){
                $roast_info = 'フレンチロースト' ;
            }else if($read['roast'] == 'イタリアンロースト'){
                $roast_info = 'イタリアンロースト' ;
            } ?>
            <p>おすすめ焙煎度合い：<?php print htmlspecialchars($roast_info, ENT_QUOTES, 'utf-8'); ?></p>
            <?php $body_info = '';
            if ($read['body'] == 0){
                $body_info = '●';
            }else if($read['body'] == 1){
                $body_info = '●●' ;
            }else if($read['body'] == 2){
                $body_info = '●●●' ;
            }else if($read['body'] == 3){
                $body_info = '●●●●' ;
            }else if($read['body'] == 4){
                $body_info = '●●●●●' ;
            } ?>
            <p>コク：<?php print htmlspecialchars($body_info, ENT_QUOTES, 'utf-8'); ?></p>
            <?php $acidity_info = '';
            if ($read['acidity'] == 0){
                $acidity_info = '●';
            }else if($read['acidity'] == 1){
                $acidity_info = '●●' ;
            }else if($read['acidity'] == 2){
                $acidity_info = '●●●' ;
            }else if($read['acidity'] == 3){
                $acidity_info = '●●●●' ;
            }else if($read['acidity'] == 4){
                $acidity_info = '●●●●●' ;
            } ?>
            <p>酸味：<?php print htmlspecialchars($acidity_info, ENT_QUOTES, 'utf-8'); ?></p>
            <?php $bitterness_info = '';
            if ($read['bitterness'] == 0){
                $bitterness_info = '●';
            }else if($read['bitterness'] == 1){
                $bitterness_info = '●●' ;
            }else if($read['bitterness'] == 2){
                $bitterness_info = '●●●' ;
            }else if($read['bitterness'] == 3){
                $bitterness_info = '●●●●' ;
            }else if($read['bitterness'] == 4){
                $bitterness_info = '●●●●●' ;
            } ?>
            <p>苦味：<?php print htmlspecialchars($bitterness_info, ENT_QUOTES, 'utf-8'); ?></p>
        </div>             
    <?php } ?>            
        <a href="./index.php">商品一覧に戻る</a>        
    </div>
    </main>
    <footer>
        <p><small>&copy; 2022 beanshop</small></p>
        <form action="./logout.php" method="post">
        <div class="logout"><input type="submit" value="ログアウト"></div>
        </form>
    </footer>
</body>
</html>