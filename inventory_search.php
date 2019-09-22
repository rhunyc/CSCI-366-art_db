<?php include "menu.php"; include "inventory_sidebar.php"?><html><div class = "row"><div class ="column middle"><head></head>
INVENTORY / Search<br><br>

<?php
$conn = oci_connect('brianjac', '**REDACTED**', '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(Host=**REDACTED**)(Port=**REDACTED**)))(CONNECT_DATA=(SID=**REDACTED**)))');
$previousColumn = "";
$defaultQuery = "select dimensions, name, url, description, quantity from inventory inner join artworks on inventory.artwork_id = artworks.artwork_id inner join sizes on inventory.size_id = sizes.size_id order by inventory.size_id";
$dimensionQuery = 'select * from sizes order by size_id';
$orderBy = $_POST['orderBy'];
$size_id = $_POST['size_id'];
$searchTerm = $_POST['searchTerm'];

echo '<form method="post" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'">';
echo '<table border = 1><tr><td>Search term</td><td>Order</td></tr>';
echo '<td><input type = "text" name = "searchTerm" value = "'.$searchTerm.'"/></td>';
echo '<td><select name = "orderBy">';
echo '<option value = ""></option>';
echo '<option value = "order by name asc" '; if(isset($_POST['orderBy']) && $_POST['orderBy'] == 'order by name asc') { echo 'selected = "selected"'; } echo '>+Name</option>';
echo '<option value = "order by name desc" '; if(isset($_POST['orderBy']) && $_POST['orderBy'] == 'order by name desc') { echo 'selected = "selected"'; } echo ' >-Name</option>';
echo '<option value = "order by quantity asc"  ';   if(isset($_POST['orderBy']) && $_POST['orderBy'] == 'order by quantity asc') { echo 'selected = "selected"'; } echo ' >+QTY</option>';
echo '<option value = "order by quantity desc" ';   if(isset($_POST['orderBy']) && $_POST['orderBy'] == 'order by quantity desc') { echo 'selected = "selected"'; } echo ' >-QTY</option>';
echo '<option value = "order by dimensions asc" ';  if(isset($_POST['orderBy']) && $_POST['orderBy'] == 'order by dimensions asc') { echo 'selected = "selected"'; } echo ' >+Size</option>';
echo '<option value = "order by dimensions desc" '; if(isset($_POST['orderBy']) && $_POST['orderBy'] == 'order by dimensions desc') { echo 'selected = "selected"'; } echo ' >-Size</option></select></td></tr>';

echo '<tr><td>Size</td></tr>';
echo "<tr><td><select name = 'size_id'>";
echo "<option value = ''></option>";


$stid = oci_parse($conn, $dimensionQuery);
oci_execute($stid, OCI_DEFAULT);
while ($row = oci_fetch_array($stid, OCI_NUM))
	{
        echo '<option value = "'.$row[0].'" '; if(isset($_POST['size_id']) && $_POST['size_id'] == $row[0]) { echo 'selected = "selected"'; } echo ' >'.$row[1].'</option>'; 				
	}
echo '</select></td><td><input type = "submit" value = "Search"></td></tr>';
echo '</table></form><br><br>';

if (!empty($size_id) && empty($searchTerm)){
$searchQuery = "select dimensions, name, url, description, quantity from inventory inner join artworks on inventory.artwork_id = artworks.artwork_id inner join sizes on inventory.size_id = sizes.size_id where inventory.size_id = '".$size_id."' ".$orderBy;
} else if (!empty($size_id) && (!empty($searchTerm))) {
$searchQuery = "select dimensions, name, url, description, quantity from inventory inner join artworks on inventory.artwork_id = artworks.artwork_id inner join sizes on inventory.size_id = sizes.size_id where inventory.size_id = '".$size_id."' AND UPPER(artworks.name) LIKE UPPER ('%".$searchTerm."%') ".$orderBy;
} else if (empty($size_id) && (!empty($searchTerm))){
$searchQuery = "select dimensions, name, url, description, quantity from inventory inner join artworks on inventory.artwork_id = artworks.artwork_id inner join sizes on inventory.size_id = sizes.size_id where UPPER(artworks.name) LIKE UPPER ('%".$searchTerm."%') ".$orderBy;
} else {
$searchQuery = "select dimensions, name, url, description, quantity from inventory inner join artworks on inventory.artwork_id = artworks.artwork_id inner join sizes on inventory.size_id = sizes.size_id ".$orderBy;
}

$stid = oci_parse($conn, $searchQuery);
oci_execute($stid, OCI_DEFAULT);
$numRows = 0;
while ($row = oci_fetch_array($stid, OCI_NUM)){ $numRows += 1; } 
echo "RESULTS ".$numRows.":<br><br>";
oci_execute($stid, OCI_DEFAULT);
if ($numRows <= 0){
echo 'No results!';
} else {
echo "<table border = '1'><tr><td>Size</td><td>Name</td><td>QTY</td></tr>";
	while ($row = oci_fetch_array($stid, OCI_NUM))
		{
		echo '<tr>';
  			if ($row[0] ==  $previousColumn) {echo '<td></td>';  } else { echo '<td>'.$row[0].'</td>'; } 
			echo '<td><a class = "tooltip" href="">'.$row[1].'<span><img src ="'.$row[2].'"><h3>'.$row[1].'</h3>'.$row[3].'</span></a></td><td>'.$row[4].'</td>'; 
		echo '</tr>';
		$previousColumn = $row[0];
		}

echo "</table>";
}
oci_close($conn);
?>
<br><br><br><br><br><br><br><br><br><br><br><br>

</div></div></html>