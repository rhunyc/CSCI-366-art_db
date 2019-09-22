<?php include "menu.php"; include "gallery_sidebar.php"?>
<html><body><div class = "row"><div class ="column middle">
<?php 
// VARIABLE DECLARATION
$artwork_id = $_POST["artwork_id"];
$name = $_POST["name"];
$imageurl = $_POST["url"];
$description = $_POST["description"];
$nameToUpdate = $_POST["nameToUpdate"];
$descriptionToUpdate = $_POST["descriptionToUpdate"];
$showQuery = "select * from artworks where artwork_id = ".$artwork_id;
$updateQuery = "update artworks set name ='".str_replace("'", "''", $nameToUpdate)."', description ='".str_replace("'", "''", $descriptionToUpdate)."' where artwork_id = ".$artwork_id;
$delete = $_POST["delete"];
$deleteQuery = "delete from artworks where artwork_id = ".$artwork_id;

function isValid($str) {
    return !preg_match("/[^A-Za-z0-9 '?!,.]/", $str);
}



// CONNECTION
$conn = oci_connect('brianjac', '**REDACTED**', '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(Host=**REDACTED**)(Port=**REDACTED**)))(CONNECT_DATA=(SID=**REDACTED**)))');

// SHOWING EXECUTION
$stid = oci_parse($conn, $showQuery);
oci_execute($stid, OCI_DEFAULT);
$row = oci_fetch_array($stid, OCI_NUM);


// UPDATE EXECUTION
if(empty($delete)){
	if((!empty($nameToUpdate)) && (!empty($descriptionToUpdate))){
		if ((isValid($nameToUpdate)) && (isValid($descriptionToUpdate))){
			$stid = oci_parse($conn, $updateQuery);
			oci_execute($stid, OCI_DEFAULT);
			oci_commit($conn);
			header("Location: gallery.php");
		} else {
			echo "Unable to update, please enter a valid descritption / name consisting of A-z, spaces, single quotes, and/or numbers.";
		}
	}
} else {
	unlink($imageurl);
	$stid = oci_parse($conn, $deleteQuery);
	oci_execute($stid, OCI_DEFAULT);
	oci_commit($conn);
        
	header("Location: gallery.php");
}


// DEPENDENT VARIABLES
$tableName = (empty($nameToUpdate) ? $row[1] : $nameToUpdate);
$tableDescription = (empty($descriptionToUpdate) ? $row[2] : $descriptionToUpdate);

// SHOWING TABLE
	echo '<form action = "'.htmlspecialchars($_SERVER["PHP_SELF"]).'" method ="post">';
	echo "<table border = '1'>";	
	echo '<tr><td><input type = "text" align = "center" name = "nameToUpdate" value ="'.$tableName.'" /></td></tr>';
	echo '<tr><td><img align = "center" src = "'.$row[3].'"><input type = "hidden" name = "url" value = "'.$row[3].'"></td></tr>';
	echo '<tr><td><textarea name = "descriptionToUpdate" rows = 3>'.$tableDescription.'</textarea></td></tr>';
	echo '<tr><td><p style"text-align:left;">Delete?<span style="float:right;"><input type = "checkbox" name = "delete" value ="checked" /></p></td></tr>';
	echo '<tr><td><p style"text-align:left;"><a href = "gallery.php">Cancel</a><span style="float:right;"><input type="submit" /><input type = "hidden" name = "artwork_id" value = "'.$artwork_id.'" /></p>';
        echo "</td></tr>";
	echo "</table></form>";
// END OF TABLE.



// Closes our connection.
	oci_close($conn);
?>
</div></div></body></html>
