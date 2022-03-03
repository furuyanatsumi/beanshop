<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>result</title>
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
        <?php if(count($err_msg) === 0){ ?>        
            <h2>ご購入ありがとうございました。</h2>        
            <?php foreach($rows as $read){ ?>
                <?php 
                $sub_total = $read['price'] * ($read['amount'] / 100 );            
                $total += $sub_total;
            } ?>        
            <div class="total"><P>購入金額は<span><?php print $total; ?>円</span>です。</P></div>            
            <!--購入商品を表示-->            
            <?php foreach($rows as $read){ ?> 
                <div class ="cart">
                <table>
                    <tr>
                        <!--商品画像-->
                        <td><img src ="<?php print htmlspecialchars($img_dir. $read['img'], ENT_QUOTES, 'utf-8'); ?>"></td>                    
                        <!--商品名・値段・小計-->
                        <td>
                            <p>【商品名】:<span><?php print htmlspecialchars($read['itemname'], ENT_QUOTES, 'utf-8'); ?></span></p>
                            <p>【焙煎度合い】:<span><?php print htmlspecialchars($read['roast'], ENT_QUOTES, 'utf-8'); ?></span></p>
                            <p>【値段】:<span><?php print htmlspecialchars($read['price'], ENT_QUOTES, 'utf-8'); ?></span>円</p>
                        </td>
                        <!--購入グラム数-->
                        <td>
                            <p>【購入量】:<span><?php print htmlspecialchars($read['amount'], ENT_QUOTES, 'utf-8'); ?></span>g</p>
                            <?php $sub_total = $read['price'] * ($read['amount'] / 100 ); ?>
                            <P>【小計】:<span><?php print htmlspecialchars($sub_total, ENT_QUOTES, 'utf-8'); ?></span>円</P>
                        </td>
                    </tr>
                </table>
                </div>
            <?php } ?>            
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