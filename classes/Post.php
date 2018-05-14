<?php
class Post {
    public static function createPost($postbody, $loggedInUserId, $ProfileUserId) {
        if (strlen($postbody) > 160 || strlen($postbody) < 1) {
            die('Incorrect length of post');
        }
        $topics=self::getTopics($postbody);
        if ($ProfileUserId == $loggedInUserId) {
            if(Notify::createNotify($postbody)!=0){
                foreach(Notify::createNotify($postbody) as $key => $n){
                    $receiver = DB::query('SELECT id FROM users WHERE username=:username',array(':username'=>$key))[0]['id'];
                    if($receiver != 0){
                        DB::query('INSERT INTO notifications (type, receiver, sender, extra) VALUES (:type, :receiver, :sender, :extra)',array(':type'=>$n['type'], ':receiver'=>$receiver, ':sender'=>$loggedInUserId, 'extra'=>$n['extra']));
                    }
                }
            }
            
            DB::query('INSERT INTO posts (body , posted_at , user_id , topics) VALUES (:postbody , NOW() , :userid , :topics)', array(':postbody' => $postbody, ':userid' => $ProfileUserId, ':topics'=>$topics));
        } else {
            die('Incorrect User');
        }
    }
    
    public static function createImgPost($postbody, $loggedInUserId, $ProfileUserId) {
        if (strlen($postbody) > 160) {
            die('Incorrect length of post');
        }
        $topics=self::getTopics($postbody);
        if ($ProfileUserId == $loggedInUserId) {
             if(Notify::createNotify($postbody)!=0){
                foreach(Notify::createNotify($postbody) as $key => $n){
                    $receiver = DB::query('SELECT id FROM users WHERE username=:username',array(':username'=>$key))[0]['id'];
                    if($receiver != 0){
                        DB::query('INSERT INTO notifications (type, receiver, sender) VALUES (:type, :receiver, :sender, :extra)',array(':type'=>$n['type'], ':receiver'=>$receiver, ':sender'=>$loggedInUserId, 'extra'=>$n['extra']));
                    }
                }
            }
            DB::query('INSERT INTO posts (body , posted_at , user_id, topics) VALUES (:postbody , NOW() , :userid , :topics)', array(':postbody' => $postbody, ':userid' => $ProfileUserId, ':topics'=>$topics));
            $postId=DB::query('SELECT id FROM posts WHERE user_id=:userid ORDER BY id DESC LIMIT 1', array(':userid' => $loggedInUserId))[0]['id'];
            return $postId;
        } else {
            die('Incorrect User');
        }
    }
    
    public static function likePost($postId, $likerId) {
        if (!DB::query('SELECT user_id FROM post_likes WHERE post_id=:post_id AND user_id=:user_id', array(':post_id' => $postId, ':user_id' => $likerId))) {
            DB::query('UPDATE posts SET likes=likes+1 WHERE id=:postid', array('postid' => $postId));
            DB::query('INSERT INTO post_likes (post_id , user_id) VALUES (:post_id , :user_id)', array(':post_id' => $postId, ':user_id' => $likerId));
            Notify::createNotify("",$postId);
        } else {
            DB::query('UPDATE posts SET likes=likes-1 WHERE id=:postid', array('postid' => $postId));
            DB::query('DELETE FROM post_likes WHERE post_id=:post_id AND user_id=:user_id', array(':post_id' => $postId, ':user_id' => $likerId));
            
        }
    }
    
    
    public static function link_add($text){
        
        $text = explode(" ", $text);
        $newstring="";
        foreach($text as $word){
            if(substr($word, 0, 1)=="@"){
                $newstring .= "<a href='profile.php?username=".substr($word, 1)."'>".htmlspecialchars($word)." </a>";
            }else if(substr($word, 0, 1)=="#"){
                $newstring .= "<a href='topics.php?topic=".substr($word, 1)."'>".htmlspecialchars($word)." </a>";
            }else{
                $newstring .= htmlspecialchars($word)." ";
            }
        }
        return $newstring;
    }
    
    public static function getTopics($text){
        
        $topics="";
        $text = explode(" ", $text);
        foreach($text as $word){
            if(substr($word, 0, 1)=="#"){
                $topics .=substr($word, 1).",";
            }
        }
        return $topics;
    }
    
    public static function displayPosts($userid, $username, $loggedInUserId) {
        $dbposts = DB::query('SELECT * FROM posts WHERE user_id=:user_id ORDER BY id DESC', array(':user_id' => $userid));
        $posts = "";
        foreach ($dbposts as $p) {
            if (!DB::query('SELECT post_id FROM post_likes WHERE post_id=:post_id AND user_id=:user_id', array(':post_id' => $p['id'], ':user_id' => $loggedInUserId))) { //follower id because .. it's the logged in one defined above :D
                $posts.= "<img src='".$p['postimg']."'>".self::link_add($p['body']) . "
                <form action='profile.php?username=$username&postid=" . $p['id'] . "' method='post'>
                        <input type='submit' name='like' value='Like'>
                        <span>" . $p['likes'] . " Likes</span>";
                if($userid==$loggedInUserId){
                    $posts.= "<input type='submit' name='deletepost' value='X'>";
                }
                $posts.="</form>
                <hr><br>
                ";
            } else {
                $posts.= "<img src='".$p['postimg']."'>".self::link_add($p['body']) . "
                <form action='profile.php?username=$username&postid=" . $p['id'] . "' method='post'>
                        <input type='submit' name='unlike' value='UnLike'>
                        <span>" . $p['likes'] . " Likes</span>";
                if($userid==$loggedInUserId){
                    $posts.= "<input type='submit' name='deletepost' value='X'>";
                }
                $posts.="</form>
                <hr><br>
                ";
            }
        }
        return $posts;
    }
}
?>
