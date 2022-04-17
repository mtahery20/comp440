<?php
  session_start();
  include("config.php");

  $user_name = $_SESSION["login_user"];

  $qry_first = "SELECT first_name FROM user WHERE username = '$user_name'";
  $result_first = mysqli_query($conn, $qry_first);
  $row_1 = mysqli_fetch_array($result_first, MYSQLI_ASSOC);
  $first = $row_1['first_name'];

  echo "<h1><i> Welcome,&nbsp" . $first .".</i></h1>";
?>

<html lang="en">

    <head>
        <meta charset="utf-8">
        <title>View Blogs</title>
        <link href="input.css" rel="stylesheet" type="text/css">
    </head>

    <body>
        <br><button type="submit"><a href="newblog.php">New Blog Post</a></button><br>
        <div>
            <?php
                include("config.php");

                // get the total number of blogs
                $sql_count_blogs = "SELECT COUNT(*) FROM blogs";
                $num_blogs = mysqli_query($conn_comp440, $sql_count_blogs);
                $row_count = mysqli_fetch_array($num_blogs);
                $blog_count = $row_count[0];
                echo "There are $blog_count posts.";

                // get all the blogs
                $sql_get_blogs = "SELECT * FROM blogs";
                $result_blogs = mysqli_query($conn_comp440, $sql_get_blogs);

                // get all the tags
                $sql_get_tags = "SELECT * FROM tags";
                $result_tags = mysqli_query($conn_comp440, $sql_get_tags);

                // get all the comments

                if ($result_blogs->num_rows > 0) {
                    while($row = $result_blogs->fetch_assoc()) {
                        $blog_id = $row['blogId'];

                        echo "<div class='card'>";
                        echo "<div class='container'>";
                        echo "<p class='blog-title'>Title: " . $row["blogTitle"]. "</p>";
                        echo "<p class='blog-owner'>&nbsp by " . $row["ownerUsername"]. "</p>";
                        echo "<p class='date'>&nbsp&nbsp" . $row["datePosted"]. "</p>";
                        echo "<br><p class='blog-content'>" .$row["content"]. "</p>";

                        // add associated tags to blog post
                        $sql_get_tags = "SELECT tagTitle FROM tags WHERE tagId IN (SELECT tagId FROM blog_tags WHERE blogId=$blog_id)";
                        $result_tags = mysqli_query($conn_comp440, $sql_get_tags);
                        echo "<br><p class='tags'>Tags:";
                        if ($result_tags->num_rows > 0) {
                            while($row = $result_tags->fetch_assoc()) {
                                // echo "<p class='tags'>" .$row["tagTitle"]."</p>";
                                echo " '". $row["tagTitle"] . "' ";
                            }
                        }
                        echo "</p>";
                        echo "<hr>";

                        // add associated comments to blog post
                        $sql_get_comments = "SELECT content, datePosted, ownerUsername, sentiment FROM comments WHERE commentId IN (SELECT commentId FROM blog_comments WHERE blogId=$blog_id)";
                        $result_comments = mysqli_query($conn_comp440, $sql_get_comments);
                        echo "<br><p class='comment-section-title'>Comments: </p>";
                        if ($result_comments->num_rows > 0) {
                            while($row = $result_comments->fetch_assoc()) {
                                echo "<br>";
                                echo "<p class='comments'>&nbsp" .$row["ownerUsername"]. "</p>";
                                echo "<p class='tags'>&nbsp&nbsp" .$row["content"]. "</p>";
                                echo "<p class='date'>&nbsp&nbsp" .$row["datePosted"]. "</p>";
                            }
                        }
                        
                        echo "<button>Leave a comment</button>";
                        echo "</div>";
                        echo "</div>";
                        echo "<br>";
                    }
                }                
            ?>
        </div>
        <br>
        <h2><a href="homepage.php">Back to Homepage</a></h2> 
    </body>
</html>