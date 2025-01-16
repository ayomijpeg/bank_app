<?php
if(!isset($_SESSION['customer_id'])){
header("Location:login.php?error=this page requires a login");
 die();
}
?>