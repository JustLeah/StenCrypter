<?php
require_once('common.php');

//Get latest text file

$path = "textDecrypt/"; 

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

$latestText = $path . $latest_filename;


$path = "bookUploads/"; 
//Latest encrypted book
if(isset($_POST['md5'])){
  if($_POST['md5']){
      $path = "bookEncode/"; 
  }
}

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

$latestBook = $path . $latest_filename;

//Create a new book if needed



if(isset($_POST['md5'])){

    //We need to generate a new book based on the one provided and the lines given from the MD5
    $sql = 'SELECT * FROM c1426527.hackathon WHERE hash = ' . make_safe($_POST['md5']);
    $qry = mysqli_query($connection, $sql);
    while($row = mysqli_fetch_assoc($qry)){
      $data = $row;
    }
    $pattern = explode(',', $data['pattern']);

    $explodedBook = explode('.', $latestBook);
    $finalbook = "";
    $first = true;
    foreach($pattern as $p){
      if($first){
        $finalbook = $p . '.';
        $first = false;
      }else{
        $finalbook = $finalbook . $p . '.';
      }
    }

}else{
  $finalbook = $latestBook;
}

$output = shell_exec('python stentext.py -b ' . $latestBook . ' -d -c ' . $latestText . ' -q 2>&1');

?>
<div id="step-title">
	STENCRYPTED TEXT
</div>
<div id="feature-title">
	This is your <span style="color:#FC1E70">STENCRYPTED</span> text!
</div>

<div id="message-to-encrypt">
	<textarea><?=$output;?></textarea>
</div>

<a id="next-step-button" onclick="loadPage('home-ajax.php'); return false;">
	<div class="button">
		<p>
			<span style="color:#FC1E70">G</span>O <span style="color:#FC1E70">H</span>OME
		</p>
	</div>
</a>
