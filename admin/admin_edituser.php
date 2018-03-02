<?php
require_once('phpscripts/config.php');
require_once('phpscripts/read.php');
require_once('phpscripts/user.php');

$id = $_SESSION['user_id'];
$tbl = 'tbl_user';
$col = 'user_id';
$result = getSingle($tbl,$col,$id);
$info = $result->fetch_assoc();
$username = $info['user_name'];
$email = $info['user_email'];
$fname = $info['user_fname'];

if(isset($_POST['submit'])){
  $username = trim($_POST['user_name']);
  $email = trim($_POST['user_email']);
  $fname = trim($_POST['user_fname']);
  $password = trim($_POST['user_pass']);
  $messages = "";

  if($username !== "" && $email !== "" && $fname !== ""){
    $messages = editUser($id,$fname,$username,$password,$email);
  }
}

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Edit User</title>
  </head>
  <body>
    <?php
    if(isset($messages)){
        echo $messages;
    } ?>

    <h1>Edit User</h1>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">

      <label for="user_name">Username:</label><br>
      <input type="text" name="user_name" <?php if(isset($username)){echo "value=".$username;} ?>><br><br>

      <label for="user_email">User Email:</label><br>
      <input type="email" name="user_email" <?php if(isset($email)){echo "value=".$email;} ?>><br><br>

      <label for="user_fname">User First Name:</label><br>
      <input type="text" name="user_fname" <?php if(isset($fname)){echo "value=".$fname;} ?>><br><br>

      <label for="user_pass">Password:</label><br>
      <input type="password" name="user_pass"><br><br>

      <input type="submit" name="submit" value="Save User">
    </div>
  </form>

  </body>
</html>
