<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>history</title>
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
        <?php foreach($err_msg as $err) { ?> 
            <p class="err"><?php print $err; ?></p>
        <?php } ?>
        <h2>購入履歴</h2>        
        <!--購入商品を表示-->
        <?php foreach($rows as $read){ ?>
            <div class ="cart">
            <table>
                <tr>
                    <!--商品画像-->
                    <td><img src ="<?php print htmlspecialchars($img_dir. $read['img'], ENT_QUOTES, 'utf-8'); ?>"></td>
                    <!--購入日時-->
                    <td>
                        <p>【商品名】</p>
                        <p><span><?php print htmlspecialchars($read['itemname'], ENT_QUOTES, 'utf-8'); ?></span></p>
                        <p>【焙煎度合い】</p>
                        <p><span><?php print htmlspecialchars($read['roast'], ENT_QUOTES, 'utf-8'); ?></span></p>
                        <p>【購入日時】</p>
                        <p><?php print htmlspecialchars($read['create_datetime'], ENT_QUOTES, 'utf-8'); ?></p>
                    </td>                    
                    <!--商品名・値段・小計-->
                    <td>                        
                        <p>【値段】</p>
                        <p><span><?php print htmlspecialchars($read['price'], ENT_QUOTES, 'utf-8'); ?></span>円</p>
                        <p>【購入量】</p>
                        <p><span><?php print htmlspecialchars($read['amount'], ENT_QUOTES, 'utf-8'); ?></span>g</p>
                        <?php $sub_total = $read['price'] * ($read['amount'] / 100 ); ?>
                        <P>【小計】</P>
                        <p><span><?php print htmlspecialchars($sub_total, ENT_QUOTES, 'utf-8'); ?></span>円</P>
                    </td>
                    <!--購入グラム数-->                    
                    <td>
                    <form method="post">                        
                        <p>メモ:</p>
                        <textarea placeholder="味や香りを次回購入時の参考に残しましょう。" name="note" cols="30" rows="7"></textarea>
                        <p><input type="submit" name="submit" value="保存"><p>
                        <input type="hidden" name="historyid" value="<?php print $read['historyid']; ?>">
                    </form>
                    <!--メモを表示-->                    
                        <p>記録日時:<?php print htmlspecialchars($read['note_datetime'], ENT_QUOTES, 'utf-8'); ?></p>
                        <p>メモ:<?php print htmlspecialchars($read['note'], ENT_QUOTES, 'utf-8'); ?></p>                    
                    </td>                    
                </tr>
            </table>
            </div>
        <?php } ?>        
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