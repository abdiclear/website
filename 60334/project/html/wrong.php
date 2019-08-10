<?php

header( "refresh:3; url= https://moham137.myweb.cs.uwindsor.ca/60334/project/html/signin.html");

echo <<<_END
 <head>
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
 <link rel="stylesheet" href="../css/main.css">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
 <script src="../js/main.js"></script>
 </head>
 <body>
 <div class="container-fluid">
  <div class="row" id="topbar">
   <div class="col-sm-9">
     <img src="../images/siteI.jpg" id="logo">
     <h2>LIBRARY</h2>
   </div>
  </div>
 </div>  
 <div class="container-fluid">
  <div class="row">
  <div class="col-sm-4"></div>
  <div class="col-sm-4">
   <form action="verify.php" method="post">
   <p>You have typed in an incorrect combination of username/password. You will be redirected to the sign-in page.</p>
  </div>
  <div class="col-sm-4"></div>
 </div>
</div>
<div class="container-fluid">
 <div class="row">
  <div class="col-sm-5"></div>
  <div class="col-sm-1">
   <a href="https://moham137.myweb.cs.uwindsor.ca" class="footer">Home</a>
  </div>
  <div class="col-sm-1"><a href="https://moham137.myweb.cs.uwindsor.ca" class="footer">Login</a></div>  
  <div class="col-sm-1"><a href="https://moham137.myweb.cs.uwindsor.ca" class="footer">Catalogue</a></div>
  <div class="col-sm-4"></div>
 </div>
 <div class="row">
  <div class="col-sm-12">
   <p class="footer">&copy; Habib Mohamed, 2019</p>
  </div>
 </div>
</div>
</body>

_END;
?>