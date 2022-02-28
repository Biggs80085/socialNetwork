<?php
    session_start();
    require_once 'cnxpdo.php';
    $idcom = connexpdo("network", "myparam");

    if(!isset($_SESSION['auth'])){
        header('Location: index.php');
    exit();
    }
  
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Mes publications</title>
		<link rel="stylesheet" type="text/css" href="CSS/my_poste.css">
    
    <script src="https://code.jquery.com/jquery-1.12.3.js"   integrity="sha256-1XMpEtA4eKXNNpXcJ1pmMPs8JV+nwLdEqwiJeCQEkyc="   crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="JS/connect.js"></script>
  </head>
<body>
	<header>
		
    <?php 
   require_once("header.php");
   
   ?>
	</header>


	 <?php 
   require_once("side_left.php");
   require_once("side_right.php"); 
   ?>

	<article>

    <div id="display_pub"></div>

	</article>
  
</body>
  
<script>

  load_mypubli();

  setInterval(function(){
      load_mypubli();
      }, 5000);

  function load_mypubli()
  {
    $.ajax({
    url:"display_mypubli.php",
    method:"POST",
    success:function(data)
    {
      $('#display_pub').html(data);
    }
    })
  }


  $(document).on('click', '.like', function(){
      var id = $(this).data('id');
      $.ajax({
              url: 'add_like.php',
              type: 'POST',
              data:{
                id: id},
              success:function(data){
                load_mypubli();
              }
            });
      });

      $(document).on('click', '.unlike', function(){
        var id = $(this).data('id');

        $.ajax({
          url: 'dislike.php',
          type: 'POST',
          data:{id:id},
          success:function(data){
            load_mypubli();
          }
        });
      
      });

</script>

</html>


	