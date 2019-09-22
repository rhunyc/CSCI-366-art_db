<?php include "menu.php"; include "inventory_sidebar.php"; ?><html><div class = "row"><div class ="column middle">

INVENTORY /<br><br>

<?php
$count = 0;
$maxCount = 10;
$conn = oci_connect('brianjac', '**REDACTED**', '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(Host=**REDACTED**)(Port=**REDACTED**)))(CONNECT_DATA=(SID=**REDACTED**)))');

echo $maxCount.' most recent prints:<br><br>';

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
if ($count < $maxCount) { echo ' <br>Only '.$count.' prints in inventory to display.';} 
?>


</div></div></html>