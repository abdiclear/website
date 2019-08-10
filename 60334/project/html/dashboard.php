<?php
 session_start();
 require_once 'login.php';
 $conn = new mysqli($hn, $un, $pw, $db);
 if ($conn->connect_error) die($conn->connect_error);
//**********************************************************

 if (isset($_POST['theData']))
  {
   $arr = $_POST['theData'];

   foreach($arr as $smallarr){
     $trans = $smallarr[0]; 
     $isbn = $smallarr[1];
     $query  = "DELETE FROM `borrowed` WHERE transactNum = '$trans'";
     
     $result = $conn->query($query);
  	if (!$result) echo "DELETE failed: $query<br>" .
      $conn->error . "<br><br>";
   
   $query2 = "UPDATE `catalogue` SET quantity = quantity + 1, borrowCount = borrowCount + 1 WHERE isbn = '$isbn'";

   $result2 = $conn->query($query2);
  	if (!$result2) echo "UPDATE failed: $query2<br>" .
      $conn->error . "<br><br>";

  }
    
}
//******************************************************

 if(isset($_POST['users'])){

  $arr = $_POST['users'];

   foreach($arr as $uid){
    
   $query2 = "UPDATE `users` SET isSuspended = 0 WHERE uid = '$uid'";

   $result2 = $conn->query($query2);
  	if (!$result2) echo "UPDATE failed: $query2<br>" .
      $conn->error . "<br><br>";

  }

 }
//********************************************************

 if(isset($_POST['fines'])){

   $uid = $_SESSION['uid'];
   $zero = 0.00;

   $query = "UPDATE `users` SET fine = '$zero' WHERE uid = '$uid'";
   $result = $conn->query($query);
   if (!$result) echo "UPDATE failed: $query2<br>" .
      $conn->error . "<br><br>";

}

//*****************************************************************

 if(isset($_POST['fname']) && isset($_POST['lname']) && isset($_POST['uname']) && isset($_POST['pword']) && isset($_POST['type'])){

   $stmt = $conn->prepare("INSERT INTO users (uid, fname, lname, type, isSuspended, username, password, fine) VALUES(?, ?, ?, ?, ?, ?, ?, ?)");
      
      $fname = get_post($conn, 'fname');
      $lname = get_post($conn, 'lname');
      $username = get_post($conn, 'uname');
      $realpword = get_post($conn, 'pword');
      $type = get_post($conn, 'type');
     
     if($type == "Faculty") $type = "F";
     if($type == "Pupil") $type = "P";

     $uid = NULL;     
     $suspend = 0;
     $fine = 0;

     $saltpword = "%gh789a9s" . $realpword . "8a9sj%a";

     $stmt->bind_param("isssissi", $uid, $fname, $lname, $type, $suspend, $username, $saltpword, $fine);
     $stmt->execute();
     if(!$stmt->error)
     {
      echo "User added successfully";
     }
     else
     {
      echo "INSERT failed".$stmt->error;
    }


 }




//****************************************************************
  echo <<<_END
  <head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <link rel="stylesheet" type="text/css" href="../css/table.css">
  <link rel="stylesheet" type="text/css" href="../css/modal.css">
  <link rel="stylesheet" type="text/css" href="../css/main.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script src="../js/dashboard.js"></script>
  <script src="../js/main.js"></script>
_END;

if($_SESSION['role'] == 'S'){ 

//################################################
  $query = "SELECT category, count(*) as cnt FROM `catalogue` GROUP BY category;";
  $result = mysqli_query($conn, $query);

  echo <<<_END
  <script type="text/javascript">
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart);
  function drawChart(){
  var data = google.visualization.arrayToDataTable([
   ['Category','Count'],
_END;
 
  while($row = mysqli_fetch_array($result)){echo "['".$row['category']."', ".$row['cnt']."],";}

 echo <<<_END
 ]);
 var options = {title: 'Number of items in each category'};
 var chart = new google.visualization.PieChart(document.getElementById("piechart"));
 chart.draw(data, options);
 }
  </script>
