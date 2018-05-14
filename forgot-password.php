<?php
include ('./classes/DB.php');
if(isset($_POST['resetpassword'])){
    $email=$_POST['email'];
    $cstrong=true;
    $token = bin2hex(openssl_random_pseudo_bytes(64,$cstrong));
    $user_id=DB::query('SELECT id FROM users WHERE email=:email',array(':email'=>$email))[0]['id'];
    DB::query('INSERT INTO password_tokens (token, user_id) VALUES (:token, :user_id)',array(':token'=>sha1($token),':user_id'=>$user_id));
    echo "email sent<br>";
    $url="Location: /SushiNetwork/change-password.php?token=".$token."";
    header($url); // new page
}
?>


    <html>

    <head>
        <title>SSN:Change Acc Pass</title>
        <link rel="stylesheet" href="CSS/style.css">
        <meta charset="utf-8">
    </head>

    <body>
        <div class="logForm">
            <h1>Forgot your account Pass</h1>
            <form action="forgot-password.php" method="post">
                <input type="email" name="email" placeholder="user@company.com">
                <input type="submit" name="resetpassword" value="Reset Password">
            </form>
        </div>
    </body>

    </html>
