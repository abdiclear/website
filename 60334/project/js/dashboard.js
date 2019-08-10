arr = new Array();
function returnI(){
var table = document.getElementById("theTable");
 var rows = table.rows.length;
 var i;
 for(i = 1; i < rows; i++){
  var checkCell = table.rows[i].cells[4].children[0];
  var transactCell = table.rows[i].cells[0];
  var isbnCell = table.rows[i].cells[1];
  var smallarr = new Array(2);
  if(checkCell.checked == true){
    smallarr[0] = transactCell.innerHTML;
    smallarr[1] = isbnCell.innerHTML;
    arr.push(smallarr);
  } 
 }
$.post('dashboard.php',
       {theData: arr},
       function(data, status, jqXHR){
         alert("Books have been returned.");
       });

}

function unSuspend(){
var table = document.getElementById("theTable");
 var rows = table.rows.length;
 var i;
 for(i = 1; i < rows; i++){
  var checkCell = table.rows[i].cells[4].children[0];
  var userCell = table.rows[i].cells[0];
  alert(userCell.innerHTML + "is being un-suspended.");
  if(checkCell.checked == true){
    arr.push(userCell.innerHTML);
  } 
 }
$.post('dashboard.php',
       {users: arr},
       function(data, status, jqXHR){
         alert("Finished un-suspending.");
       });

}
