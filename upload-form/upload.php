<?php

// A list of permitted file extensions
$allowed = array('pdf', 'txt', 'rtf');

if(isset($_FILES['upl']) && $_FILES['upl']['error'] == 0){

	$extension = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);

	if(!in_array(strtolower($extension), $allowed)){
		echo '{"status":"error"}';
		exit;
	}


	$directory = '../textUploads/' . time() . '.txt';
	

	if(move_uploaded_file($_FILES['upl']['tmp_name'], $directory)){
		echo '{"status":"success"}';
		exit;
	}
}

echo '{"status":"error"}';
exit;