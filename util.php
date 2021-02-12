<?php
function flashMessages(){
    if(isset($_SESSION['success'])){
      echo '<span style="color:green;text-align:center;">'.$_SESSION['success'].'</span>';
      unset($_SESSION['success']);
    }
    if(isset($_SESSION['error'])){
      echo '<span style="color:red;text-align:center;">'.$_SESSION['error'].'</span>';
      unset($_SESSION['error']);
    }
}  
?>