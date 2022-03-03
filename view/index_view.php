<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>home</title>
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
                <!-- 管理ユーザーのみ商品管理ページリンク -->
                <?php if($type == 1){ ?>
                    <li><a href="./item_management.php">商品管理</a></li>
                <?php } ?>
            </ul>
        </nav>        
    </header>
    <?php foreach($err_msg as $err){ ?>
            <p class="err"><?php print $err; ?></p>
        <?php } ?>
    <div class="container">        
    <nav>
        <h3>並び替え</h3>
        <form method="get" name="order" action="index.php">
            <select name="order">
                <option value="">選択してください</option>
                <option value="high_price">値段が高い順</option>
                <option value="low_price">値段が安い順</option>
                <option value="newer">登録が新しい順</option>
                <option value="older">登録が古い順</option>
            </select>
            <input type="submit" value="並び替え">
        </form>
    </nav>
    <main>
        <h3>商品一覧</h3>
        <div class="main_items">
        <?php foreach($rows as $read){ ?>            
            <div class="bean">
                <!--商品画像-->
                <form method="get" name="itemid" action="item_information.php">
                    <a href="./item_information.php?itemid=<?php print $read['itemid']; ?>" target="_blank">
                        <img src ="<?php print htmlspecialchars($img_dir. $read['img'], ENT_QUOTES, 'utf-8'); ?>">
                    </a>
                    <input type="hidden" name="itemid" value="<?php print $read['itemid']; ?>">
                </form>
                <!--商品名-->
                <p>商品名:<span class="info"><?php print htmlspecialchars($read['itemname'], ENT_QUOTES, 'utf-8'); ?></span></p>
                <!--特徴-->
                <p>特徴:<?php print htmlspecialchars($read['comment'], ENT_QUOTES, 'utf-8'); ?></p>
                <!--値段-->
                <p>値段(100g):<span class="info"><?php print htmlspecialchars($read['price'], ENT_QUOTES, 'utf-8'); ?></span>円</p>
                <!--カート追加ボタンor売り切れ表示-->
                <?php
                    if($read['stock'] === 0){ ?>
                        <p><span class="soldout"><?php print '売り切れ'; ?></span></p>
                    <?php } else{ ?>
                        <form method="post" target="_blank" action="cart.php">
                            <input type="submit" name="cart" value="カートに追加">
                            <input type="hidden" name="itemid" value="<?php print $read['itemid']; ?>">
                            <input type="hidden" name="stock" value="<?php print $read['stock']; ?>">
                            <input type="hidden" name="roast" value="<?php print $read['roast']; ?>">
                        </form>
                    <?php } ?>
            </div>
        <?php } ?>    
        </div>
    </main>
    <aside>
        <h3>人気ランキング</h3>
        <?php foreach($rankings as $ranking){ ?>
            <div class="ranking">
                <!--商品画像-->
                <form method="get" name="itemid" action="item_information.php">
                    <a href="./item_information.php?itemid=<?php print $ranking['itemid']; ?>" target="_blank">
                        <img src ="<?php print htmlspecialchars($img_dir. $ranking['img'], ENT_QUOTES, 'utf-8'); ?>">
                    </a>
                    <input type="hidden" name="itemid" value="<?php print $ranking['itemid']; ?>">
                </form>
                <!--商品名-->
                <p>商品名:<span class="info"><?php print htmlspecialchars($ranking['itemname'], ENT_QUOTES, 'utf-8'); ?></span></p>
                <!--値段-->
                <p>値段(100g):<span class="info"><?php print htmlspecialchars($ranking['price'], ENT_QUOTES, 'utf-8'); ?></span>円</p>
                <!--カート追加ボタンor売り切れ表示-->
                <?php if($ranking['stock'] === 0){ ?>
                    <p><span class="soldout"><?php print '売り切れ'; ?></span></p>
                <?php } else{ ?>
                    <form method="post" target="_blank" action="cart.php">
                        <input type="submit" name="cart" value="カートに追加">
                        <input type="hidden" name="itemid" value="<?php print $ranking['itemid']; ?>">
                        <input type="hidden" name="stock" value="<?php print $ranking['stock']; ?>">
                        <input type="hidden" name="roast" value="<?php print $read['roast']; ?>">
                    </form>
                <?php } ?>
            </div>        
        <?php } ?>
     </aside>    
    </div>
    <footer>
        <p><small>&copy; 2022 beanshop</small></p>
        <form action="./logout.php" method="post">
        <div class="logout"><input type="submit" value="ログアウト"></div>
        </form>
    </footer>
</body>
</html>