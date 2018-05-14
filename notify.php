<?php
include ('./classes/DB.php');
include ('./classes/Login.php');

if (Login::isLoggedIn()) {
    $userid = Login::isLoggedIn();
} else {
    die ('Not logged in <br>');
}
echo "<h1>Notifications</h1>";
if(DB::query('SELECT * FROM notifications WHERE receiver=:userid',array(':userid'=>$userid))){
    $notifications = DB::query('SELECT * FROM notifications WHERE receiver=:userid ORDER BY id DESC',array(':userid'=>$userid));
    
    foreach ($notifications as $n){
        if($n['type']==1){
            $sender=DB::query('SELECT username FROM users WHERE id=:senderid',array(':senderid'=>$n['sender']))[0]['username'];
            if($n['extra']==""){
                echo "Unknown Notification<hr>";
            }else{
            $extra = json_decode($n['extra']);
            echo $sender." Mentioned you in a post - ".$extra->postbody."<hr>";
            }
        }else if($n['type']==2){
            $sender=DB::query('SELECT username FROM users WHERE id=:senderid',array(':senderid'=>$n['sender']))[0]['username'];
            echo $sender." Liked your post <hr>";
        }
    }
}else{
    echo 'No Notifications Check Back Later.. Thanks for using Sushi Network';
}

?>
