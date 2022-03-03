<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
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
        <div class="login">
            <form method="post">
                <p>username:<input type="text" name="username"></p>
                <p>password:<input type="text" name="password"></p>
                <input class="login_button" type="submit" name="login" value="login">
            </form>
            <div class="to_signup"><a href="./signup.php">new account</a></div>
        </div>
        </div>
    </main>
    <footer>
        <p><small>&copy; 2022 beanshop</small></p>
    </footer>
</body>
</html>