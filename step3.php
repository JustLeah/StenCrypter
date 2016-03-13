
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
$linecount = count(explode('.', $file));
?>
<div id="step-title">
	STEP 3
</div>
<div id="feature-title">
	Please choose which sentences to use from the book. Or press "Choose Random" to get a random selection of sentences. 
	Your book has a total of <span style="color:#FC1E70"><?=$linecount;?></span> sentences.
</div>


<a id="next-step-button" onclick="loadPage('generateRandom.php'); return false;">
	<div class="button">
		<p>
			<span style="color:#FC1E70">C</span>HOOSE <span style="color:#FC1E70">R</span>ANDOM
		</p>
	</div>
</a>

<a id="next-step-button" onclick="loadPage('encryptWholeBook.php'); return false;">
	<div class="button">
		<p>
			<span style="color:#FC1E70">F</span>ULL <span style="color:#FC1E70">B</span>OOK
		</p>
	</div>
</a>
