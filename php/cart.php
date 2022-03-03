<?php
require_once '../const.php';
$img_dir = './images/img';
$msg = [];
$rows = [];
$err_msg = [];
$total = 0;

session_start();
if(isset($_SESSION['userid'])){
    $userid = $_SESSION['userid'];
    $username = $_SESSION['username'];
    $type = $_SESSION['type'];
}else{
    header("location:./login.php");
    exit;
}
//DB接続
$dsn = 'mysql:dbname='. DB_NAME .';host='. DB_HOST .';charset='.DB_CHARSET;
try {
    $dbh = new PDO($dsn, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'));
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    //カートに追加
    if(isset($_POST['cart'])){
        $create_datetime = date('Y-m-d H:i:s');
        $updete_datetime = date('Y-m-d H:i:s');        
        $itemid = '';
        if(isset($_POST['itemid']) === TRUE){
            $itemid = $_POST['itemid'];
        }
        $roast = '';
        if(isset($_POST['roast']) === TRUE){
            $roast = $_POST['roast'];
        }
        $sql = 'INSERT INTO cart(userid, itemid, amount, roast, create_datetime, update_datetime) VALUE(?, ?, ?, ?, ?, ?)';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $userid, PDO::PARAM_INT);
        $stmt->bindValue(2, $itemid, PDO::PARAM_INT);
        $stmt->bindValue(3, 100, PDO::PARAM_INT);
        $stmt->bindValue(4, $roast, PDO::PARAM_INT);
        $stmt->bindValue(5, $create_datetime, PDO::PARAM_STR);
        $stmt->bindValue(6, $updete_datetime, PDO::PARAM_STR);
        $stmt->execute();
    }
    $sql_kind = '';
    if(isset($_POST['sql_kind']) === TRUE){
        $sql_kind = $_POST['sql_kind'];
    }
    //焙煎度合いを選択
    if($sql_kind === 'select_roast'){
        $update_datetime = date('Y-m-d H:i:s');
        $cartid = '';
        if(isset($_POST['cartid']) === TRUE){
            $cartid = $_POST['cartid'];
        }
        $roast = '';
        if(isset($_POST['update_roast']) === TRUE){
            $roast = $_POST['update_roast'];
        }        
        if(count($err_msg) === 0){
            try{
                $sql = 'UPDATE cart SET roast = ?, update_datetime = ? WHERE cartid = ?';
                $stmt = $dbh -> prepare($sql);
                $stmt -> bindValue(1, $roast, PDO::PARAM_INT);
                $stmt -> bindValue(2, $update_datetime, PDO::PARAM_STR);
                $stmt -> bindValue(3, $cartid, PDO::PARAM_INT);
                $stmt -> execute();
                $msg[] = '焙煎が選択されました';                
            }catch(PDOException $e){
                throw $e;
            }
        }
    }
    //購入グラム数を変更
    if($sql_kind === 'amount_change'){
        $update_datetime = date('Y-m-d H:i:s');
        $stock = '';
        if(isset($_POST['stock']) === TRUE){
            $stock = $_POST['stock'];
        }        
        $cartid = '';
        if(isset($_POST['cartid']) === TRUE){
            $cartid = $_POST['cartid'];
        }        
        $amount = '';
        if(isset($_POST['update_amount']) === TRUE){
            $amount = $_POST['update_amount'];
        }        
        $check_amount = preg_replace('/[\s　]/', '', $amount);
        if(mb_strlen($check_amount) === 0){
            $err_msg[] = '購入グラム数が入力されていません';
        }        
        //購入グラム数
        if(preg_match('/^[0-9]+$/', $amount) === 0 || $amount < 0){
            $err_msg[] = '購入グラム数は0以上かつ整数を入力してください';
        }        
        //在庫数は100g単位
        if($amount % 100 !== 0){
            $err_msg[] = '購入グラム数は100g単位で入力してください';
        }
        //エラーメッセージが0なら実行
        if(count($err_msg) === 0){
            try{
                $sql = 'UPDATE cart SET amount = ?, update_datetime = ? WHERE cartid = ?';
                $stmt = $dbh -> prepare($sql);
                $stmt -> bindValue(1, $amount, PDO::PARAM_INT);
                $stmt -> bindValue(2, $update_datetime, PDO::PARAM_STR);
                $stmt -> bindValue(3, $cartid, PDO::PARAM_INT);
                $stmt -> execute();
                $msg[] = '購入グラム数が更新されました';                
            }catch(PDOException $e){
                throw $e;
            }
        }
    }        
    //カートから削除
    if($sql_kind === 'delete_item'){        
        $cartid = '';
        if(isset($_POST['cartid']) === TRUE){
            $cartid = $_POST['cartid'];
        }    
        try{
            $sql = 'DELETE FROM cart WHERE cartid = ?';
            $stmt = $dbh -> prepare($sql);
            $stmt -> bindValue(1, $cartid, PDO::PARAM_INT);
            $stmt -> execute();
            $msg[] = '商品が削除されました';            
        }catch(PDOException $e){
            throw $e;
        }
    }
    //カート情報を取得
    $sql = 'SELECT
                items.itemid,
                items.itemname,
                items.price,
                items.img,
                cart.cartid,
                cart.amount,
                cart.roast
            FROM
                items
            INNER JOIN cart
            ON items.itemid = cart.itemid
            WHERE
                cart.userid = ?';
    $stmt = $dbh->prepare($sql);
    $stmt -> bindValue(1, $userid, PDO::PARAM_INT);
    $stmt -> execute();
    $rows = $stmt -> fetchAll();    
}catch(PDOException $e){
    $err_msg[] = '接続できませんでした。理由:'. $e->getMessage();
}
include_once '../view/cart_view.php';
?>
