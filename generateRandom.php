<?php
require_once('common.php');
?>
<script type="text/javascript">
</script>
<?php
$path = "bookUploads/"; 

$latest_ctime = 0;
$latest_filename = '';    

$d = dir($path);
while (false !== ($entry = $d->read())) {
  $filepath = "{$path}/{$entry}";
  // could do also other checks than just checking whether the entry is a file
  if (is_file($filepath) && filectime($filepath) > $latest_ctime) {
    $latest_ctime = filectime($filepath);
    $latest_filename = $entry;
  }
}

$file = file_get_contents($path . $latest_filename);
$explodedFile = explode('.', $file);
$linecount = count($explodedFile);

//Select a random amount of lines from the book to create a new file
//We will use between 70 and 80% of the uploaded book

$percentToUse = '0.' . rand(20, 30);

$counterMax = round($percentToUse * $linecount);



$counter = 0;
$removedLines = array();

while($counter < $counterMax){
	//Generate a random number between 0 and max lines - 1
	$randNum = rand(0, $linecount - 1);
	if(!in_array($randNum, $removedLines)){
		$removedLines[] = $randNum;
		$counter++;
	}
}

$finaldoc = "";
$finalHash = "";
$first = true;
$counter = 0;

$newArray = array();

while($counter < $linecount){
	if(!in_array($counter, $removedLines)){
		$newArray[] = array($explodedFile[$counter], $counter);
	}
	$counter++;
}


shuffle($newArray);
$counter = 0;

while(count($newArray) > 0){
	
		$newSentence = array_pop($newArray);
		if($first){
			$finaldoc = $newSentence[0] . '.';
			$finalHash = $newSentence[1];
			$first = false;
		}else{
			$finaldoc = $finaldoc . ' ' . $newSentence[0] . '.';
			$finalHash = $finalHash . ',' . $newSentence[1];
		}
		
}

$finalHashHash = md5($finalHash);

$sql = 'INSERT INTO c1426527.hackathon (hash, pattern) VALUES ('.make_safe($finalHashHash).', '.make_safe($finalHash).')';
$qry = mysqli_query($connection, $sql);

//Create the new file based on this files line structure
file_put_contents('bookEncode/' . time() . '.txt', $finaldoc);
?>
<div id="step-title">
	STEP 4
</div>
<div id="feature-title">
	This is the MD5 of your random line selection, this is used needed to access the lines required to decode the file.
	<span style="color:#FC1E70"><?=$finalHashHash;?></span>
</div>

<a id="next-step-button" onclick="loadPage('step5.php'); return false;">
	<div class="button">
		<p>
			<span style="color:#FC1E70">G</span>ET <span style="color:#FC1E70">T</span>EXT
		</p>
	</div>
</a>