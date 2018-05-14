<?php
//include('classes/DB.php');
header("Location: /SushiNetwork/create-account.html"); // new page

//
//if (isset($_POST['createaccount'])) {
//    $username = $_POST['username'];
//    $password = $_POST['password'];
//    $email    = $_POST['email'];
//    
//    if (!DB::query('SELECT username FROM users WHERE username=:username', array(':username' => $username))) {
//        
//        if (strlen($username) >= 3 && strlen($username) <= 32) {
//            
//            if (preg_match('/[a-zA-Z0-9_]+/', $username)) {
//                if (strlen($password) >= 6 && strlen($password) <= 60) {
//                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
//                        if (!DB::query('SELECT email FROM users WHERE email=:email', array(':email' => $email))) {
//                            DB::query('INSERT INTO users (username, password, email)VALUES (:username, :password, :email)', array(
//                                ':username' => $username,
//                                ':password' => password_hash($password, PASSWORD_BCRYPT),
//                                ':email' => $email
//                            ));
//                            echo "Success"; // ECHO
//                        } else {
//                            echo "email exists"; //ECHO
//                        }
//                    } else {
//                        echo "invalid email"; // ECHO
//                    }
//                } else {
//                    echo "Invalid password"; // ECHO
//                }
//            } else {
//                echo "Invalid username"; // ECHO
//            }
//        } else {
//            echo "Invalid username"; // ECHO
//        }
//    } else {
//        echo "Already exist"; // ECHO
//    }
//}

?>
    <!--

    <html>

    <head>
        <title>SSN:Create A new Account</title>
        <meta charset="utf-8">
    </head>

    <body>
        <div class="regForm">
            <h1>Register here</h1>
            <form action="create-account.php" method="post">
                <input type="text" name="username" placeholder="username....">
                <input type="password" name="password" placeholder="password....">
                <input type="email" name="email" placeholder="name@company.com">
                <input type="submit" name="createaccount" value="Create Account">
            </form>
        </div>
    </body>

    </html>
-->