_END;
//######################################################
  $query = "SELECT type, count(*) as cnt FROM `catalogue` GROUP BY type;";
  $result = mysqli_query($conn, $query);

  echo <<<_END
  <script type="text/javascript">
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart);
  function drawChart(){
  var data = google.visualization.arrayToDataTable([
   ['Type','Count'],
_END;
 
  while($row = mysqli_fetch_array($result)){echo "['".$row['type']."', ".$row['cnt']."],";}

 echo <<<_END
 ]);
 var options = {title: 'Number of items for each type'};
 var chart = new google.visualization.PieChart(document.getElementById("piechart2"));
 chart.draw(data, options);
 }
  </script>
_END;
//#################################################
 $query = "SELECT name, borrowCount FROM `catalogue`;";
  $result = mysqli_query($conn, $query);

  echo <<<_END
  <script type="text/javascript">
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart);
  function drawChart(){
  var data = google.visualization.arrayToDataTable([
   ['Name','Times Borrowed'],
_END;
 
  while($row = mysqli_fetch_array($result)){echo "['".$row['name']."', ".$row['borrowCount']."],";}

 echo <<<_END
 ]);
 var options = {title: 'Items and the number of times they were borrowed', pieHole: 0.4};
 var chart = new google.visualization.PieChart(document.getElementById("piechart3"));
 chart.draw(data, options);
 }
  </script>
_END;
//################################################
$query = "SELECT type, count(*) AS cnt FROM `users` GROUP BY type;";
  $result = mysqli_query($conn, $query);

  echo <<<_END
  <script type="text/javascript">
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart);
  function drawChart(){
  var data = google.visualization.arrayToDataTable([
   ['User Type','Number of'],
_END;
 
  while($row = mysqli_fetch_array($result)){echo "['".$row['type']."', ".$row['cnt']."],";}

 echo <<<_END
 ]);
 var options = {title: 'Categories of users, and the number of each', pieHole: 0.5};
 var chart = new google.visualization.PieChart(document.getElementById("piechart4"));
 chart.draw(data, options);
 }
  </script>
_END;
//################################################
$query = "SELECT uid, count(*) as cnt FROM `users` WHERE fine = 0";
$result1 = mysqli_query($conn, $query);
$row1 = mysqli_fetch_array($result1);

$query = "SELECT uid, count(*) as cnt FROM `users` WHERE fine > 0 AND fine < 10";
$result2 = mysqli_query($conn, $query);
$row2 = mysqli_fetch_array($result2);
$query = "SELECT uid, count(*) as cnt FROM `users` WHERE fine >= 10";
$result3 = mysqli_query($conn, $query);
$row2 = mysqli_fetch_array($result2);

 echo <<<_END
  <script type="text/javascript">
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart);
  function drawChart(){
  var data = google.visualization.arrayToDataTable([
  ['Amount Owed', 'Number of People'],
  ['Owes $0', 
_END;
echo (int)$row1['cnt'];
 echo <<<_END
  ],
  ['Owes between $0.01 and $9.99', 
_END;
echo (int)$row2['cnt']; 
 echo <<<_END
  ],
  ['Owes $10 or more', 
_END;
echo (int)$row3['cnt']; 
echo <<<_END
 ],  
 ]); 

  var options = {title: "Concentration of Fine Amounts", legend: { position: "none" }};
   var chart = new google.visualization.BarChart(document.getElementById("barchart"));
   chart.draw(data, options);
  }
  </script>
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
  <div class="col-sm-3">
   <button type="button" onclick="unSuspend()">Un-Suspend</button>
  </div>
  <div class="col-sm-3">
   <button type="button" id="add" onclick="addUser()">Add User</button>
  </div>
  <div id="addModal" class="modal">
  <div class="modal-content">
   <span class = "close">&times;</span>
   <form action="dashboard.php" method="post"><pre>
    First Name:    <input type="text" required name="fname">
    Last Name:     <input type="text" required name="lname">
    Username:      <input type="text" required name="uname">
    Password:      <input type="text" required name="pword">
    Type:          <select name="type"><option>Faculty</option><option>Pupil</option></select> 
                   <input type="submit" value="Submit">
  </pre></form>
  </div>
 </div>
 <script>
 var modal = document.getElementById("addModal");

