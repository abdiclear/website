<?php
  session_start();
  require_once 'login.php';
  $conn = new mysqli($hn, $un, $pw, $db);
  if ($conn->connect_error) die($conn->connect_error);

  if (isset($_POST['theData']))
  {
   $arr = $_POST['theData'];

   foreach($arr as $smallarr){
     $num = rand(100000,1000000);
     $user = $_SESSION['uid']; 
     $isbn = $smallarr[0];
     $query = "";
     if($_SESSION['role'] == 'F'){
      $query  = "INSERT INTO `borrowed`(`transactNum`, `uid`, `isbn`, `dateBorrow`, `dueDate`) VALUES ('$num','$user','$isbn',now(),now() + INTERVAL 2 WEEK)";
     }
      if($_SESSION['role'] == 'P'){
      $query = "INSERT INTO `borrowed`(`transactNum`, `uid`, `isbn`, `dateBorrow`, `dueDate`) VALUES ('$num','$user','$isbn',now(),now() + INTERVAL 1 MONTH)";
     }
     $result = $conn->query($query);
  	if (!$result) echo "INSERT failed: $query<br>" .
      $conn->error . "<br><br>";
   

   $qty = $smallarr[1] - 1;
   $query2 = "UPDATE `catalogue` SET quantity = '$qty' WHERE isbn = '$isbn'";

   $result2 = $conn->query($query2);
  	if (!$result2) echo "UPDATE failed: $query2<br>" .
      $conn->error . "<br><br>";

   header('Location: https://moham137.myweb.cs.uwindsor.ca/60334/project/html/catalogue.php');
  }
    
}

  if(isset(isset($_POST['name']) && isset($_POST['author']) && isset($_POST['category']) && isset($_POST['type']) && isset($_POST['qty'])){
      $stmt = $conn->prepare("INSERT INTO catalogue (isbn, name, author, category, type, quantity, borrowCount, holds) VALUES(?, ?, ?, ?, ?, ?, ?, ?)");
      

      $name = get_post($conn, 'name');
      $author = get_post($conn, 'author');
      $category = get_post($conn, 'category');
      $type = get_post($conn, 'type');
      $qty = get_post($conn, 'qty');
          
     $isbn = NULL;
     $count = 0;
     $holds = 0;

     $stmt->bind_param("issssiii", $isbn, $name, $author, $category, $type, $qty, $count, $holds);
     $stmt->execute();
     if(!$stmt->error)
     {
      echo "Inserted record successfully<br><br>";
     }
     else
     {
      echo "INSERT failed".$stmt->error;
    }
 
  }

echo <<<_END
  <head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="../css/table.css">
  <link rel="stylesheet" type="text/css" href="../css/modal.css">
  <link rel="stylesheet" type="text/css" href="../css/main.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="../js/catalogue.js"></script>
  </head>
 <body>
_END;

echo <<<_END
 <div class="container-fluid">
  <div class="row" id="topbar">
   <div class="col-sm-9">
    <img src="../images/siteI.jpg" id="logo">
    <h2>LIBRARY</h2>
   </div>
   <div class="col-sm-3">
_END;

if(isset($_SESSION["username"]) && isset($_SESSION["password"])){
   echo '<a href="https://moham137.myweb.cs.uwindsor.ca/60334/project/html/dashboard.php" class="linkbutton">Dashboard</a>';
   echo '<form action="https://moham137.myweb.cs.uwindsor.ca/index.php" method="post"> <input type="hidden" name="logout"> <input type="submit" value="Log Out"></form>';
  }else{
   echo '<a href="https://moham137.myweb.cs.uwindsor.ca/60334/project/html/signin.html" class="linkbutton">LOGIN</a>';
  }

echo <<<_END
</div>
 </div> 
</div>
_END;

if($_SESSION['role'] == 'S'){ 
 echo <<<_END
 <div id="test">
 <button type="button" onclick="count_check()">Delete</button>
 <button id="add">Add</button>

 <div id="addModal" class="modal">
  <div class="modal-content">
   <span class = "close">&times;</span>
   <form action="catalogue.php" method="post"><pre>
    Name:     <input type="text" required name="name">
    Author:   <input type="text" required name="author">
    Category: <select name="category"><option>Fiction</option><option>Non-Fiction</option><option>Play</option></select>
    Type:     <select name="type"><option>B</option><option>V</option></select>
    Quantity: <input type="text" required name="qty">
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
_END;
}

if($_SESSION['role'] == 'F' || $_SESSION['role'] == 'P'){
 echo '<button type="button" onclick="borrow()">Borrow</button>';
}

 echo <<<_END
 <div class="container-fluid">
  <div class="row">
   <div class="col-sm-6">
    <input type="text" id="search" class="search" onkeyup="search()" placeholder="Search catalogue">
   </div>
   <div class="col-sm-6">
    <select id="searchby">
     <option>ISBN</option>
     <option>Title</option>
     <option>Author</option>
     <option>Category</option>
    </select>
   </div>
  </div>
  <div class="row">
   <div class="col-sm-12">
    <table id="theTable" class="tables">
    <thead>
    <tr>      
     <th>ISBN</th>     
     <th>Title</th>     
     <th>Author</th>     
     <th>Category</th> 
     <th>Quantity</th>     
     <th></th>  
   </tr>
   </thead>
   <tbody>
_END;

  $query  = "SELECT isbn, name, author, category, quantity FROM catalogue";
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
  <td>$row[4]</td>
_END;
  if($row[4] > 0){
  echo <<<_END
  <td><input type="checkbox" name="check" value="n"></td>
_END;
}else{
 echo <<<_END
 <td></td>
_END;
}
 echo <<<_END
  </tr> 
_END;
  }
  
  echo <<<_END
  </tbody>
  </table>
  </div>
  </div>
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

  $result->close();
  $conn->close();
  
  function get_post($conn, $var)
  {
    return $conn->real_escape_string($_POST[$var]);
  }
  
?>