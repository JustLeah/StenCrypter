<script type="text/javascript">
$('#uploadiframe').load(function() {
    $('#loading-gif').css('display', 'none');
    $('#uploadiframe').show();
});

$(document).ready(function(){
	$('#uploadiframe').hide();
});

</script>
<div id="step-title">
	STEP 1
</div>
<div id="feature-title">
	Please upload the text document to Stencrypt and then click "Next Step"
</div>


<div id="message-to-encrypt" style="width:230px; height:230px; margin:0 auto; margin-top:20px">
<div id="loading-gif">
	<img src="images/loading.gif" />
</div>
	<iframe id="uploadiframe" src="upload-form/fileupload.php" style="width:230px; height:230px; margin:0 auto;" frameBorder="0" scrolling="no"></iframe>
</div>


<a id="next-step-button" onclick="loadPage('step2.php'); return false;">
	<div class="button">
		<p>
			<span style="color:#FC1E70">N</span>EXT <span style="color:#FC1E70">S</span>TEP
		</p>
	</div>
</a>
