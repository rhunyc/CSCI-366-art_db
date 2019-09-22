<?php include "menu.php";
include "gallery_sidebar.php"; ?><html><div class = "row"><div class ="column middle">
GALLERY / search <br><br>

<?php
$orderBy = $_POST['orderBy'];
if (empty($orderBy)) { $orderBy = 'order by artwork_id desc';}
$specify = $_POST['specify'];
$searchTerm = $_POST['searchTerm'];
$actualSearchTerm = $searchTerm;
$actualSearchTerm = str_replace("'", "''", $actualSearchTerm);
$searchQuery = "select * from artworks ".$orderBy;
$conn = oci_connect('brianjac', '**REDACTED***', '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(Host=**REDACTED**)(Port=1521)))(CONNECT_DATA=(SID=**REDACTED**)))');

function isValid($str) { return !preg_match("/[^A-Za-z0-9 '?!.]/", $str); }

echo '<form method="post" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'">';
echo '<table border = 1 ><tr><td width = 300>Search term</td><td>Sort</td><td>Specify</td></tr>';
echo '<td><input type = "text" name = "searchTerm" value = "'.$searchTerm.'"/></td>';
echo '<td><select name = "orderBy">';
echo '<option value = ""></option>';
echo '<option value = "order by name asc" '; if(isset($_POST['orderBy']) && $_POST['orderBy'] == 'order by name asc') { echo 'selected = "selected"'; } echo '>+Name</option>';
echo '<option value = "order by name desc" '; if(isset($_POST['orderBy']) && $_POST['orderBy'] == 'order by name desc') { echo 'selected = "selected"'; } echo ' >-Name</option>';
echo '<option value = "order by artwork_id asc" '; if(isset($_POST['orderBy']) && $_POST['orderBy'] == 'order by artwork_id asc') { echo 'selected = "selected"'; } echo '>Oldest</option>';
echo '<option value = "order by artwork_id desc" '; if(isset($_POST['orderBy']) && $_POST['orderBy'] == 'order by artwork_id desc') { echo 'selected = "selected"'; } echo ' >Newest</option>';
echo '</select>';
echo '<td><select name = "specify">';
echo '<option value = ""></option>';
echo '<option value = "name" '; if(isset($_POST['specify']) && $_POST['specify'] == 'name') { echo 'selected = "selected"'; } echo ' >Name only</option>';
echo '<option value = "description"  ';   if(isset($_POST['specify']) && $_POST['specify'] == 'description') { echo 'selected = "selected"'; } echo ' >Desc only</option>';
echo '</select>';
echo '<td><input type = "submit" value = "Search" /></td>';


if ( (!empty($actualSearchTerm)) && (empty($specify)) ){
$searchQuery = "select * from artworks where UPPER(name) LIKE UPPER ('%".$actualSearchTerm."%') or UPPER(description) LIKE UPPER ('%".$actualSearchTerm."%') ".$orderBy;
} else if ( (!empty($actualSearchTerm)) && (!empty($specify)) ) {
	if ($specify == 'name'){
		$searchQuery = "select * from artworks where UPPER(name) LIKE UPPER ('%".$actualSearchTerm."%') ".$orderBy;
	} else {
		$searchQuery = "select * from artworks where UPPER(description) LIKE UPPER ('%".$actualSearchTerm."%') ".$orderBy;
	}
} 

echo'</tr>';
echo '</table></form><br><br>Results:';


$stid = oci_parse($conn, $searchQuery);
oci_execute($stid, OCI_DEFAULT);
echo '<table id="gallery">';
$counter = 0;
$maxColumns = 6;
while ($row = oci_fetch_array($stid, OCI_NUM))
		{
		if ($counter == 0){ echo '<tr>'; }
		echo '<td align = left>';
		echo '<form action = "edit_art.php" method = "post"><table border = 1 height = 300 id = "innergallery"><tr><th align = "center" height = 50>'.$row[1].'</th></tr>';
		echo '<tr><td align = "center" width = 100 height = 100><div title = "'.$row[1].'"><input type = "image" src = "'.$row[3].'" width = "100" alt = "Submit Form" /></div></td></tr>';		
		echo '<input type = "hidden" name = "artwork_id" value = "'.$row[0].'"><input type = "hidden" name = "url" value = "'.$row[3].'">';
		echo '<tr><font size = 1><td align = "center" width = 150 height = 75>'.$row[2].'</td></font size></tr></table></form>';
		$counter += 1;
		echo '</td>';
		if ($counter >= $maxColumns){ echo '</tr>'; $counter = 0; }
		}
echo "</table>";



oci_close($conn);
?>

</div></div></html>
