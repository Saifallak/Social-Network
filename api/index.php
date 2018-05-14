<?php
require_once ("DB.php");
$db = new DB("127.0.0.1", "sushi_network", "root", "");
if ($_SERVER['REQUEST_METHOD'] == "GET") {
    
    if ($_GET['url'] == "auth") {
    
    } else if ($_GET['url'] == "users") {
    } else if ($_GET['url'] == "search") {

                $tosearch = explode(" ", $_GET['query']);
                if (count($tosearch) == 1) {
                        $tosearch = str_split($tosearch[0], 2);
                }

                $whereclause = "";
                $paramsarray = array(':body'=>'%'.$_GET['query'].'%');
                for ($i = 0; $i < count($tosearch); $i++) {
                        if ($i % 2) {
                        $whereclause .= " OR body LIKE :p$i ";
                        $paramsarray[":p$i"] = $tosearch[$i];
                        }
                }
                $posts = $db->query('SELECT posts.id, posts.body, users.username, posts.posted_at FROM posts, users WHERE users.id = posts.user_id AND posts.body LIKE :body '.$whereclause.' LIMIT 10', $paramsarray);
                //echo "<pre>";
                echo json_encode($posts);

    } else if ($_GET['url'] == "comments" && isset($_GET["postid"])) {
        $output="";
        $comments= $db->query('SELECT comments.comment, users.username FROM comments, users WHERE post_id=:post_id AND comments.user_id=users.id',array(':post_id'=>$_GET["postid"]));
        $output.="[";
        foreach($comments as $comment){
            $output .="{";
            $output .='"comment": "'.$comment['comment'].'",';
            $output .='"CommentedBy": "'.$comment['username'].'"';
            $output .="},";
        }
        if(strlen($output) != 1){
            $output=substr($output, 0 , strlen($output)-1);
        }
        $output.="]";
        echo $output;
        http_response_code(200);
        
    } else if ($_GET['url'] == "posts") {
        
        $token = $_COOKIE['SNID'];
        $userid = $db->query('SELECT user_id FROM login_tokens WHERE token=:token',array(':token'=>sha1($token)))[0]['user_id'];
        
        $followingposts = $db->query('SELECT posts.id, posts.body, posts.postimg, posts.posted_at, posts.likes, users.username FROM users, posts, followers 
                            WHERE followers.user_id=posts.user_id 
                            AND users.id=posts.user_id
                            AND follower_id=:userid
                            ORDER BY posts.posted_at DESC',array(':userid'=>$userid));
        $response="[";
        foreach ($followingposts as $post) {
            $response .= "{";
            $response .= '"PostId": '.$post['id'].',';
            $response .= '"PostBody": "'.htmlspecialchars($post['body']).'",';
            $response .= '"PostedBy": "'.$post['username'].'",';
            $response .= '"PostDate": "'.$post['posted_at'].'",';
            $response .= '"PostImg": "'.$post['postimg'].'",';
            $response .= '"Likes": '.$post['likes'].'';
            $response .= "},";
        }
        
        if(strlen($response)!=1){
            $response = substr($response, 0 , strlen($response)-1);
        }
        $response .="]";
        echo $response;
        http_response_code(200);
    } else if ($_GET['url'] == "profileposts") {
        
        $userid = $db->query('SELECT id FROM users WHERE username=:username',array(':username'=>$_GET['username']))[0]['id'];
        
        $followingposts = $db->query('SELECT posts.id, posts.body, posts.postimg, posts.posted_at, posts.likes, users.username FROM users, posts 
                            WHERE users.id=posts.user_id
                            AND posts.user_id=:userid
                            ORDER BY posts.posted_at DESC',array(':userid'=>$userid));
        $response="[";
        foreach ($followingposts as $post) {
            $response .= "{";
            $response .= '"PostId": '.$post['id'].',';
            $response .= '"PostBody": "'.htmlspecialchars($post['body']).'",';
            $response .= '"PostedBy": "'.$post['username'].'",';
            $response .= '"PostDate": "'.$post['posted_at'].'",';
            $response .= '"PostImg": "'.$post['postimg'].'",';
            $response .= '"Likes": '.$post['likes'].'';
            $response .= "},";
        }
        if(strlen($response)!=1){
            $response = substr($response, 0 , strlen($response)-1);
        }
        $response .="]";
        echo $response;
        http_response_code(200);
    }

} else if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if ($_GET['url'] == "users") {
        $postBody = file_get_contents("php://input");
        $postBody = json_decode($postBody);
        $username = $postBody->username;
        $email = $postBody->email;
        $password = $postBody->password;
        if (!$db->query('SELECT username FROM users WHERE username=:username', array(':username' => $username))) {
            if (strlen($username) >= 3 && strlen($username) <= 32) {
                if (preg_match('/[a-zA-Z0-9_]+/', $username)) {
                    if (strlen($password) >= 6 && strlen($password) <= 60) {
                        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            if (!$db->query('SELECT email FROM users WHERE email=:email', array(':email' => $email))) {
                                $db->query('INSERT INTO users (username, password, email)VALUES (:username, :password, :email)', array(':username' => $username, ':password' => password_hash($password, PASSWORD_BCRYPT), ':email' => $email));
                                echo '{ "Success": "User Created!" }';
                                http_response_code(200);
                            } else {
                                echo '{ "Error": "email exists" }';
                                http_response_code(409);
                            }
                        } else {
                            echo '{ "Error": "Invalid email" }';
                            http_response_code(409);
                        }
                    } else {
                        echo '{ "Error": "Invalid Password" }';
                        http_response_code(409);
                    }
                } else {
                    echo '{ "Error": "Invalid username" }';
                    http_response_code(409);
                }
            } else {
                echo '{ "Error": "Invalid username" }';
                http_response_code(409);
            }
        } else {
            echo '{ "Error": "Already exist" }';
            http_response_code(409);
        }
    }
    if ($_GET['url'] == "auth") {
        $postBody = file_get_contents("php://input");
        $postBody = json_decode($postBody);
        $username = $postBody->username;
        $password = $postBody->password;
        if ($db->query('SELECT username FROM users WHERE username=:username', array(':username' => $username))) {
            if (password_verify($password, $db->query('SELECT password FROM users WHERE username=:username', array(':username' => $username)) [0]['password'])) {
                $cstrong = True;
                $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
                $user_id = $db->query('SELECT id FROM users WHERE username=:username', array(':username' => $username)) [0]['id'];
                $db->query('INSERT INTO login_tokens (token, user_id) VALUES (:token, :user_id)', array(':token' => sha1($token), ':user_id' => $user_id));
                echo '{ "Token": "' . $token . '" }';
            } else {
                echo '{ "Error": "Invalid username or password!" }';
                http_response_code(401);
            }
        } else {
            echo '{ "Error": "Invalid username or password!" }';
            http_response_code(401);
        }
    }else if ($_GET['url'] == "likes") {
        $postId = $_GET['id'];
        $token = $_COOKIE['SNID'];
        $likerId = $db->query('SELECT user_id FROM login_tokens WHERE token=:token',array(':token'=>sha1($token)))[0]['user_id'];
        
         if (!$db->query('SELECT user_id FROM post_likes WHERE post_id=:post_id AND user_id=:user_id', array(':post_id' => $postId, ':user_id' => $likerId))) {
            $db->query('UPDATE posts SET likes=likes+1 WHERE id=:postid', array('postid' => $postId));
            $db->query('INSERT INTO post_likes (post_id , user_id) VALUES (:post_id , :user_id)', array(':post_id' => $postId, ':user_id' => $likerId));
            //Notify::createNotify("",$postId);
        } else {
            $db->query('UPDATE posts SET likes=likes-1 WHERE id=:postid', array('postid' => $postId));
            $db->query('DELETE FROM post_likes WHERE post_id=:post_id AND user_id=:user_id', array(':post_id' => $postId, ':user_id' => $likerId));
        }
        echo "{";
        echo '"Likes":';
        echo $db->query('SELECT likes FROM posts WHERE id=:postid',array(':postid'=>$postId))[0]['likes'];
        echo "}";
    }else if ($_GET['url'] == "addcomment") { //ADD COMMENTS
        $postBody = file_get_contents("php://input");
        $postBody = json_decode($postBody);
        $commentBody = $postBody->commentBody;
        $postId= $_GET['id'];
        
        if (strlen($commentBody) > 160 || strlen($commentBody) < 1) {
            echo 'Incorrect length of post';
            http_response_code(409);
        }
        if(!DB::query('SELECT id FROM posts WHERE id=:postId',array(':postId'=>$postId))){
            echo "Invalid Post ID";
            http_response_code(409);
        }else{
            DB::query('INSERT INTO comments (comment, posted_at, user_id, post_id) VALUES (:commentBody , NOW() , :userid , :post_id)', array(':commentBody' => $commentBody, ':userid' => $userId, ':post_id'=>$postId));
            echo "commented";
            http_response_code(200);
        }
    }
} else if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
    if ($_GET['url'] == "auth") {
        if (isset($_GET['token'])) {
            if ($db->query("SELECT token FROM login_tokens WHERE token=:token", array(':token' => sha1($_GET['token'])))) {
                $db->query('DELETE FROM login_tokens WHERE token=:token', array(':token' => sha1($_GET['token'])));
                echo '{ "Status": "Success" }';
                http_response_code(200);
            } else {
                echo '{ "Error": "Invalid token" }';
                http_response_code(400);
            }
        } else {
            echo '{ "Error": "Malformed request" }';
            http_response_code(400);
        }
    }
} else {
    http_response_code(405);
}
?>