// Get the button that opens the modal
var btn = document.getElementById("add");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>
 </div>
 <div class="row">
 <div class="col-sm-12">
 <table id="theTable" class="tables">
 <thead>
 <tr>      
 <th>User ID</th>     
 <th>First Name</th>     
 <th>Last Name</th>     
 <th>Role</th>      
 <th></th>  
 </tr>
 </thead>
 <tbody>
_END;

  $query  = "SELECT uid, fname, lname, type FROM `users` WHERE isSuspended = 1";
  $result = $conn->query($query);
  if (!$result) die ("Database access failed: " . $conn->error);

  $rows = $result->num_rows;
  
  for ($j = 0 ; $j < $rows ; ++$j)
  {
    $result->data_seek($j);
    $row = $result->fetch_array(MYSQLI_NUM);

    echo <<<_END
  <tr>
  <td>$row[0]</td>
  <td>$row[1]</td>
  <td>$row[2]</td>
  <td>$row[3]</td>
  <td><input type="checkbox" name="check" value="n"></td>
  </tr> 
_END;
}
echo <<<_END
  </tbody>
  </table>
  </div>
 </div>
</div>
<div class="container-fluid">
 <div class="row">
  <div class="col-sm-4">
   <div id="piechart" style="width: 500px; height: 300px;"></div>
  </div>
  <div class="col-sm-4">
   <div id="piechart2" style="width: 500px; height: 300px;"></div>
  </div>
  <div class="col-sm-4">
   <div id="barchart" style="width: 500px; height: 300px;"></div>
  </div>
 </div>
 <div class="row">
  <div class="col-sm-4">
   <div id="piechart3" style="width: 500px; height: 300px;"></div>
  </div>
  <div class="col-sm-4">
   <div id="piechart4" style="width:500px;" height: 300px;"></div>
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
_END;
}


//*********************************************************************


if($_SESSION['role'] == 'F' || $_SESSION['role'] == 'P'){
 
 echo <<<_END
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
  <div class="col-sm-4">
   <button type="button" onclick="returnI()">Return</button>
  </div>
 </div>
 <div class="row">
  <div class="col-sm-12">
 <table class="tables" id="theTable">
 <thead>
 <tr>      
 <th>Transact</th>     
 <th>ISBN</th>     
 <th>Date Borrowed</th>     
 <th>Due Date</th>      
 <th></th>  
 </tr>
 </thead>
 <tbody>
_END;

  $uid = $_SESSION['uid'];
  $query  = "SELECT transactNum, isbn, dateBorrow, dueDate FROM borrowed WHERE uid = '$uid'";
  $result = $conn->query($query);
  if (!$result) die ("Database access failed: " . $conn->error);

  $rows = $result->num_rows;
  
  for ($j = 0 ; $j < $rows ; ++$j)
  {
    $result->data_seek($j);
    $row = $result->fetch_array(MYSQLI_NUM);

    echo <<<_END
  <tr>
  <td>$row[0]</td>
  <td>$row[1]</td>
  <td>$row[2]</td>
  <td>$row[3]</td>
  <td><input type="checkbox" name="check" value="n"></td>
  </tr> 
_END;
  }
  
  echo <<<_END
  </tbody>
  </table>
  </div>
 </div>
 <div class="row">
  <div class="col-sm-4">
   <form action = "dashboard.php" method = "post">
   <input type="hidden" name="fines" value="yes">
   <input class="fbutton" type="submit" value="Pay Fines"></form>
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
_END;
}

function get_post($conn, $var)
  {
    return $conn->real_escape_string($_POST[$var]);
  }

?>