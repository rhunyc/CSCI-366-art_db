<?php include "menu.php"; include "inventory_sidebar.php"; ?><html><div class = "row"><div class ="column middle"><head></head>
INVENTORY / Update<br><br>



<?php
$size_id = $_POST['size_id'];
$artwork_id = $_POST['artwork_id'];
$quantity = $_POST['quantity'];
$previousColumn = "";

$defaultQuery = "select inventory.size_id, dimensions, inventory.artwork_id, name, quantity, url, description from inventory inner join artworks on inventory.artwork_id = artworks.artwork_id inner join sizes on inventory.size_id = sizes.size_id order by inventory.size_id, name";
$updateQuery = "update inventory set quantity = ".$quantity." where artwork_id = ".$artwork_id." AND size_id = '".$size_id."'";



function isValid($str) { return !preg_match("/[^A-Za-z0-9 '?!.]/", $str); }

$conn = oci_connect('brianjac', '**REDACTED**', '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(Host=**REDACTED**)(Port=**REDACTED**)))(CONNECT_DATA=(SID=**REDACTED**)))');
$stid = oci_parse($conn, $defaultQuery);
oci_execute($stid, OCI_DEFAULT);

echo "<table border = '1'><tr><td>Size</td><td>Name</td><td>QTY</td></tr>";
	while ($row = oci_fetch_array($stid, OCI_NUM))
		{
		echo '<tr><form action = "'.htmlspecialchars($_SERVER["PHP_SELF"]).'" method ="post">'; 
		echo '<input type = "hidden" value = "Updated QTY successful: print '.$row[3].' size '.$row[1].'" name = "success" />';
		echo '<input type = "hidden" value = "'.$row[0].'" name = "size_id" />'; 
		if ($row[1] ==  $previousColumn) {echo '<td></td>';  } else { echo '<td>'.$row[1].'</td>'; }
		echo '<input type = "hidden" value = "'.$row[2].'" name = "artwork_id" />'; 
		echo '<td><a class= "tooltip" href="">'.$row[3].'<span><img src="'.$row[5].'"><h3>'.$row[3].'</h3>'.$row[6].'</span></a></td>'; 
		echo '<td><input type = "text" value = "'.$row[4].'" name = "quantity" size = 4 /></td><td><input type = "submit" value = "Update" /></td>'; 
		echo '</form></tr>';
		$previousColumn = $row[1];
		}
echo "</table>";
echo '<br><br>';

if (!empty($artwork_id)){
if (isValid($quantity)) {
	$stid = oci_parse($conn, $updateQuery);
	if (oci_execute($stid, OCI_DEFAULT))
	{
		oci_commit($conn);
                echo "<script> location.href='inventory_transaction.php';</script>"; 
        } 
	else 
	{ 
		echo 'unable to commit changes';
        }
}
}
       	


oci_close($conn);
