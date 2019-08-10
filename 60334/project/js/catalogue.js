var arr = new Array();

function search() {
  var input, filter, table, tr, td, i, txtValue, searchby;
  input = document.getElementById("search");
  filter = input.value.toUpperCase();
  table = document.getElementById("theTable");
  tr = table.getElementsByTagName("tr");

  searchby = document.getElementById("searchby").selectedIndex;

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[searchby];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      } 
   }
  }
}

function borrow() {

 var table = document.getElementById("theTable");
 var rows = table.rows.length;
 var i;
 for(i = 1; i < rows; i++){
  var checkCell = table.rows[i].cells[5].children[0];
  var isbnCell = table.rows[i].cells[0];
  var qtyCell = table.rows[i].cells[4];
  var smallarr = new Array(2);
  if(checkCell.checked == true){
    smallarr[0] = isbnCell.innerHTML;
    smallarr[1] = qtyCell.innerHTML;
    arr.push(smallarr);
  } 
 }
$.post('catalogue.php',
       {theData: arr},
       function(data, status, jqXHR){
         alert("Borrowed successfully! Check the dashboard to see your current borrowed items.");
       });

}