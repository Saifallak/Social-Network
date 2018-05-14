<?php
include ('./classes/DB.php');
include ('./classes/Login.php');
include ('./classes/Post.php');
include ('./classes/Image.php');
include ('./classes/Notify.php');

$username = "";
$isFollowing = false;
$verified = false;
if (isset($_GET['username'])) {
    if (DB::query('SELECT username FROM users WHERE username=:username', array(':username' => $_GET['username']))) {
        $username = DB::query('SELECT username FROM users WHERE username=:username', array(':username' => $_GET['username'])) [0]['username'];
        $userid = DB::query('SELECT id FROM users WHERE username=:username', array(':username' => $_GET['username'])) [0]['id'];
        $followerid = Login::isLoggedIn();
        $verified = DB::query('SELECT verified FROM users WHERE username=:username', array(':username' => $_GET['username'])) [0]['verified'];
        if (isset($_POST['follow'])) {
            if ($userid != $followerid) {
                if ($followerid == 0) {
                    DB::query('UPDATE users SET verified=1 WHERE id=:userid', array(':userid' => $userid));
                }
                if (!DB::query('SELECT follower_id FROM followers WHERE user_id=:user_id AND follower_id=:follower_id', array(':user_id' => $userid, 'follower_id' => $followerid))) {
                    DB::query('INSERT INTO followers (user_id, follower_id) VALUES (:user_id, :follower_id)', array(':user_id' => $userid, ':follower_id' => $followerid));
                } else {
                    echo "Already Following";
                }
                $isFollowing = true;
            }
        }
        if (isset($_POST['comment'])) {
            Comment::createComment($_POST['commentbody'], $_GET['postid'], $userid);
        }
        if (isset($_POST['unfollow'])) {
            if ($userid != $followerid) {
                if ($followerid == 0) {
                    DB::query('UPDATE users SET verified=0 WHERE id=:userid', array(':userid' => $userid));
                }
                if (DB::query('SELECT follower_id FROM followers WHERE user_id=:user_id AND follower_id=:follower_id', array(':user_id' => $userid, 'follower_id' => $followerid))) {
                    DB::query('DELETE FROM followers WHERE user_id=:user_id AND follower_id=:follower_id', array(':user_id' => $userid, ':follower_id' => $followerid));
                }
                $isFollowing = false;
            }
        }
        if (DB::query('SELECT follower_id FROM followers WHERE user_id=:user_id AND follower_id=:follower_id', array(':user_id' => $userid, 'follower_id' => $followerid))) {
            $isFollowing = true;
        }
        
        if(isset($_POST['deletepost'])){
            if(DB::query('SELECT id FROM posts WHERE id=:postid AND user_id=:userid',array(':postid'=>$_GET['postid'],':userid'=>$followerid))){
                DB::query('DELETE FROM posts WHERE id=:postid AND user_id=:userid',array(':postid'=>$_GET['postid'],':userid'=>$followerid));
                DB::query('DELETE FROM post_likes WHERE post_id=:postid',array(':postid'=>$_GET['postid']));
                echo "Post Deleted";
            }else{
                echo "Post doesn't exist";
            }
        }
        
        
        if (isset($_POST['post'])) {
            if($_FILES['postimg']['size']==0){
                Post::createPost($_POST['postbody'],Login::IsLoggedIn(),$userid);
            }else{
                $postId=Post::createImgPost($_POST['postbody'],Login::IsLoggedIn(),$userid);
                Image::uploadImage('postimg','UPDATE posts SET postimg=:postimg WHERE id=:post_id',array(':post_id'=>$postId));
            }
        }
        if (isset($_GET['postid']) && !isset($_POST['deletepost']) && !isset($_POST['comment'])) {
            Post::likePost($_GET['postid'],$followerid);
        }
        $posts=Post::displayPosts($userid,$username,$followerid);
    } else {
        die('user not found');
    }
}else{
    if(Login::isLoggedIn()){ // go to account profile if logged in
        $userid = Login::isLoggedIn();
        $username=DB::query('SELECT username FROM users WHERE id=:userid', array(':userid' => $userid))[0]['username'];
        header("Location: /SushiNetwork/profile.php?username=".$username.""); // new page
    }
    die("incorrect username");
}
?>

    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Social Network</title>
        <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
        <link rel="stylesheet" href="assets/css/Footer-Dark.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.1.1/aos.css">
        <link rel="stylesheet" href="assets/css/Login-Form-Clean.css">
        <link rel="stylesheet" href="assets/css/Navigation-Clean1.css">
        <link rel="stylesheet" href="assets/css/styles.css">
        <link rel="stylesheet" href="assets/css/untitled.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cookie">
        <link rel="stylesheet" href="assets/css/Pretty-Header.css">
        <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/css/Footer-Dark.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    </head>

    <body>
        <nav class="navbar navbar-light navbar-expand-md custom-header" style="width: 100%">
            <div class="container-fluid"><a class="navbar-brand" href="/SushiNetwork/">Sushi<span>Network</span> </a>
                <form>
                    <div class="searchbox"><i class="glyphicon glyphicon-search"></i>
                        <input class="form-control sbox" type="text">
                        <ul class="list-group autocomplete" style="position:absolute;width:100%; z-index: 100">
                        </ul>
                    </div>
                </form>
                <button class="navbar-toggler" data-toggle="collapse" data-target="#navbar-collapse"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbar-collapse">
                    <ul class="nav navbar-nav links">
                        <li class="nav-item" role="presentation"><a class="nav-link" href="/SushiNetwork/index.html">Timeline</a></li>
                        <li class="nav-item" role="presentation"><a class="nav-link" href="/SushiNetwork/my-messages.php">Messages</a></li>
                        <li class="nav-item" role="presentation"><a class="nav-link" href="/SushiNetwork/notify.php"> Notifications</a></li>
                    </ul>
                    <ul class="nav navbar-nav ml-auto">
                        <li class="dropdown">
                            <a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="#"> <img src="assets/img/avatar.jpg" class="dropdown-image"></a>
                            <div class="dropdown-menu dropdown-menu-right" role="menu"><a class="dropdown-item" role="presentation" href="/SushiNetwork/profile.php">Profile </a><a class="dropdown-item" role="presentation" href="/SushiNetwork/my-account.php">Settings </a><a class="dropdown-item" role="presentation" href="/SushiNetwork/logout.php">Logout </a></div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container">
            <h1><?php echo $username; ?>'s Profile <?php if ($verified) { echo '<i class="glyphicon glyphicon-ok-sign verified" data-toggle="tooltip" title="Verified User" style="font-size:28px;color:#da052b;"></i>'; } ?></h1></div>

        <div>
            <div class="container">
                <div class="row">
                    <div class="col-md-3">
                        <ul class="list-group">
                            <li class="list-group-item"><span><strong>About Me</strong></span>
                                <p>Welcome to my profile bla bla&nbsp;bla bla&nbsp;bla bla&nbsp;bla bla&nbsp;bla bla&nbsp;bla bla&nbsp;bla bla&nbsp;bla bla&nbsp;bla bla&nbsp;bla bla&nbsp;bla bla&nbsp;bla bla&nbsp;bla bla&nbsp;bla bla&nbsp;bla bla&nbsp;bla bla&nbsp;</p>
                            </li>
                        </ul>
                        <form action="profile.php?username=<?php echo $username; ?>" method="post">
                            <?php
                if ($userid != $followerid) {
                    if ($isFollowing) {
                        echo '<input type="submit" value="unFollow" name="unfollow">';
                    } else {
                        echo '<input type="submit" value="Follow" name="follow">';
                    }
                }
            ?>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-group">
                            <div class="timelineposts">

                            </div>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-default" type="button" onclick="showPostModal()" style="width:100%;background-image:url(&quot;none&quot;);background-color:#da052b;color:#fff;padding:16px 32px;margin:0px 0px 6px;border:none;box-shadow:none;text-shadow:none;opacity:0.9;text-transform:uppercase;font-weight:bold;font-size:13px;letter-spacing:0.4px;line-height:1;outline:none;">NEW POST</button>
                        <ul class="list-group"></ul>
                    </div>
                </div>
            </div>
        </div>
        <!--        START OF NEWPOST MODAL-->
        <div class="modal fade" role="dialog" id="newpost" tabindex="-1" style="padding-top:100px;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                        <h4 class="modal-title">New Post</h4></div>
                    <div style="max-height: 400px; overflow-y: auto">
                        <form action="profile.php?username=<?php echo $username; ?>" method="post" enctype="multipart/form-data">
                            <textarea name="postbody" rows="8" cols="80"></textarea>
                            <br />Upload an image:
                            <input type="file" name="postimg">
                            <input type="submit" name="post" value="Post">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!--        END OF NEWPOST MODAL-->
        <!--        START OF COMMENTS MODAL-->
        <div class="modal fade" role="dialog" id="commentsmodal" tabindex="-1" style="padding-top:100px;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
                        <h4 class="modal-title">Comments</h4></div>
                    <div class="modal-body" style="max-height: 400px; overflow-y: auto">
                        <p>The content of your modal.</p>
                    </div>
                    <!--
                    <form action='profile.php?postid="' <?php echo $post[ "id"] ?>'"' method='post'>
    <textarea name='commentbody' rows='3' cols='55'></textarea>
    <input type='submit' name='comment' value='Comment'>
