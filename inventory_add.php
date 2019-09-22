<?php include "menu.php"; include "inventory_sidebar.php"?><html><div class = "row"><div class ="column middle">
INVENTORY / Add<br><br>
<?php
$conn = oci_connect('brianjac', '**REDACTED**', '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(Host=**REDACTED**)(Port=**REDACTED**)))(CONNECT_DATA=(SID=**REDACTED**)))');
$dimensionQuery = 'select * from sizes order by size_id';
$artQuery = 'select * from artworks order by name';
$size_id = $_POST['size_id'];
$artwork_id = $_POST['artwork_id'];
$quantity = $_POST['quantity'];
$submitQuery = "insert into inventory (size_id, artwork_id, quantity) values ('".$size_id."', ".$artwork_id.", ".$quantity.")"; 
function isValid($str) { return !preg_match("/[^A-Za-z0-9 '?!.]/", $str); }
$stid = oci_parse($conn, $artQuery);

oci_execute($stid, OCI_DEFAULT);
echo '<table border = 1><tr><td>Name</td><td>Dimensions</td><td>QTY</td></tr><tr height = 5 style = "vertical-align: baseline;"><td ><form action = "'.htmlspecialchars($_SERVER["PHP_SELF"]).'" method ="post">';
echo "<select name = 'artwork_id'><option value = ''></option>";
while ($row = oci_fetch_array($stid, OCI_NUM))
	{
	foreach ($row as $item)
		{
		   if ($item == $row[0]) { echo '<option value = "'.$row[0].'"><img src = "test.jpg">'.$row[1].'</option>'; }			
		}
		
	}
echo "</select></td><td>";

$stid = oci_parse($conn, $dimensionQuery);
oci_execute($stid, OCI_DEFAULT);
echo "<select name = 'size_id'><option value = ''></option>";
while ($row = oci_fetch_array($stid, OCI_NUM))
	{
	 echo '<option value = "'.$row[0].'">'.$row[1].'</option>'; 					
	}
echo "</select></td><td>";

echo "<input type = text name = 'quantity' size = 4 /></td><td>";
echo "<input type = submit value = 'Submit' />";
echo "</td></form></tr></table>";
echo '<br><br>';



if ((!empty($artwork_id)) && (!empty($size_id)) && (isValid($quantity))){
	$stid = oci_parse($conn, $submitQuery);
	if (oci_execute($stid, OCI_DEFAULT)) { echo '<br><br>'; oci_commit($conn); } else {
	echo "Unable to create print!<br><br>";} 
} else {
        echo 'Must select both an artwork and size.<br><br>';
}

$count = 0;
$maxCount = 5;

echo 'The '.$maxCount.' latest entries:<br><br>';


$searchQuery = "select dimensions, name, url, description, quantity from inventory inner join artworks on inventory.artwork_id = artworks.artwork_id inner join sizes on inventory.size_id = sizes.size_id order by inventory.place desc";
$stid = oci_parse($conn, $searchQuery);
oci_execute($stid, OCI_DEFAULT);

echo "<table border = '1'><tr><td>Size</td><td>Name</td><td>QTY</td></tr>";
	while ($row = oci_fetch_array($stid, OCI_NUM))
		{
		$count += 1;
		if ($count <= $maxCount) { 
				echo '<tr>';  
				echo '<td>'.$row[0].'</td>'; 
				echo '<td><a class= "tooltip" href="">'.$row[1].'<span><img src="'.$row[2].'"><h3>'.$row[1].'</h3>'.$row[3].'</span></a></td>';
				echo '<td>'.$row[4].'</td>';
				}
			 echo '</tr>'; 
			
		}
echo "</table>";
oci_close($conn);



oci_close($conn);






?>








</div></div></html>