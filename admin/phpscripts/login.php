<?php

  function logIn($username, $password, $ip) {
    require_once('connect.php');
    $username = mysqli_real_escape_string($link, $username);
    $password = mysqli_real_escape_string($link, $password);
    $loginstring = "SELECT * FROM tbl_user WHERE user_name='{$username}'"; //
    //echo $loginstring;
    $user_set = mysqli_query($link, $loginstring);
    // echo mysqli_num_rows($user_set);
    // die;
    if(mysqli_num_rows($user_set)){
      $founduser = mysqli_fetch_array($user_set, MYSQLI_ASSOC);
      $id = $founduser['user_id'];
      if(password_verify($password,$founduser['user_pass']) && ($founduser['user_fail'] < 3)){ //separating the password and user login function

        //echo $id;

        date_default_timezone_set('America/Toronto'); //setting the timestamp to where ever the user is logging in from
        $date = date("Y-m-d h:i:s");

        $_SESSION['user_id'] = $id;
        $_SESSION['user_name'] = $founduser['user_fname'];
        $_SESSION['user_last'] = $founduser['user_last'];
        if(mysqli_query($link, $loginstring)){
          $update = "UPDATE tbl_user SET user_ip='{$ip}' WHERE user_id={$id}";
          $updatequery = mysqli_query($link, $update);

          $updateLast = "UPDATE tbl_user SET user_last='{$date}' WHERE user_id={$id}";
          $updateLastQuery = mysqli_query($link, $updateLast);

          $failLogin = "UPDATE tbl_user SET user_fail = 0 WHERE user_id = {$id}";
          $fail_attempt = mysqli_query($link, $failLogin);

          $num_logins = $founduser['user_num_login'];
          // var_dump($num_logins);
          // die;
          if($num_logins == 0){ //first login - redirect to edit user
            $numLoginQuery = "UPDATE tbl_user SET user_num_login = 1 WHERE user_id = {$id}";
            $numLoginResult = mysqli_query($link, $numLoginQuery);
            redirect_to('admin_edituser.php');
          }else{ //more than first login - go to index page
            redirect_to('admin_index.php');
          }

          $created = new DateTime($founduser['user_date']);
          $timeLimit = $created->modify("+2 days"); // add two days
          $now = new DateTime();

          //format as unix timestamps
          $timeLimit = $timeLimit->format('U');
          $now = $now->format('U');
          /*
           try running an
          	echo $now; die;
          here and you'll see what I mean by the numbers in unix format
          */

          if($now < $timelimit){
            redirect_to('admin_edituser.php');
          }else{
          return "Sorry, you have been locked otu of your account. Please contact your manager.";
          }
        }

      }else if($founduser['user_fail'] < 3){ //
        $number = $founduser['user_fail'] +1;

        $failLogin = "UPDATE tbl_user SET user_fail = {$number} WHERE user_id = {$id}";
        $fail_attempt = mysqli_query($link, $failLogin);
        return "did you forget your password?";
      }else{
        return "You're locked out!";
      }
    } else {
      $message = "Did you forget your username or password?";
      return $message;
    }

    mysqli_close($link);
  }

?>
