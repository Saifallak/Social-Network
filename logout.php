<?php
include ('classes/DB.php');
include ('classes/Login.php');
if (!Login::isLoggedIn()) {
    header("Location: /SushiNetwork/");
}
if (isset($_POST['confirm'])) {
    if (isset($_POST['alldevices'])) {
        DB::query('DELETE FROM login_tokens WHERE user_id=:userid', array(':userid' => Login::isLoggedIn()));
    } else {
        if (isset($_COOKIE['SNID'])) {
            DB::query('DELETE FROM login_tokens WHERE token=:token', array(':token' => sha1($_COOKIE['SNID'])));
        }
        setcookie('SNID', '1', time() - 3600);
        setcookie('SNID_', '1', time() - 3600);
    }
    header("Location: /SushiNetwork/"); // go back to timeline
}
?>
    <html>

    <head>
        <title>SSN:Logout from Account</title>
        <link rel="stylesheet" href="CSS/style.css">
        <meta charset="utf-8">
    </head>

    <body>
        <div class="logForm">
            <h1>Are you sure you wanna logout?!</h1>
            <form action="logout.php" method="post">
                <label>Check Box to logout from all devices!</label>
                <input type="checkbox" name="alldevices" value="alldevices">
                <input type="submit" name="confirm" value="Confirm">
            </form>
        </div>
    </body>

    </html>
