<?php
require_once '../const.php';
$err_msg = [];
$rows = [];
$msg = '';

session_start();
    if(isset($_SESSION['userid'])){
        header("location:./index.php");
        exit;
    }
//DB接続
$dsn = 'mysql:dbname='. DB_NAME .';host='. DB_HOST .';charset='.DB_CHARSET;
try {
    $dbh = new PDO($dsn, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'));
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    //signup後の処理
    if(isset($_POST['signup'])){
        $create_datetime = date('Y-m-d H:i:s');        
        $username = '';
        if(isset($_POST['username']) === TRUE){
            $username = $_POST['username'];
        }    
        $check_username = preg_replace('/[\s　]/', '', $username);
        if(isset($check_username) === false){
            $err_msg[] = 'usernameが入力されていません';
        }        
        if(preg_match('/^[0-9a-zA-Z]*$/', $username) === 0){
            $err_msg[] = 'usernameは半角英数字で入力してください';
        }        
        $username_len = mb_strlen($username);
        if($username_len < 6){
            $err_msg[] = 'usernameは6文字以上で入力してください';
        }        
        $password = '';
        if(isset($_POST['password']) === TRUE){
            $password = $_POST['password'];
        }        
        $check_password = preg_replace('/[\s　]/', '', $password);
        if(isset($check_password) === false){
            $err_msg[] = 'passwordが入力されていません';
        }        
        if(preg_match('/^[0-9a-zA-Z]*$/', $password) === 0){
            $err_msg[] = 'passwordは半角英数字で入力してください';
        }        
        $password_len = mb_strlen($password);
        if($password_len < 6){
            $err_msg[] = 'passwordは6文字以上で入力してください';
        }        
        //エラーメッセージが0なら実行
        if(count($err_msg) === 0){            
            try{
                $sql = 'SELECT
                            username,
                            password
                        FROM user 
                        WHERE username = ?';
                $stmt = $dbh -> prepare($sql);
                $stmt -> bindValue(1, $username, PDO::PARAM_STR);
                $stmt -> execute();
                $rows = $stmt-> fetchAll();                
                if(count($rows) > 0){
                    $err_msg[] = '既に同じusernameが存在します';
                }else{
                    //登録がなければDBに登録
                    $sql = 'INSERT INTO user (username, password, create_datetime) VALUE(?, ?, ?)';
                    $stmt = $dbh->prepare($sql);
                    $stmt->bindValue(1, $username, PDO::PARAM_STR);
                    $stmt->bindValue(2, $password, PDO::PARAM_STR);
                    $stmt->bindValue(3, $create_datetime, PDO::PARAM_STR);
                    $stmt-> execute();
                    $msg = 'ユーザー登録が完了しました。';
                }                
                try{
                //sql文作成(userから情報取得)
                $sql = 'SELECT
                            username,
                            userid,
                            type
                        FROM user
                        WHERE username = ? AND password = ?';
                $stmt = $dbh -> prepare($sql);
                $stmt -> bindValue(1, $username, PDO::PARAM_STR);
                $stmt -> bindValue(2, $password, PDO::PARAM_STR);
                $stmt -> execute();
                $rows = $stmt-> fetchAll(); 
                }catch(PDOException $e){
                        throw $e;
                }
                if(count($rows)>0){
                $_SESSION['userid'] = $rows[0]['userid'];
                $_SESSION['username'] = $rows[0]['username'];
                $_SESSION['type'] =$rows[0]['type'];
                header("location:./index.php");
                 exit;
                }else{
                $err_msg[] = 'usernameおよびpasswordが一致しません';
                }                
            }catch(PDOException $e){
                    throw $e;
            }
        }
    }
}catch(PDOException $e){
    $err_msg[] = '接続できませんでした。理由:'. $e->getMessage();
}
include_once '../view/signup_view.php';
?>