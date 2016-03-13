<?php
require_once('common.php');

//Get latest text file

$path = "textUploads/"; 

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


//Latest encrypted book

$path = "bookEncode/"; 

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

$output = shell_exec('python stentext.py -b ' . $latestBook . ' -e -P ' . $latestText . ' -q');

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
