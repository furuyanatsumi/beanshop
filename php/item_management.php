<?php
require_once '../const.php';
$img_dir = './images/img';
$msg = [];
$rows = [];
$err_msg = [];

session_start();
if(isset($_SESSION['userid'])){
    $userid = $_SESSION['userid'];
    $username = $_SESSION['username'];
    $type = $_SESSION['type'];
}else{
    header("location:./login.php");
    exit;
}
if($type !== 1){
    header("location:/index.php");
}
//DB接続
$dsn = 'mysql:dbname='. DB_NAME .';host='. DB_HOST .';charset='.DB_CHARSET;
try {
    $dbh = new PDO($dsn, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'));
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    //$sql_kindでボタンの処理を分別
    $sql_kind = '';
    if(isset($_POST['sql_kind']) === TRUE){
        $sql_kind = $_POST['sql_kind'];
    }    
    //商品追加ボタンの処理
    if($sql_kind === 'insert'){
        $create_datetime = date('Y-m-d H:i:s');
        $update_datetime = date('Y-m-d H:i:s');        
        $itemname = '';
        if(isset($_POST['itemname']) === TRUE){
            $itemname = $_POST['itemname'];
        }        
        $price = '';
        if(isset($_POST['price']) === TRUE){
            $price = $_POST['price'];
        }        
        $stock = '';
        if(isset($_POST['stock']) === TRUE){
            $stock = $_POST['stock'];
        }        
        //商品名未入力チェック
        $check_itemname = preg_replace('/[\s　]/', '', $itemname);
        if(mb_strlen($check_itemname) === 0){
            $err_msg[] = '商品名が入力されていません';
        }        
        //値段未入力チェック
        $check_price = preg_replace('/[\s　]/', '', $price);
        if(mb_strlen($check_price) === 0){
            $err_msg[] = '値段が入力されていません';
        }        
        //在庫数のチェック
        $check_stock = preg_replace('/[\s　]/', '', $stock);
        if(mb_strlen($check_stock) === 0){
            $err_msg[] = '在庫数が入力されていません';
        }        
        //値段チェック
        if(preg_match('/^[0-9]+$/', $price) === 0 || $price < 0){
            $err_msg[] = '値段は0以上かつ整数を入力してください';
        }        
        //在庫数チェック
        if(preg_match('/^[0-9]+$/', $stock) === 0 || $stock < 0){
            $err_msg[] = '在庫数は0以上かつ整数を入力してください';
        }        
        //在庫数単位チェック
        if($stock % 100 !== 0){
            $err_msg[] = '在庫数は100g単位で入力してください';
        }        
        //公開ステータス
        $status = '';
        if(isset($_POST['status']) === TRUE){
            $status = $_POST['status'];
        }        
        if($status === ''){
            $err_msg[] = 'ステータスを選択してください';
        }        
        if($status !== '0' && $status !== '1'){
            $err_msg[] = 'ステータスは公開か非公開を選択してください';
        }        
        //アップロード画像ファイルの保存
        if(is_uploaded_file($_FILES['new_img']['tmp_name']) === TRUE){
            $extension = pathinfo($_FILES['new_img']['name'], PATHINFO_EXTENSION);
            if($extension === 'JPEG' ||$extension === 'jpeg' || $extension === 'PNG' || $extension === 'png' || $extension === 'JPG' || $extension === 'jpg'){
                $new_img_filename = sha1(uniqid(mt_rand(), true)).'.'.$extension;
                if(is_file($img_dir.$new_img_filename) !== TRUE){
                    if(move_uploaded_file($_FILES['new_img']['tmp_name'], $img_dir.$new_img_filename) !== TRUE){
                        $err_msg[] = 'ファイルアップロードに失敗しました';
                    }
                }else{
                    $err_msg[] = 'ファイルアップロードに失敗しました。再度お試しください。';
                }
            }else{
                $err_msg[] = 'ファイル形式が異なります。画像ファイルはjpeg/pngが利用可能です。';
            }
        }else{
            $err_msg[] = 'ファイルを選択してください。';
        }        
        //焙煎度
        $roast = '';
        if(isset($_POST['roast']) === TRUE){
            $roast = $_POST['roast'];
        }        
        //コク
        $body = '';
        if(isset($_POST['body']) === TRUE){
            $body = $_POST['body'];
        }        
        //酸味
        $acidity = '';
        if(isset($_POST['acidity']) === TRUE){
            $acidity = $_POST['acidity'];
        }        
        //苦味
        $bitterness = '';
        if(isset($_POST['bitterness']) === TRUE){
            $bitterness = $_POST['bitterness'];
        }        
        //焙煎度未入力チェック
        $check_roast = preg_replace('/[\s　]/', '', $roast);
        if(mb_strlen($check_roast) === 0){
            $err_msg[] = 'ローストが選択されていません';
        }        
        //コク未入力チェック
        $check_body = preg_replace('/[\s　]/', '', $body);
        if(mb_strlen($check_body) === 0){
            $err_msg[] = 'コクが選択されていません';
        }        
        //酸味未入力チェック
        $check_acidity = preg_replace('/[\s　]/', '', $acidity);
        if(mb_strlen($check_acidity) === 0){
            $err_msg[] = '酸味が選択されていません';
        }
        //苦味未入力チェック
        $check_bitterness = preg_replace('/[\s　]/', '', $bitterness);
        if(mb_strlen($check_bitterness) === 0){
            $err_msg[] = '苦味が選択されていません';
        }        
        
        //コク入力チェック
        if(preg_match('/[0-4]/', $body) === 0){
            $err_msg[] = 'コクを選択してください';
        }        
        //酸味入力チェック
        if(preg_match('/[0-4]/', $acidity) === 0){
            $err_msg[] = '酸味を選択してください';
        }        
        //苦味入力チェック
        if(preg_match('/[0-4]/', $bitterness) === 0){
            $err_msg[] = '苦味を選択してください';
        }        
        //特徴
        $comment = '';
        if(isset($_POST['comment']) === TRUE){
            $comment = $_POST['comment'];
        }        
        //特徴のチェック
        $check_comment = preg_replace('/[\s　]/', '', $comment);
        if(isset($check_comment) === false){
            $err_msg[] = '特徴が入力されていません';
        }        
        //エラーメッセージ0なら実行
        if(count($err_msg) === 0){
            $dbh->beginTransaction();
            try{                
                $sql = 'INSERT INTO items (itemname, price, img, status, stock, comment, create_datetime, update_datetime) VALUE(?,?,?,?,?,?,?,?)';
                $stmt = $dbh -> prepare($sql);
                $stmt -> bindValue(1, $itemname, PDO::PARAM_STR);
                $stmt -> bindValue(2, $price, PDO::PARAM_INT);
                $stmt -> bindValue(3, $new_img_filename, PDO::PARAM_STR);
                $stmt -> bindValue(4, $status, PDO::PARAM_INT);
                $stmt -> bindValue(5, $stock, PDO::PARAM_INT);
                $stmt -> bindValue(6, $comment, PDO::PARAM_STR);
                $stmt -> bindValue(7, $create_datetime, PDO::PARAM_STR);
                $stmt -> bindValue(8, $update_datetime, PDO::PARAM_STR);
                $stmt -> execute();                
                $itemid = $dbh -> lastInsertId();                
                //SQL文作成(item_info)
                $sql = 'INSERT INTO item_info (itemid, roast, body, acidity, bitterness, create_datetime) VALUE(?, ?, ?, ?, ?, ?)';
                $stmt = $dbh->prepare($sql);
                $stmt->bindValue(1, $itemid, PDO::PARAM_INT);
                $stmt->bindValue(2, $roast, PDO::PARAM_INT);
                $stmt->bindValue(3, $body, PDO::PARAM_INT);
                $stmt->bindValue(4, $acidity, PDO::PARAM_INT);
                $stmt->bindValue(5, $bitterness, PDO::PARAM_INT);
                $stmt->bindValue(6, $create_datetime, PDO::PARAM_STR);
                $stmt->execute();                
                $dbh->commit();
                $msg[] = '商品情報が追加されました';            
            }catch(PDOException $e){
                $dbh->rollback();
                throw $e;
            }
        }
    }    
    //商品名変更ボタンの処理
    if($sql_kind === 'change_itemname'){
        $update_datetime = date('Y-m-d H:i:s');        
        $itemid = '';
        if(isset($_POST['itemid']) === TRUE){
            $itemid = $_POST['itemid'];
        }        
        $itemname = '';
        if(isset($_POST['update_itemname']) === TRUE){
            $itemname = $_POST['update_itemname'];
        }        
        $check_itemname = preg_replace('/[\s　]/', '', $itemname);
        if(isset($check_itemname) === false){
            $err_msg[] = '商品名が入力されていません';
        }        
        //エラーメッセージが0なら実行
        if(count($err_msg) === 0){
            try{                
                $sql = 'UPDATE items SET itemname = ?, update_datetime = ? WHERE itemid = ?';
                $stmt = $dbh -> prepare($sql);
                $stmt -> bindValue(1, $itemname, PDO::PARAM_STR);
                $stmt -> bindValue(2, $update_datetime, PDO::PARAM_STR);
                $stmt -> bindValue(3, $itemid, PDO::PARAM_INT);
                $stmt -> execute();
                $msg[] = '商品名が更新されました';                
            }catch(PDOException $e){
                throw $e;
            }
        }
    }    
    //値段変更ボタンの処理
    if($sql_kind === 'change_price'){
        $update_datetime = date('Y-m-d H:i:s');        
        $itemid = '';
        if(isset($_POST['itemid']) === TRUE){
            $itemid = $_POST['itemid'];
        }        
        $price = '';
        if(isset($_POST['update_price']) === TRUE){
            $price = $_POST['update_price'];
        }        
        $check_price = preg_replace('/[\s　]/', '', $price);
        if(isset($check_price) === false){
            $err_msg[] = '値段が入力されていません';
        }        
        if(preg_match('/^[0-9]+$/', $price) === 0 || $price < 0){
            $err_msg[] = '値段は0以上かつ整数を入力してください';
        }        
        //エラーメッセージが0なら実行
        if(count($err_msg) === 0){
            try{                
                $sql = 'UPDATE items SET price = ?, update_datetime = ? WHERE itemid = ?';
                $stmt = $dbh -> prepare($sql);
                $stmt -> bindValue(1, $price, PDO::PARAM_STR);
                $stmt -> bindValue(2, $update_datetime, PDO::PARAM_STR);
                $stmt -> bindValue(3, $itemid, PDO::PARAM_INT);
                $stmt -> execute();
                $msg[] = '値段が変更されました';                
            }catch(PDOException $e){
                throw $e;
            }
        }
    }    
    //在庫数変更ボタンの処理
    if($sql_kind === 'change_stock'){
        $update_datetime = date('Y-m-d H:i:s');        
        $itemid = '';
        if(isset($_POST['itemid']) === TRUE){
            $itemid = $_POST['itemid'];
        }        
        $stock = '';
        if(isset($_POST['update_stock']) === TRUE){
            $stock = $_POST['update_stock'];
        }        
        $check_stock = preg_replace('/[\s　]/', '', $stock);
        if(isset($check_stock) === false){
            $err_msg[] = '在庫数が入力されていません';
        }        
        if($stock % 100 !== 0){
            $err_msg[] = '在庫数は100g単位で入力してください';
        }        
        if(preg_match('/^[0-9]+$/', $stock) === 0 || $stock < 0){
            $err_msg[] = '在庫数は0以上かつ整数を入力してください';
        }        
        //エラーメッセージが0なら実行
        if(count($err_msg) === 0){
            try{
                $sql = 'UPDATE items SET stock = ?, update_datetime = ? WHERE itemid = ?';
                $stmt = $dbh -> prepare($sql);
                $stmt -> bindValue(1, $stock, PDO::PARAM_STR);
                $stmt -> bindValue(2, $update_datetime, PDO::PARAM_STR);
                $stmt -> bindValue(3, $itemid, PDO::PARAM_INT);
                $stmt -> execute();
                $msg[] = '在庫数が変更されました';                
            }catch(PDOException $e){
                throw $e;
            }
        }
    }    
    //status変更処理
    if($sql_kind === 'change_status'){
        $update_datetime = date('Y-m-d H:i:s');        
        $itemid = '';
        if(isset($_POST['itemid']) === TRUE){
            $itemid = $_POST['itemid'];
        }        
        $status = '';
        if(isset($_POST['update_status']) === TRUE){
            $status = $_POST['update_status'];
        }        
        if($status === '0'){
            $status = 0;
        }else if($status === '1'){
            $status = 1;
        }else {
            $err_msg[] = 'ステータスは公開か非公開を選択してください';
        }        
        if(count($err_msg) === 0){
            try{                
                //sql文作成
                $sql = 'UPDATE items SET status = ?, update_datetime = ? WHERE itemid = ?';
                $stmt = $dbh -> prepare($sql);
                $stmt -> bindValue(1, $status, PDO::PARAM_STR);
                $stmt -> bindValue(2, $update_datetime, PDO::PARAM_STR);
                $stmt -> bindValue(3, $itemid, PDO::PARAM_INT);
                $stmt -> execute();
                $msg[] = 'ステータスが更新されました';                
            }catch(PDOException $e){
                throw $e;
            }           
        }        
    }    
    //商品削除処理
    if($sql_kind === 'delete'){
        $itemid = '';
        if(isset($_POST['itemid']) === TRUE){
            $itemid = $_POST['itemid'];
        }        
        $dbh->beginTransaction();
        try{    
            $sql = 'DELETE FROM items WHERE itemid = ?';
            $stmt = $dbh -> prepare($sql);
            $stmt -> bindValue(1, $itemid, PDO::PARAM_INT);
            $stmt -> execute();

            $itemid = $dbh -> lastInsertId();

            $sql = 'delete from item_info where itemid = ?';
            $stmt = $dbh -> prepare($sql);
            $stmt -> bindValue(1, $itemid, PDO::PARAM_INT);
            $stmt -> execute();            
            $dbh->commit();
            $msg[] = '商品が削除されました';            
        }catch(PDOException $e){
            $dbh->rollback();
            throw $e;
        }
    }  
        //商品情報を取得
        $sql = 'SELECT
                    itemid,
                    itemname,
                    price,
                    img,
                    stock,
                    status
                FROM items';
        $stmt = $dbh->prepare($sql);
        $stmt -> execute();
        $rows = $stmt -> fetchAll();        
    }catch(PDOException $e){
        echo '接続できませんでした。理由:' . $e-> getMessage();
    }
include '../view/item_management_view.php';
?>