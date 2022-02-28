<?php 
      if($_SESSION['auth']['mode'] == 0){ 
            echo "<link rel='stylesheet' type='text/css' href='CSS/lightMode.css'>" ;
      }elseif($_SESSION['auth']['mode'] == 1){ 
            echo "<link rel='stylesheet' type='text/css' href='CSS/darkMode.css'>" ;
      } 
 
 ?>