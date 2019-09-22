<?php 
include "menu.php";
include "gallery_sidebar.php";

?>
<html><div class = "row"><div class ="column middle">
GALLERY / Add<br><br>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" 
    enctype="multipart/form-data"> 
<table border = 1>
    <tr><td>Name:</td><td><input type = "text" id="name" name="name" size = 45 value = "<?php echo $name; ?>"></td></tr> 
    <tr><td width = 150 >Artwork to upload: </td>
    <td><input type="file" id="upload" name="file_upload"/></label></div></td></tr>
    <tr><td>Description:</td><td><textarea rows = 3 cols = 50 id="description" name="description"><?php echo $description; ?></textarea></td></tr>
    <tr><td><a href = "gallery.php">Cancel</a></td>
    <td align = right><input type="submit" value="Submit"/></td></tr></table>
</form>



<?php
// SETUP --------------------------------------------------------------------------------------

// DECLARE MY PHP VARIABLES FOR USE
$description = $_POST["description"];
$filename = $_FILES['file_upload']['name'];
$name = $_POST["name"];
$name = str_replace("'", "''", $name);
$description = str_replace("'", "''", $description);
$url = 'uploads/'.$filename;
$insertQuery = "insert into artworks (name, description, url) values ('".$name."','".$description."','".$url."')";
$conn = oci_connect('brianjac', '**REDACTED**', '(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(Host=**REDACTED**)(Port=**REDACTED**)))(CONNECT_DATA=(SID=**REDACTED**)))');



// HELPER FUNCS
function isValid($str) {
    return !preg_match("/[^A-Za-z0-9 ',?!.]/", $str);
}


// DATABASE INSERT ---------------------------------------------------------------------------
//ERROR CHECKS
if(getimagesize($_FILES['file_upload']['tmp_name'])){
	if($_FILES['file_upload']['error'] > 0){
 	   die('An error ocurred when uploading.');
	}
	if(!getimagesize($_FILES['file_upload']['tmp_name'])){ 	
	   die('Please ensure you are uploading an image.');
	}
// Check filetype
	if($_FILES['file_upload']['type'] != 'image/png'){
	   die('Unsupported filetype uploaded.');
	}
// Check filesize
	if($_FILES['file_upload']['size'] > 1000000){
	   die('File uploaded exceeds maximum upload size.');
	}
// Check if the file exists
	if(file_exists('uploads/' . $_FILES['file_upload']['name'])){
	   die('File with that name already exists.');
	}
// Check for empty name:
	if(empty($_POST['name'])){
	   die('Cannot leave name field blank!');
	} else {
		if(!isValid($_POST['name'])){
			die('Name cannot include invalid characters!');
		}
	}

// Check for empty description:
	if(empty($_POST['description'])){
	   die('Description cannot be blank!');
	} else {
		if(!isValid($_POST['description'])){
			die('Description cannot include invalid characters!');
		}
	}



// Upload file
	if(!move_uploaded_file($_FILES['file_upload']['tmp_name'], 'uploads/' . $_FILES['file_upload']['name'])){
	$_POST = array();
	    die('Error uploading file - check destination is writeable.');
	}
	


// IF WE GET HERE, NO ERRORS, WEE!
	echo 'File uploaded successfully.';

// INSERT ACTION
	$stid = oci_parse($conn, $insertQuery);
	oci_execute($stid, OCI_DEFAULT);
	oci_commit($conn);
	header('Location: gallery.php');

}

// CONNECTION CLOSE
	oci_close($conn);
?></div></div></html>