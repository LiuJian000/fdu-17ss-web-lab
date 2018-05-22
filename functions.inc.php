<?php
function generateLink($url, $label, $class) {
   $link = '<a href="' . $url . '" class="' . $class . '">';
   $link .= $label;
   $link .= '</a>';
   return $link;
}
function outputPostRow($number)  {
    include("travel-data.inc.php");
    $postId;
    $userId;
    $userName;
    $date;
    $thumb;
    $title;
    $excerpt;
    $reviewsNum;
    $reviewsRating;
    switch($number) {
        case 1:
            $postId = $postId1;
            $userId = $userId1;
            $userName = $userName1;
            $date = $date1;
            $thumb = $thumb1;
            $title = $title1;
            $excerpt = $excerpt1;
            $reviewsNum = $reviewsNum1;
            $reviewsRating = $reviewsRating1;
            break;
        case 2:
            $postId = $postId2;
            $userId = $userId2;
            $userName = $userName2;
            $date = $date2;
            $thumb = $thumb2;
            $title = $title2;
            $excerpt = $excerpt2;
            $reviewsNum = $reviewsNum2;
            $reviewsRating = $reviewsRating2;
            break;
        case 3:
            $postId = $postId3;
            $userId = $userId3;
            $userName = $userName3;
            $date = $date3;
            $thumb = $thumb3;
            $title = $title3;
            $excerpt = $excerpt3;
            $reviewsNum = $reviewsNum3;
            $reviewsRating = $reviewsRating3;
            break;
    }
    $temp = '<div class="row"><div class="col-md-4"><a href="post.php?id=';
    $temp .= $postId;
    $temp .= '" class=""><img src="images/';
    $temp .= $thumb;
    $temp .= '" alt="';
    $temp .= $title;
    $temp .= '" class="img-responsive"/></a></div><div class="col-md-8"><h2>';
    $temp .= $title;
    $temp .= '</h2><div class="details">Posted by <a href="user.php?id=';
    $temp .= $userId;
    $temp .= '" class="">';
    $temp .= $userName;
    $temp .= '</a><span class="pull-right">';
    $temp .= $date;
    $temp .= '</span><p class="ratings">';
    $temp .= constructRating($reviewsRating);
    $temp .= $reviewsNum;
    $temp .= ' Reviews</p></div><p class="excerpt">';
    $temp .= $excerpt;
    $temp .= '</p><p><a href="post.php?id=';
    $temp .= $postId;
    $temp .= '" class="btn btn-primary btn-sm">Read more</a></p></div></div><hr/>';
    echo $temp;
}
/*
  Function constructs a string containing the <img> tags necessary to display
  star images that reflect a rating out of 5
*/
function constructRating($rating) {
    $imgTags = "";

    // first output the gold stars
    for ($i=0; $i < $rating; $i++) {
        $imgTags .= '<img src="images/star-gold.svg" width="16" />';
    }

    // then fill remainder with white stars
    for ($i=$rating; $i < 5; $i++) {
        $imgTags .= '<img src="images/star-white.svg" width="16" />';
    }

    return $imgTags;
}
?>