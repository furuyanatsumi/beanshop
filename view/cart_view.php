<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>cart</title>
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
        <div class="cart_info">
        <?php foreach($err_msg as $err) { ?> 
            <p class="err"><?php print $err; ?></p>
        <?php } ?>        
        <h2>カートの詳細</h2>        
        <?php foreach($rows as $read){
            $sub_total = $read['price'] * ($read['amount'] / 100 );            
            $total += $sub_total;
            }             
            if($total === 0) { ?>
                <div class="msg"><p>カートが空です</p></div>
            <?php } else { ?>
                <div class="total"><P>購入金額は<span><?php print $total; ?>円</span>です。</P></div>                  
                    <div class="buy">
                        <form method="post" action="result.php">
                            <input type="submit" class="buybutton" name="buy" value="購入する">
                        </form>
                    </div>                
            <?php } ?>
            </div>     
        <?php foreach($rows as $read){ ?>        
        <!--カートの中身を表示-->
            <div class="cart">
            <table>            
                <tr>
                    <!--商品画像-->
                    <td class="imgbox"><img src ="<?php print htmlspecialchars($img_dir. $read['img'], ENT_QUOTES, 'utf-8'); ?>"></td>                    
                    <!--商品名・値段・小計-->
                    <td class="info">
                        <p>商品名:　<span><?php print htmlspecialchars($read['itemname'], ENT_QUOTES, 'utf-8'); ?></span></p>
                        <p>焙煎度合い選択:
                            <form method="post">
                            <select name="update_roast">
                                <option value="<?php print htmlspecialchars($read['roast'], ENT_QUOTES, 'utf-8'); ?>">【<?php print htmlspecialchars($read['roast'], ENT_QUOTES, 'utf-8'); ?>】</option>
                                <option value="ライトロースト">ライトロースト</option>
                                <option value="シナモンロースト">シナモンロースト</option>
                                <option value="ミディアムロースト">ミディアムロースト</option>
                                <option value="ハイロースト">ハイロースト</option>
                                <option value="シティロースト">シティロースト</option>
                                <option value="フルシティロースト">フルシティロースト</option>
                                <option value="フレンチロースト">フレンチロースト</option>
                                <option value="イタリアンロースト">イタリアンロースト</option>
                            </select>
                            <input type="submit" value="選択">
                            <input type="hidden" name="sql_kind" value="select_roast">
                            <input type="hidden" name="cartid" value="<?php print $read['cartid']; ?>">
                            </form>
                        </p>
                        <p>値段:　<span><?php print htmlspecialchars($read['price'], ENT_QUOTES, 'utf-8'); ?></span>円</p>
                        <?php $sub_total = $read['price'] * ($read['amount'] / 100 ); ?>
                        <P>小計:　<span><?php print htmlspecialchars($sub_total, ENT_QUOTES, 'utf-8'); ?></span>円</P>
                    </td>
                    <!--購入グラム数-->
                    <td class="info">
                        <form method = "post">
                            <p>購入量:<input type="text" class="amountform" name="update_amount" value="<?php print htmlspecialchars($read['amount'], ENT_QUOTES, 'utf-8'); ?>">g
                            <input type="submit" value="変更">
                            <input type="hidden" name="sql_kind" value="amount_change">
                            <input type="hidden" name="cartid" value="<?php print $read['cartid']; ?>"></p>
                        </form>
                        <form method = "post">
                            <p>商品をカートから削除　<input type="submit" value="削除"></p>
                            <input type="hidden" name="sql_kind" value="delete_item">
                            <input type="hidden" name="cartid" value="<?php print $read['cartid']; ?>">
                        </form>
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