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
    //ランキング取得
    $sql = 'SELECT
                items.itemid,
                items.itemname,
                items.price,
                items.img,
                items.stock,
                item_info.roast,
                history.itemid,
                SUM(history.amount) 
        FROM items
        JOIN history
        ON items.itemid = history.itemid
        JOIN item_info ON items.itemid = item_info.itemid
        GROUP BY items.itemid
        ORDER BY SUM(history.amount) DESC
        LIMIT 3';
    $stmt = $dbh->prepare($sql);
    $stmt -> execute();
    $rankings = $stmt -> fetchAll();    
    //商品情報取得
    $sql = 'SELECT
                items.itemid,
                itemname,
                price,
                img,
                stock,
                comment,
                roast 
            FROM items 
            INNER JOIN item_info ON items.itemid = item_info.itemid
            WHERE items.status = "1" ';
    $stmt = $dbh->prepare($sql);
    $stmt -> execute();
    $rows = $stmt -> fetchAll();    
    //並び替え
    $order = '';
    if(isset($_GET['order']) === TRUE){
        $order = $_GET['order'];
    }    
    //新しい順
    if($order === 'newer'){
        //DBから情報を取得
        $sql = 'SELECT
                    items.itemid,
                    itemname,
                    price,
                    img,
                    stock,
                    comment,
                    roast,
                    items.create_datetime 
                FROM items 
                INNER JOIN item_info ON items.itemid = item_info.itemid
                WHERE items.status = "1" 
                ORDER BY create_datetime DESC';
        $stmt = $dbh->prepare($sql);
        $stmt -> execute();
        $rows = $stmt -> fetchAll();
    //古い順
    }else if ($order === 'order'){
        $sql = 'SELECT
                    items.itemid,
                    itemname,
                    price,
                    img,
                    stock,
                    comment,
                    roast,
                    items.create_datetime 
                FROM items 
                INNER JOIN item_info ON items.itemid = item_info.itemid
                WHERE items.status = "1" 
                ORDER BY create_datetime ASC';
        $stmt = $dbh->prepare($sql);
        $stmt -> execute();
        $rows = $stmt -> fetchAll();
    //値段が高い順    
    }else if ($order === 'high_price'){
        $sql = 'SELECT
                    items.itemid,
                    itemname,
                    price,
                    img,
                    stock,
                    comment,
                    roast,
                    items.create_datetime 
                FROM items
                INNER JOIN item_info ON items.itemid = item_info.itemid
                WHERE items.status = "1" 
                ORDER BY price DESC';
        $stmt = $dbh->prepare($sql);
        $stmt -> execute();
        $rows = $stmt -> fetchAll();
    //値段が安い順
    }else if($order === 'low_price'){
        $sql = 'SELECT
                    items.itemid,
                    itemname,
                    price,
                    img,
                    stock,
                    comment,
                    roast,
                    items.create_datetime 
                FROM items
                INNER JOIN item_info ON items.itemid = item_info.itemid
                WHERE items.status = "1" 
                ORDER BY price ASC';
        $stmt = $dbh->prepare($sql);
        $stmt -> execute();
        $rows = $stmt -> fetchAll();
    }
}catch(PDOException $e){
    $err_msg[] = '接続できませんでした。理由:'. $e->getMessage();
}
include_once '../view/index_view.php';
?>