<?php include "menu.php";
include "gallery_sidebar.php"; ?><html><div class = "row"><div class ="column middle"><link href ="style/gallerytable.css" rel="stylesheet">

GALLERY /<br><br>You may click an image to edit / remove it.<br><br><br><br><br></font size><br>LATEST UPLOADS:<br>

<?php


// DECLARE MY PHP VARIABLES FOR USE
$searchQuery = "select * from artworks ORDER BY artwork_id DESC";
$conn = oci_connect('brianjac', '**REDACTED**', '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(Host=**REDACTED**)(Port=**REDACTED**)))(CONNECT_DATA=(SID=**REDACTED**)))');
$stid = oci_parse($conn, $searchQuery);




oci_execute($stid, OCI_DEFAULT);
echo "<table>";
$counter = 0;
$maxColumns = 6;
// Loop through table and print row as a row, and each item as it's own cell in the table:
	while ($row = oci_fetch_array($stid, OCI_NUM))
		{
		if ($counter == 0){ echo '<tr>'; }
		echo '<td align = center>';
		echo '<form action = "edit_art.php" method = "post"><table border = 1 width = 200 height = 300><tr><th align = "center" height = 50>'.$row[1].'</th></tr>';
		echo '<tr><td align = "center" width = 150 height = 100><div title = "'.$row[1].'"><input type = "image" src = "'.$row[3].'" width = "100" alt = "Submit Form" /></div></td></tr>';		
		echo '<input type = "hidden" name = "artwork_id" value = "'.$row[0].'"><input type = "hidden" name = "url" value = "'.$row[3].'">';
		echo '<tr><font size = 1><td align = "center" width = 150 height = 75>'.$row[2].'</td></font size></tr></table></form>';
		$counter += 1;
		echo '</td>';
		if ($counter >= $maxColumns){ echo '</tr>'; $counter = 0; }
		}
echo "</table>";
// END OF TABLE.





// CONNECTION CLOSE
	oci_close($conn);
?>

</div></div></html>