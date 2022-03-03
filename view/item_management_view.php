<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>item_management</title>
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
        <?php foreach($err_msg as $err){ ?>
            <p class ="error"><?php print $err; ?></p>
        <?php } ?>
        <?php if(count($msg) !== 0){
            foreach($msg as $complete){ ?>
            <p><?php print $complete; ?></p>
            <?php }
        } ?>
        <div class="infomation">
            <!-- 商品追加-->
            <form method = "post" enctype = "multipart/form-data">
                <table class="input_table">
                <tr>
                <td class="input_left">
                    <label><p>商品名:<input type = "text" name = "itemname"></p></label>
                    <label><p>値段:<input type = "text" name = "price"></p></label>
                    <label><p>在庫数(100g単位):<input type = "text" name = "stock"></p></label>
                    <label>
                        <p>公開ステータス:
                            <select name = "status">
                                <option value="">選択してください</option>
                                <option value="0">非公開</option>
                                <option value="1">公開</option>
                            </select>
                        </p>
                    </label>
                    <label><p>商品画像:<input type = "file" name = "new_img"></p></label>
                </td>
                <td class="input_right">
                    <label>
                        <p>おすすめ焙煎度合い:
                            <select name="roast">
                                <option value="">選択してください</option>
                                <option value="ライトロースト">ライトロースト</option>
                                <option value="シナモンロースト">シナモンロースト</option>
                                <option value="ミディアムロースト">ミディアムロースト</option>
                                <option value="ハイロースト">ハイロースト</option>
                                <option value="シティロースト">シティロースト</option>
                                <option value="フルシティロースト">フルシティロースト</option>
                                <option value="フレンチロースト">フレンチロースト</option>
                                <option value="イタリアンロースト">イタリアンロースト</option>
                            </select>
                        </p>
                    </label>
                    <label>
                        <p>コク:
                            <select name="body">
                                <option value="">選択してください</option>
                                <option value="0">●</option>
                                <option value="1">●●</option>
                                <option value="2">●●●</option>
                                <option value="3">●●●●</option>
                                <option value="4">●●●●●</option>
                            </select>
                            </p>
                    </label>
                    <label>
                        <p>酸味:
                            <select name="acidity">
                                <option value="">選択してください</option>
                                <option value="0">●</option>
                                <option value="1">●●</option>
                                <option value="2">●●●</option>
                                <option value="3">●●●●</option>
                                <option value="4">●●●●●</option>
                            </select>
                        </p>
                    </label>
                    <label>
                        <p>苦味:
                            <select name="bitterness">
                                <option value="">選択してください</option>
                                <option value="0">●</option>
                                <option value="1">●●</option>
                                <option value="2">●●●</option>
                                <option value="3">●●●●</option>
                                <option value="4">●●●●●</option>
                            </select>
                        </p>
                    </label>
                    <label>特徴:<textarea name="comment" cols="30" rows="7"></textarea></label>
                </td>    
                </tr>    
                </table>
                <input type = "hidden" name = "sql_kind" value = "insert">
                <input type = "submit" value = "商品追加">
            </form>            
        </div>
        <div class="item">
            <table>
                <tr>
                    <th>商品画像</th>
                    <th>商品名</th>
                    <th>値段(100gあたり)</th>
                    <th>在庫数(g)</th>
                    <th>公開ステータス</th>
                    <th>商品削除</th>
                </tr>                
                <?php foreach($rows as $read){ ?>                
                <tr>
                    <!--商品画像-->
                    <td><img src="<?php print htmlspecialchars($img_dir.$read['img'], ENT_QUOTES,'utf-8'); ?>"></td>                    
                    <!--商品名-->
                    <td>
                        <form method="post">
                            <p><input type="text" name="update_itemname" value="<?php print htmlspecialchars($read['itemname'], ENT_QUOTES, 'utf-8'); ?>"></p>
                            <p><input type="submit" value="商品名変更"></p>
                            <input type="hidden" name="sql_kind" value="change_itemname">
                            <input type="hidden" name="itemid" value="<?php print $read['itemid']; ?>">
                        </form>
                    </td>                    
                    <!--値段-->
                    <td>
                        <form method="post">
                            <p><input type="text" name="update_price" value="<?php print htmlspecialchars($read['price'], ENT_QUOTES, 'utf-8'); ?>"></p>
                            <p><input type="submit" value="値段変更"></p>
                            <input type="hidden" name="sql_kind" value="change_price">
                            <input type="hidden" name="itemid" value="<?php print $read['itemid']; ?>">
                        </form>
                    </td>                    
                    <!--在庫数-->
                    <td>
                        <form method="post">
                            <p><input type="text" name="update_stock" value="<?php print htmlspecialchars($read['stock'], ENT_QUOTES, 'utf-8'); ?>"></p>
                            <p><input type="submit" value="在庫数変更"></p>
                            <input type="hidden" name="sql_kind" value="change_stock">
                            <input type="hidden" name="itemid" value="<?php print $read['itemid']; ?>">
                        </form>
                    </td>                    
                    <!--ステータス-->
                    <td>
                        <?php
                            if($read['status'] === 0){ ?>
                                <form method="post">
                                    <input type="submit" value="非公開→公開">
                                    <input type="hidden" name="sql_kind" value="change_status">
                                    <input type="hidden" name="update_status" value="1">
                                    <input type="hidden" name="itemid" value="<?php print $read['itemid']; ?>">
                                </form>
                            <?php }else{ ?>
                                <form method="post">
                                    <input type="submit" name="status_select" value="公開→非公開">
                                    <input type="hidden" name="sql_kind" value="change_status">
                                    <input type="hidden" name="update_status" value="0">
                                    <input type="hidden" name="itemid" value="<?php print $read['itemid']; ?>">
                                </form>
                            <?php } ?>
                    </td>                    
                    <!--商品削除-->
                    <td>
                        <form method = "post">
                            <input type="submit" value="商品削除">
                            <input type="hidden" name="sql_kind" value="delete">
                            <input type="hidden" name="itemid" value="<?php print $read['itemid']; ?>">
                        </form>
                    </td>
                </tr>                
                <?php } ?>
            </table>
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