<?php
class Comment{
    public static function createComment($commentBody, $postId, $userId) {
        if (strlen($commentBody) > 160 || strlen($commentBody) < 1) {
            die('Incorrect length of post');
        }
        if(!DB::query('SELECT id FROM posts WHERE id=:postId',array(':postId'=>$postId))){
            echo "Invalid Post ID";
        }else{
            DB::query('INSERT INTO comments (comment, posted_at, user_id, post_id) VALUES (:commentBody , NOW() , :userid , :post_id)', array(':commentBody' => $commentBody, ':userid' => $userId, ':post_id'=>$postId));
        }
    }
    
    public static function displayComments($postId){
        $comments= DB::query('SELECT comments.comment, users.username FROM comments, users 
        WHERE post_id=:post_id AND comments.user_id=users.id',array(':post_id'=>$postId));
        foreach($comments as $comment){
            echo $comment['comment']."~".$comment['username']."<br>";
        }
    }
}

?>
