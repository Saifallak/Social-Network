<?php
include ('./classes/DB.php');
include ('./classes/Login.php');
include ('./classes/Post.php');
include ('./classes/Comment.php');
include ('./classes/Notify.php');

$showTimeLine = false;
if (Login::isLoggedIn()) {
    echo 'Logged In as ID:' . Login::isLoggedIn() . "<br>";
    $userid = Login::isLoggedIn();
    $showTimeLine = true;
    header("Location: /SushiNetwork/index.html"); // new page for signed in users
} else {
    header("Location: /SushiNetwork/create-account.html"); // new page sign up
    die( 'Not logged in <br>' );
}
if (isset($_GET['postid']) && !isset($_POST['comment'])) {
    Post::likePost($_GET['postid'], $userid);
}
if (isset($_POST['comment'])) {
    Comment::createComment($_POST['commentbody'], $_GET['postid'], $userid);
}
if (isset($_POST['searchbox'])) {
    echo "NOT Developed Yet Vid29";
    //$users=DB::query('SELECT users.username FROM users WHERE user.username LIKE :username',array(':username'=>'%'.$_POST['searchbox'].'%'));
}



?>

    <form action="index.php" method="post">
        <input type="search" name="searchbox">
        <input type="submit" name="search" value="Search">
    </form>

    <?php
$followingposts = DB::query('SELECT posts.id, posts.body, posts.likes, users.username FROM users, posts, followers 
                            WHERE posts.user_id=followers.user_id 
                            AND users.id=posts.user_id 
                            AND follower_id=:userid 
                            ORDER BY posts.posted_at DESC',array(':userid'=>$userid));
foreach ($followingposts as $post) {
    echo htmlspecialchars($post['body']) . " ~ " . $post['likes'] . " Likes BY " . $post['username'] . "<br>";
    echo "<form action='index.php?postid=" . $post['id'] . "' method='post'>";
    if (!DB::query('SELECT post_id FROM post_likes WHERE post_id=:post_id AND user_id=:user_id', array(':post_id' => $post['id'], ':user_id' => $userid))) {
        echo "<input type='submit' name='like' value='Like'>";
    } else {
        echo "<input type='submit' name='unlike' value='unLike'>";
    }
    echo "<span>" . $post['likes'] . " Likes</span>
                </form>
                <form action='index.php?postid=".$post['id']."' method='post'>
                <textarea name='commentbody' rows='3' cols='55'></textarea>
                <input type='submit' name='comment' value='Comment'>
                </form>";
    Comment::displayComments($post['id']);
    echo"
                <hr><br>
                ";
}
?>


        <html>

        <head>
            <title>SSN: TimeLine</title>
            <meta charset="utf-8">
        </head>

        <body>
            <h1>INDEX PAGE</h1>
        </body>

        </html>
