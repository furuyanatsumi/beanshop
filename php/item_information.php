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
//DB接続
$dsn = 'mysql:dbname='. DB_NAME .';host='. DB_HOST .';charset='.DB_CHARSET;
try {
    $dbh = new PDO($dsn, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'));
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $itemid = '';
    if(isset($_GET['itemid']) === TRUE){
        $itemid = $_GET['itemid'];
    }    
    //商品情報を取得
    try{        
        $sql = 'SELECT
                    items.itemid,
                    items.itemname,
                    items.price,
                    items.img,
                    items.stock,
                    items.comment,
                    item_info.roast,
                    item_info.body,
                    item_info.acidity,
                    item_info.bitterness
                FROM
                    items
                INNER JOIN item_info ON items.itemid = item_info.itemid           
                WHERE items.itemid = ? ' ;
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $itemid, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt-> fetchAll();    
    }catch(PDOException $e){
        throw $e;
    }   
}catch(PDOException $e){
    $err_msg[] = '接続できませんでした。理由:'. $e->getMessage();
}
include_once '../view/item_information_view.php';
?>