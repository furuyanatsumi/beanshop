<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>signup</title>
    <link rel="stylesheet" href="../view/css/style.css">
</head>
<body>
<header class="page_header1">
        <h1><a href="./index.php"><img class="logo" src="../view/images/logo.png" alt="logo">beanshop</a></h1>
    </header>
    <main>
        <div class="login_container">
            <?php foreach($err_msg as $err){ ?>
                <p class="err"><?php print $err; ?></p>
            <?php } ?>
            <?php print htmlspecialchars($msg, ENT_QUOTES, 'utf-8'); ?>
            <div class="login">
                <form method="post">
                    <p>username:<input type="text" name="username"></p>
                    <p>password:<input type="password" name="password"></p>
                    <input type="submit" name="signup" value="signup">
                </form>
                <div class="attention"><p>※「username」「password」は半角英数字6文字以上で入力してください</p></div>
                <div class="to_login"><a href="./login.php">ログイン画面に戻る</a></div>
            </div>
        </div>
    </main>
    <footer>
        <p><small>&copy; 2022 beanshop</small></p>
    </footer>
</body>
</html>