<?php
error_reporting(0);
require("functions.php");
require_once("databases.php");
$username_emailErr = $passwordErr = "";
$username_email = $password = $email = $username = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["username_email"])) {
    $errors["username_emailErr"] = "Username/E-mail is required";
  } else {
    $username_email = test_input($_POST["username_email"]);
  }

  if (empty($_POST["password"])) {
    $errors["passwordErr"] = "Password is required";
  } else {
    $password = test_input($_POST["password"]);
    // check if name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z ]*$/",$password)) {
      $passwordErr = "Only letters and white space allowed";
    }
  }

} 

if( isset($_POST["submit"]))
{
if(empty($errors)){
	$username = $_POST['username_email'];
	$email = $_POST['username_email'];
	$password = $_POST['password'];

	$found_user = attempt_login($username, $password, $email);
	if($found_user)
	{
        $user_id = $_SESSION["user_id"];
		if($_SESSION["user_type"] === "Admin" && $_SESSION["user_status"] === "Activated"){
            redirect("admin.php?id=$user_id");
        } else {
            $error_msg = "Sorry&nbsp;" . ucname($username) .", your account is temporarily deactivated by the admin.<br>";
        }

        if($_SESSION["user_type"] === "Member" && $_SESSION["user_status"] === "Activated") {
            redirect("member.php?id=$user_id");
        } else {
            $error_msg = "Sorry&nbsp;" . ucname($username) .", your account is temporarily deactivated by the admin.<br>";
        }

	} else {
        if(!$found_user){
            $error_msg = $_SESSION["error_msg"];
        }
    }

    }
}
?>

<html>
<body>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">


<?php if(isset($error_msg)){ echo $error_msg; }?>

<code>Username/E-mail:</code> <br>
<input type="text" name="username_email" value="<?php echo $username_email?>">
<?php echo $errors["username_emailErr"]; ?><span>
<br><br>
<code>Password:</code><br>
<input type="password" name="password" value="<?php echo $password?>">
<?php echo $errors["passwordErr"]; ?>
<br><br>
	  <input type="submit" name="submit" value="Submit">
</form>
<br><br>
<a href="reg.php">Registration</a>&nbsp;&nbsp;<a href="reset.php">Forgot Password</a>

</body>
</html> 