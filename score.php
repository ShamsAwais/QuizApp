<?php
  session_start(); 

  if (!isset($_SESSION['username'])) {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: login.php');
  }
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['username']);
  	header("location: login.php");
  }
?>
<html>
    <head>
        <style>
            body{
                background-color:coral;
                text-align:center;
            }
            
progress {
    width: 70%;
    height: 30px;
    color: #3fb6b2;
    background: #efefef;
}
progress::-webkit-progress-bar {
    background: #efefef;
}
progress::-webkit-progress-value {
    background: #3fb6b2;
} 
progress::-moz-progress-bar {
    color: #3fb6b2;
    background: #efefef;
}

        </style>
    </head>
    <body>
<?php  if (isset($_SESSION['username'])) : ?>
    	<?php $uname=$_SESSION['username']; ?>
	<?php endif ?>
<?php $score=$_GET["score"];
if($score < 50)
{
    echo '<h1>You need to score at least 50% to pass the exam.</h1>';
}
else {
    echo '<script>alert("Congratulations you passed!");</script>';
    echo '<h1>You have passed the exam and scored '.$score.'%.</h1>';
    echo '<h2>Score</h2>';
    echo '<progress min="0" max="100" value="'.$score.'"></progress>';
     $host     = 'localhost';
 $username = 'root';
 $password = '';
 $database = 'registration';
 $db;
try {
	$db = new mysqli($host, $username, $password, $database);
	}catch (Exception $e){
	$error = $e->getMessage();
	echo $error;
    }
    $sql="UPDATE users SET score='".$score."' WHERE username='".$uname."'";
    if($db->query($sql)===TRUE){
        //echo "record updated successfully";
    } 
    else{
        //echo "error in updating";
    }
}
?>