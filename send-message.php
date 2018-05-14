<?php
include ('./classes/DB.php');
include ('./classes/Login.php');
session_start();
$cstrong=true;
$token = bin2hex(openssl_random_pseudo_bytes(64,$cstrong));

if(!isset($_SESSION['token'])){
    $_SESSION['token']=$token;
}
if (Login::isLoggedIn()) {
    $userid = Login::isLoggedIn();
    
} else {
    die('Not logged in');
}

if(isset($_POST['send'])){

    if(isset($_POST['nocsrf'])!=$_SESSION['token']){
        die("INVALID! Token!");
    }
    if(!isset($_POST['nocsrf'])){
        die("INVALID Token!");
    }
    if(DB::query("SELECT id FROM users WHERE id=:userid",array(':userid'=>htmlspecialchars($_GET['receiver'])))){
        DB::query("INSERT INTO messages(body,receiver,sender) VALUES(:body, :receiver, :sender)",array(':body'=>$_POST['body'],':receiver'=>htmlspecialchars($_GET['receiver']),':sender'=>$userid));
        echo "SENT";
    }else{
        die ("Wrong receiver");
    }
    session_destroy();
}

?>

    <form action="send-message.php?receiver=<?php echo htmlspecialchars($_GET['receiver']); ?>" method="post" enctype="multipart/form-data">
        <label>SEND MESSAGE</label>
        <br>
        <textarea name="body" rows="8" cols="55"></textarea>
        <br>
        <input type="hidden" value="<?php echo $token; ?>" name="nocsrf">
        <input type="submit" name="send" value="Send Message">
    </form>