</form>
-->
                    <div class="modal-footer">
                        <button class="btn btn-default" type="button" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!--        END OF COMMENTS MODAL-->
        <div class="footer-dark">
            <footer>
                <div class="container">
                    <p class="copyright">Social Network© 2016</p>
                </div>
            </footer>
        </div>
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/js/bs-animation.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.1.1/aos.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('.sbox').keyup(function() {
                    $('.autocomplete').html("")
                    $.ajax({

                        type: "GET",
                        url: "api/search?query=" + $(this).val(),
                        processData: false,
                        contentType: "application/json",
                        data: '',
                        success: function(r) {
                            r = JSON.parse(r)
                            for (var i = 0; i < r.length; i++) {
                                console.log(r[i].body)
                                $('.autocomplete').html(
                                    $('.autocomplete').html() +
                                    '<a href="profile.php?username=' + r[i].username + '#' + r[i].id + '"><li class="list-group-item"><span>' + r[i].body + '</span></li></a>'
                                )
                            }
                        },
                        error: function(r) {
                            console.log(r)
                        }
                    })
                })

                $.ajax({

                    type: "GET",
                    url: "api/profileposts?username=<?php echo $username; ?>",
                    processData: false,
                    contentType: "application/json",
                    data: '',
                    success: function(r) {
                        var posts = JSON.parse(r)
                        $.each(posts, function(index) {
                            if (posts[index].PostImg == "") {
                                $('.timelineposts').html(
                                    $('.timelineposts').html() +

                                    '<li class="list-group-item"><blockquote><p>' + posts[index].PostBody + '</p><footer>Posted by ' + posts[index].PostedBy + ' on ' + posts[index].PostDate + '<button class="btn btn-default" type="button" style="color:#eb3b60;background-image:url(&quot;none&quot;);background-color:transparent;" data-id=\"' + posts[index].PostId + '\"> <i class="glyphicon glyphicon-heart" data-aos="flip-right"></i><span> ' + posts[index].Likes + ' Likes</span></button><button class="btn btn-default comment" data-postid=\"' + posts[index].PostId + '\" type="button" style="color:#eb3b60;background-image:url(&quot;none&quot;);background-color:transparent;"><i class="glyphicon glyphicon-flash" style="color:#f9d616;"></i><span style="color:#f9d616;"> Comments</span></button></footer></blockquote></li>'
                                )
                            } else {
                                $('.timelineposts').html(
                                    $('.timelineposts').html() +

                                    '<li class="list-group-item"><blockquote><p>' + posts[index].PostBody + '</p><img src="" class="postimg" data-tempsrc="' + posts[index].PostImg + '" id="img' + posts[index].postId + '"><footer>Posted by ' + posts[index].PostedBy + ' on ' + posts[index].PostDate + '<button class="btn btn-default" type="button" style="color:#eb3b60;background-image:url(&quot;none&quot;);background-color:transparent;" data-id=\"' + posts[index].PostId + '\"> <i class="glyphicon glyphicon-heart" data-aos="flip-right"></i><span> ' + posts[index].Likes + ' Likes</span></button><button class="btn btn-default comment" data-postid=\"' + posts[index].PostId + '\" type="button" style="color:#eb3b60;background-image:url(&quot;none&quot;);background-color:transparent;"><i class="glyphicon glyphicon-flash" style="color:#f9d616;"></i><span style="color:#f9d616;"> Comments</span></button></footer></blockquote></li>'
                                )
                            }

                            $('[data-postid]').click(function() {
                                var buttonid = $(this).attr('data-postid');

                                $.ajax({

                                    type: "GET",
                                    url: "api/comments?postid=" + $(this).attr('data-postid'),
                                    processData: false,
                                    contentType: "application/json",
                                    data: '',
                                    success: function(r) {
                                        var res = JSON.parse(r)
                                        showCommentsModal(res);
                                    },
                                    error: function(r) {
                                        console.log(r)
                                    }

                                });
                            });

                            $('[data-id]').click(function() {
                                var buttonid = $(this).attr('data-id');
                                $.ajax({

                                    type: "POST",
                                    url: "api/likes?id=" + $(this).attr('data-id'),
                                    processData: false,
                                    contentType: "application/json",
                                    data: '',
                                    success: function(r) {
                                        var res = JSON.parse(r)
                                        $("[data-id='" + buttonid + "']").html(' <i class="glyphicon glyphicon-heart" data-aos="flip-right"></i><span> ' + res.Likes + ' Likes</span>')
                                    },
                                    error: function(r) {
                                        console.log(r)
                                    }

                                });
                            })
                        })

                        $('.postimg').each(function() {

                            this.src = $(this).attr('data-tempsrc')
                            this.onload = function() {
                                this.style.opacity = '1';
                                this.style.width = '100%';
                            }

                        })

                    },
                    error: function(r) {
                        console.log(r)
                    }

                });

            });

            function showPostModal() {
                $('#newpost').modal('show')
            }

            function showCommentsModal(res) {
                $('#commentsmodal').modal('show')
                var output = "";
                for (var i = 0; i < res.length; i++) {
                    output += res[i].comment;
                    output += " ~ ";
                    output += res[i].CommentedBy;
                    output += "<hr />";
                }

                $('.modal-body').html(output)
            }

        </script>
    </body>

    </html>
