<?php
include ('./classes/DB.php');
include ('./classes/Login.php');
$tokenIsValid = false;
if (Login::isLoggedIn()) {
    if (isset($_POST['changepassword'])) {
        $oldpassword = $_POST['oldpassword'];
        $newpassword = $_POST['newpassword'];
        $newpasswordrepeat = $_POST['newpasswordrepeat'];
        $userid = Login::isLoggedIn();
        if (password_verify($oldpassword, DB::query('SELECT password FROM users WHERE id=:userid', array(':userid' => $userid)) [0]['password'])) {
            if ($newpassword == $newpasswordrepeat) {
                if (strlen($newpassword) >= 6 && strlen($newpassword) <= 60) {
                    DB::query('UPDATE users SET password=:password WHERE id=:userid', array(':password' => password_hash($newpassword, PASSWORD_BCRYPT), ':userid' => $userid));
                    echo "password change successfly";
                } else {
                    echo "new password isn't correct";
                }
            } else {
                echo "new password don't not match";
            }
        } else {
            echo "Incorrect Old Password";
        }
    }
} else {
    if (isset($_GET['token'])) {
        $token = $_GET['token'];
        if (DB::query('SELECT user_id FROM password_tokens WHERE token=:token', array(':token' => sha1($token)))) {
            $userid = DB::query('SELECT user_id FROM password_tokens WHERE token=:token', array(':token' => sha1($token))) [0]['user_id'];
            $tokenIsValid = true;
            if (isset($_POST['changepassword'])) {
                $newpassword = $_POST['newpassword'];
                $newpasswordrepeat = $_POST['newpasswordrepeat'];
                if ($newpassword == $newpasswordrepeat) {
                    if (strlen($newpassword) >= 6 && strlen($newpassword) <= 60) {
                        DB::query('UPDATE users SET password=:password WHERE id=:userid', array(':password' => password_hash($newpassword, PASSWORD_BCRYPT), ':userid' => $userid));
                        DB::query('DELETE FROM password_tokens WHERE user_id=:user_id',array(':user_id'=>$userid));
                        echo "password change successfly";
                    } else {
                        echo "new password isn't correct";
                    }
                } else {
                    echo "new password don't not match";
                }
            }
        } else {
            die("token invalid");
        }
    } else {
        die('Not logged in');
    }
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
            <h1>Change your account Pass</h1>
            <form action="<?php if (!$tokenIsValid) {echo 'change-password.php';} else {echo 'change-password.php?token=' . $token . '';} ?>" method="post">
                <?php if (!$tokenIsValid) {echo '<input type="password" name="oldpassword" placeholder="your current password...">';} ?>
                    <input type="password" name="newpassword" placeholder="new password...">
                    <input type="password" name="newpasswordrepeat" placeholder="repeat-new password...">
                    <input type="submit" name="changepassword" value="Change Password">
            </form>
        </div>
    </body>

    </html>
