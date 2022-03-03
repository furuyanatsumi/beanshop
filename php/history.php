<?php
require_once '../const.php';
$img_dir = './images/img';
$msg = [];
$rows = [];
$err_msg = [];
$data = [];

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
    $historyid = '';
    if(isset($_POST['historyid']) === TRUE){
        $historyid = $_POST['historyid'];
    }
    // メモ処理
    if(isset($_POST['submit'])){
        $note_datetime = date('Y-m-d H:i:s');        
        $note = '';
        if(isset($_POST['note']) === TRUE){
            $note = $_POST['note'];
        }          
        //エラーチェック(未入力と文字制限)
        $check_note = preg_replace('/[\s　]/', '', $note);
        if(mb_strlen($check_note) === 0){
            $err_msg[] = 'メモが入力されていません';
        }                
        $note_len = mb_strlen($note);
        if($note_len > 400){
            $err_msg[] = 'メモは400文字以内で入力してください';
        }        
        //エラーが0であれば実行
        if(count($err_msg) === 0){       
            try{            
                $sql = 'UPDATE history SET note = ?, note_datetime = ? WHERE historyid = ?';
                $stmt = $dbh -> prepare($sql);
                $stmt -> bindValue(1, $note, PDO::PARAM_STR);
                $stmt -> bindValue(2, $note_datetime, PDO::PARAM_INT);                    
                $stmt -> bindValue(3, $historyid, PDO::PARAM_INT);                    
                $stmt -> execute();                
            }catch(PDOException $e){               
                throw $e;
            }
        }      
    }
    // 履歴情報取得 
    try{        
        $sql = 'SELECT                    
                    items.itemid,
                    items.itemname,
                    items.price,
                    items.img,
                    history.historyid,
                    history.amount,
                    history.roast,
                    history.note,
                    history.note_datetime,
                    history.create_datetime
                FROM
                    items
                INNER JOIN history
                ON items.itemid = history.itemid
                WHERE history.userid = ?' ;
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $userid, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll();    
    }catch(PDOException $e){
        throw $e;
    }    
}catch(PDOException $e){
    $err_msg[] = '接続できませんでした。理由:'. $e->getMessage();
}
include_once '../view/history_view.php';
?>