<?php
require_once '../const.php';
$img_dir = './images/img';
$msg = [];
$rows = [];
$err_msg = [];
$data = [];
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
    //購入ボタン後の処理
    if(isset($_POST['buy'])){
        $buydate = date('Y-m-d H:i:s');
        $update_datetime = date('Y-m-d H:i:s');        
        $sql = 'SELECT
                    cart.cartid,
                    cart.itemid,
                    cart.amount,
                    cart.roast,
                    items.itemname,
                    items.price,
                    items.img,
                    items.stock,
                    items.status
                FROM
                    cart
                INNER JOIN items ON cart.itemid = items.itemid
                WHERE cart.userid = ?' ;
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $userid, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        foreach($rows as $data){       
            //ステータス確認
            if($data['status'] !== 1){
                $err_msg[] = 'この商品は購入できません';
            }            
            //在庫更新
            $remaining_stock = $data['stock'] - $data['amount'];
            if($remaining_stock < 0){
                $err_msg[] = '在庫がありません';
            }    
            if(count($err_msg) === 0){
                //トランザクション
                $dbh->beginTransaction();
                try{                        
                    $sql = 'UPDATE items SET stock= ?, update_datetime= ? WHERE itemid= ?';
                    $stmt = $dbh->prepare($sql);
                    $stmt->bindValue(1, $remaining_stock, PDO::PARAM_INT);
                    $stmt->bindValue(2, $update_datetime, PDO::PARAM_STR);
                    $stmt->bindValue(3, $data['itemid'], PDO::PARAM_INT);
                    $stmt->execute();                    
                    $sql = 'INSERT INTO history (userid, itemid, amount, roast, create_datetime) VALUE(?, ?, ?, ?, ?)';
                    $stmt = $dbh->prepare($sql);
                    $stmt->bindValue(1, $userid, PDO::PARAM_INT);
                    $stmt->bindValue(2, $data['itemid'], PDO::PARAM_INT);
                    $stmt->bindValue(3, $data['amount'], PDO::PARAM_INT);
                    $stmt->bindValue(4, $data['roast'], PDO::PARAM_INT);
                    $stmt->bindValue(5, $buydate, PDO::PARAM_STR);
                    $stmt->execute();                    
                    $sql = 'DELETE FROM cart WHERE userid = ?';
                    $stmt = $dbh -> prepare($sql);
                    $stmt -> bindValue(1, $userid, PDO::PARAM_INT);
                    $stmt -> execute();                                
                    $dbh->commit();
                }catch(PDOException $e){
                    $dbh->rollback();
                    throw $e;
                }
            }
        }      
    }
}catch(PDOException $e){
    $err_msg[] = '接続できませんでした。理由:'. $e->getMessage();
}
include_once '../view/result_view.php';
?>