<?php
if(isset($_POST['logout'])){
 session_unset();
 session_destroy();
}else{
  session_start();
  $inactive = 600;
  if (isset($_SESSION["timeout"])) {
   $sessionTTL = time() - $_SESSION["timeout"];
   if($sessionTTL > $inactive){
    session_destroy();
    header("Location: /index.php");
   }
  }
  $_SESSION["timeout"] = time();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>HM Library</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="../60334/project/css/main.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <script src="../60334/project/js/main.js"></script>
</head>
<body>
<div class="container-fluid">
 <div class="row" id="topbar">
  <div class="col-sm-9">
    <img src="../60334/project/images/siteI.jpg" id="logo">
    <h2>LIBRARY</h2>
  </div>
  <div class="col-sm-3">
   <?php
  if(isset($_SESSION["username"]) && isset($_SESSION["password"])){
   echo '<a href="https://moham137.myweb.cs.uwindsor.ca/60334/project/html/dashboard.php" class="linkbutton">Dashboard</a>';
   echo '<form action="index.php" method="post"> <input type="hidden" name="logout"> <input type="submit" class="fbutton" value="Log Out"></form>';
  }else{
  echo '<a href="https://moham137.myweb.cs.uwindsor.ca/60334/project/html/signin.html" class="linkbutton">LOGIN</a>';
  }
  ?>
 
   </div>
 </div> 
</div>

<div class="container-fluid">
 <div class="row">
  <div class="col-sm-12" id="imagediv">
   <img src="../60334/project/images/library.jpg" id="image">
  </div>
 </div>
</div>

<div class="container-fluid">
 <div class="row">
  <div class="col-sm-6" id="leftbar">
   <h3>About Us</h3>
   <p>The HM Library is where the faculty of HM University go to get their research materials. Founded in 2019,
 it showcases a variety of books, videos, and audio materials.</p>
  </div>
  <div class="col-sm-6" id="rightbar">
   <h3>Catalogue</h3>
   <p>If you would like to browse our catalogue, please click down below</p>
   <a href="https://moham137.myweb.cs.uwindsor.ca/60334/project/html/catalogue.php" class="linkbutton">Catalogue</a>
  </div>
 </div>
</div>

<div class="container-fluid">
 <div class="row">
  <div class="col-sm-5"></div>
  <div class="col-sm-1">
   <a href="https://moham137.myweb.cs.uwindsor.ca" class="footer">Home</a>
  </div>
  <div class="col-sm-1"><a href="https://moham137.myweb.cs.uwindsor.ca/60334/html/signin.html" class="footer">Login</a></div>  
  <div class="col-sm-1"><a href="https://moham137.myweb.cs.uwindsor.ca/60334/html/catalogue.php" class="footer">Catalogue</a></div>
  <div class="col-sm-4"></div>
 </div>
 <div class="row">
  <div class="col-sm-12">
   <p class="footer">&copy; Habib Mohamed, 2019</p>
  </div>
 </div>
</div>
</body>
</html>