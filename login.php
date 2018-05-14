<?php
include('classes/DB.php');

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if (DB::query('SELECT username FROM users WHERE username=:username', array(':username' => $username))) {
        if (password_verify($password, DB::query('SELECT password FROM users WHERE username=:username',array(':username'=>$username))[0]['password'])) {
            echo "logged in";
            $cstrong=true;
            $token = bin2hex(openssl_random_pseudo_bytes(64,$cstrong));
            $user_id=DB::query('SELECT id FROM users WHERE username=:username',array(':username'=>$username))[0]['id'];
            DB::query('INSERT INTO login_tokens (token, user_id) VALUES (:token, :user_id)',array(':token'=>sha1($token),':user_id'=>$user_id));
            setcookie("SNID",$token,time() + 60 * 60 *24 * 7,'/',NULL,NULL,TRUE);
            setcookie("SNID_",'1',time() + 60 * 60 *24 * 3,'/',NULL,NULL,TRUE);
            header("Location: /SushiNetwork/"); // go back to timeline
        } else {
            echo "incorrect password";
        }
    } else {
        echo "user doesn't exist";
    }
}

?>
    <!--
 <html>

<head>
    <title>SSN:Create A new Account</title>
    <link rel="stylesheet" href="CSS/style.css">
    <meta charset="utf-8">
</head>

<body>
    <div class="logForm">
        <h1>Login into your account</h1>
        <form action="login.php" method="post">
            <input type="text" name="username" placeholder="username...">
            <input type="password" name="password" placeholder="password...">
            <input type="submit" name="login" value="login">
        </form>
    </div>
</body>   </html>
-->


    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>SSN:Login</title>
        <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
        <link rel="stylesheet" href="assets/css/Login-Form-Dark.css">
        <link rel="stylesheet" href="assets/css/styles.css">
    </head>

    <body>
        <div class="login-dark">
            <form method="post" action="login.php">
                <h2 class="sr-only">Login Form</h2>
                <div class="illustration"><i class="icon ion-ios-locked-outline"></i></div>
                <div class="form-group">
                    <input class="form-control" type="text" id="username" name="username" placeholder="username" autofocus>
                </div>
                <div class="form-group">
                    <input class="form-control" type="password" id="password" name="password" placeholder="Password">
                </div>
                <div class="form-group">
                    <button class="btn btn-primary btn-block" id="login" name="login" type="submit">Log In</button>
                </div><a href="/SushiNetwork/forgot-password.php" class="forgot">Forgot your email or password?</a></form>
        </div>
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/js/bs-animation.js"></script>
        <!--
        <script>
    $('#login').click(function() {


        $.ajax({
            type: "POST",
            url: "api/auth",
            processData: false,
            contentType: "application/json",
            data: '{ "username": "' + $("#username").val() + '","password": "' + $("#password").val() + '" }',
            success: function(r) {
                console.log(r);
            },
            error: function(r) {
                setTimeout(function() {
                    $('[data-bs-hover-animate]').removeClass('animated ' + $('[data-bs-hover-animate]').attr('data-bs-hover-animate'));
                }, 2000)
                $('[data-bs-hover-animate]').addClass('animated ' + $('[data-bs-hover-animate]').attr('data-bs-hover-animate'))
                console.log(r)
            }

        });

    });
</script>
-->
    </body>

    </html>
