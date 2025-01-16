<?php 
session_start();
include('../include/admin_auth.php');
include('../include/db.php');
if(isset($_POST['submit'])){
	$error=array();
	if(empty($_POST['account_name'])){
		$error['account_name']="Please Enter Name";
		}
		if(empty($_POST['account_balance'])){
			$error['account_balance']="Please Enter Account Balance";
			}
			if(empty($_POST['account_type'])){
				$error['account_type']="Please enter account Type";
				}
			if(!is_numeric($_POST['account_balance'])){
		$error['account_balance']="Numeric Value Required";		
		}
		if(empty($error)){
			$account = "309".rand(1000000, 9999999);
			$stmt=$conn->prepare("INSERT INTO customer VALUES(NULL,:anm,:an,:at,:ab,NOW(),NOW() )");
			$data=array(
			":anm"=>$_POST['account_name'],
			":an"=>$account,
			":at"=>$_POST['account_type'],
			":ab"=>$_POST['account_balance']
			);
			$stmt->execute($data);
			header("Location:view_accounts.php");
			//echo $account;
			}
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Create Account</title>
</head>

<body>
<?php include('../include/admin_header.php');?>
<form action="" method="post">
<p> Account Name <input type="text" name="account_name" /></p>
<p>Account Balance <input type="text" name="account_balance" /></p>
<select name="account_type"> 
<option disabled selected>--Select Account Type--</option>
<option value="Savings">--Savings--</option>
<option value="Current">--Current--</option>
</select>
<br/>
<p><input type="submit" name="submit" value="create  account"/></p>
<br />

</form>
</body>
</html>