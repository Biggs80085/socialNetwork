<?php

    session_start();
    require 'function.php';
    include("cnxpdo.php");
    $idcom = connexpdo("network", "myparam");

    if(!isset($_SESSION['auth'])){
      header('Location: index.php');
      exit();
    }

    if(isset($_GET['id'])){
      $id_post = htmlentities($_GET['id']);

      $reqPost = $idcom->prepare("SELECT * 
      FROM publication p, user u
      WHERE p.id_poster = u.Id
      AND p.id_pub = ?");
      $reqPost->execute(array($id_post));
      $post = $reqPost->fetch();
    }

    $photo = '';
    /** PHOTO */
    if(!empty($post['photo'])){
      $photo = '<img src="img/post/'. $post['photo'] .'" class="user_pub_img">';
    }
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Messagerie</title>
    <link rel="stylesheet" type="text/css" href="CSS/post2.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="JS/connect.js"></script>
  </head>
<body>
  <header>
    <?php require_once("header.php"); ?>
  </header>

  <?php require_once("side_left.php"); require_once("side_right.php"); ?>

  <article>

    <div class="publication">
      <div class="post">
        <div class="user_pub">
          <a href="#"><img class="user_photo_pub" src="img/profil/<?= $post['profil_photo'] ?>">
            <?php if($_SESSION['auth']['mode']==0){ ?>
              <span class="user_name_pub"><?= $post['Name']. " " .$post['LastName'] ?></span>
            <?php }else{ ?>
              <span class="user_name_pub" style="color:white"><?= $post['Name']. " " .$post['LastName'] ?></span>
            <?php } ?>
          </a>
          <p class="user_date_pub"><?= disDate($post['post_date']) ?><p> 
        </div>

        <div class="user_pub_text">
          <?php if($_SESSION['auth']['mode']==0){ ?>
            <p><?= $post['content'] ?></p> 
          <?php }else{ ?>
            <p style='color:white'><?= $post['content'] ?></p> 
          <?php } ?>
          <?= $photo ?>
        </div>

        <div id="button"></div>
          <button class="user_pub_commentaire">
            <?php if($_SESSION['auth']['mode']==0){ ?>
              <img src="img/chat-quote-fill.svg" width="22" height="22"><p>Commentaires</p>
            <?php }else{ ?>
              <img src="img/chat-quote-fill.png" width="22" height="22"><p style="color:white">Commentaires</p> 
            <?php } ?>
          </button>
        </div>

        <div class="comment">
          <div id="display_comment"></div>
        </div>

        <form method="POST" id="comment_form">
          <?php if($_SESSION['auth']['mode']==0){ ?>
            <TEXTAREA name="comment_content" id="comment_content" PLACEHOLDER="Ecrivez un commentaire ..."></TEXTAREA>
          <?php }else{ ?>
            <TEXTAREA name="comment_content" id="comment_content" PLACEHOLDER="Ecrivez un commentaire ..." style="color:white"></TEXTAREA>
          <?php } ?>
       
          <input type="hidden" name="post_id" id="post_id" value="<?= $id_post ?>" />
          <input type="submit" name="com" id="com" class="envoie" value="" />
          <label for="com"></label>
        </form>
      </div>
    </div>
    
  </article>

</body>

<script>
$(document).ready(function(){
  
  $('#comment_form').on('submit', function(event){
    event.preventDefault();
    var form_data = $(this).serialize();
    $.ajax({
      url:"add_comment.php",
      method:"POST",
      data: form_data,
      success:function(data){
        if(data.error != ''){
          $('#comment_form')[0].reset();
          load_comment();
        }
      }
    });
  });

  load_comment();
  setInterval(function(){
    load_comment();
  }, 5000);

  function load_comment(){
    var id_post;
    id_post = <?= $id_post ?>;
    $.ajax({
      url:"display_comment.php",
      method:"POST",
      data: {id_post: id_post},
      dataType:"json",
      success:function(data){
        $('#display_comment').html(data.comment);
        $('#button').html(data.button);
      }
    });
  }

  $(document).on('click', '.likes', function(){
    var id = $(this).data('id');
    $.ajax({
      url: 'add_like.php',
      type: 'POST',
      data:{
        id: id},
      success:function(data){
        load_comment();
      }
    });
  });

    $(document).on('click', '.unlikes', function(){
      var id = $(this).data('id');
      $.ajax({
        url: 'dislike.php',
        type: 'POST',
        data:{id:id},
        success:function(data){
          load_comment();
        }
      });
    });
});
</script>

</html>

