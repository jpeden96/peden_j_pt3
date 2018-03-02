<?php
  function createUser($fname, $username, $password, $email, $lvllist) {
    include('connect.php');

    $hashedpassword = password_hash($password, PASSWORD_DEFAULT);

    $userstring = "INSERT INTO `tbl_user` (`user_id`, `user_fname`, `user_name`, `user_pass`, `user_email`, `user_date`, `user_ip`, `user_last`, `user_fail`) VALUES (NULL, '{$fname}', '{$username}', '{$hashedpassword}', '{$email}', CURRENT_TIMESTAMP, 'no', CURRENT_TIMESTAMP, '{$lvllist}')";

    // echo $userstring;
    // die;

  $userquery = mysqli_query($link, $userstring);
  if($userquery) {
    redirect_to('admin_index.php');
  }else{
    $message = "Sorry, there were issues with your attempt to create a user. Please try again later.";
    return $message;
  }
}

  function editUser($id,$fname,$username,$password,$email){
    include('connect.php');
    if(empty($password)){ //if pass is empty - won't change
      $updateString = "UPDATE `tbl_user` SET `user_fname`='{$fname}',`user_name`='{$username}',`user_email`='{$email}' WHERE `user_id` = {$id}";
    }else{ //does change it
      $password = password_hash($password,PASSWORD_DEFAULT);
      $updateString = "UPDATE `tbl_user` SET `user_fname`='{$fname}',`user_name`='{$username}',`user_pass`='{$password}',`user_email`='{$email}' WHERE `user_id` = {$id}";
    }
  $queryCall = mysqli_query($link,$updateString);
  if($queryCall){
    $_SESSION['user_name'] = $fname;
    redirect_to('admin_index.php');
  }else{
    return "Something went wrong - please try again.";
  }
  }
 ?>
