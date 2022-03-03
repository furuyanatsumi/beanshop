<?php
require_once '../const.php';
session_start();
$session_name = session_name();
$_SESSION = array();
 
if (isset($_COOKIE[$session_name])) {
  $params = session_get_cookie_params();
 
  // session無効化
  setcookie($session_name, '', time() - 42000,
    $params["path"], $params["domain"],
    $params["secure"], $params["httponly"]
  );
}
// セッションIDを無効化
session_destroy();
header('Location: login.php');
exit;
?>