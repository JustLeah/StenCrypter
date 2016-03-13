<script type="text/javascript">
function sendText(pageToSend){
	text = $('#message-to-encrypt textarea').val();
	loadingGif();
	$.ajax({
	   type: "POST",
	   url: pageToSend,  
	   data:"md5=" + text,
	   success: function loadData(data){
			$('#feature-container').html(data);
		   }
	   });
}
</script>
<div id="step-title">
	STEP 4
</div>
<div id="feature-title">
	Please paste the MD5 in to the text area below and click "Decrypt Me"!
</div>

<div id="message-to-encrypt">
	<textarea></textarea>
</div>


<a id="next-step-button" onclick="sendText('decryptText.php?mode=md5'); return false;">
	<div class="button">
		<p>
			<span style="color:#FC1E70">D</span>ECRYPT <span style="color:#FC1E70">M</span>E
		</p>
	</div>
</a>
