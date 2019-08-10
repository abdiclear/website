<?php
 session_start();
 require_once 'login.php';
 $conn = new mysqli($hn, $un, $pw, $db);
 if ($conn->connect_error) die($conn->connect_error);

 if(isset($_POST['username']) && isset($_POST['password'])){
     $username = get_post($conn, 'username');
     $password = get_post($conn, 'password'); 

     $saltpwd = "%gh789a9s" . $password . "8a9sj%a";

     $query    = "SELECT username, password, type, uid, fine, isSuspended FROM users WHERE username = '$username' AND password = '$saltpwd'";
    $result   = $conn->query($query);
    $row = $result->fetch_array(MYSQLI_NUM);

    if ($result->num_rows == 0){ 
     header("Location: https://moham137.myweb.cs.uwindsor.ca/60334/project/html/wrong.php");
     exit();
       
    }
 
    if($row[5] == 1){
      header("Location: https://moham137.myweb.cs.uwindsor.ca/60334/project/html/suspend.html");
      exit();

    }else{

     $_SESSION["username"] = $username;
     $_SESSION["password"] = $password;
     $_SESSION["role"] = $row[2];
     $_SESSION["uid"] = $row[3];

     $curr_fine = $row[4];
     $total_fine = 0;

     $query = "SELECT dueDate FROM `borrowed` WHERE uid = '$row[3]'";
     $result = $conn->query($query);
     if(!result) die("Database access failed: " . $conn->error);
     $rows = $result->num_rows;

     for ($j = 0 ; $j < $rows ; ++$j)
     {
     $result->data_seek($j);
     $row = $result->fetch_array(MYSQLI_NUM);

    $datet1 = date("Y-m-d H:i:s"); 
    $datet2 = $row[0]; 

    $datetime1 = strtotime($datet1);
    $format = date('Y-m-d',$datetime1);
    $datetime2 = strtotime($datet2);
    $format2 = date('Y-m-d', $datetime2);

    $diff = date_diff(date_create($format), date_create($format2));

    $num_diff = (int)$diff->format("%R%a");

    if($num_diff < -18){
      $temp_uid = $_SESSION['uid'];
      $query    = "UPDATE `users` SET isSuspended = 1 WHERE uid = '$temp_uid'";
      header("Location: https://moham137.myweb.cs.uwindsor.ca/60334/project/html/suspend.html");
      exit();

    } 

    if($num_diff < 0){
      $total_fine += (-0.55 * $num_diff + 1); 
    }


 
 } 
    if($total_fine > $curr_fine){
   
     $temp_uid = $_SESSION['uid'];
     $query    = "UPDATE `users` SET fine = '$total_fine' WHERE uid = '$temp_uid'";
     $result   = $conn->query($query);  
     if(!result) die("Database access failed: " . $conn->error); 

   }
   
 
 }
    
   
 
 }



function get_post($conn, $var)
  {
    return $conn->real_escape_string($_POST[$var]);
  }

header("Location: https://moham137.myweb.cs.uwindsor.ca");
 
/* Make sure that code below does not get executed when we redirect. */
exit();

?>