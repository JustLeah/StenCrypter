<script type="text/javascript">
function sendText(pageToSend){
	text = $('#message-to-encrypt textarea').val();
	loadingGif();
	$.ajax({
	   type: "POST",
	   url: pageToSend,  
	   data:"text=" + text,
	   success: function loadData(data){
			$('#feature-container').html(data);
		   }
	   });
}
</script>
<div id="step-title">
	STEP 1
</div>
<div id="feature-title">
	Please enter the text that you wish to decrypt! And then click "Next Step"
</div>


<div id="message-to-encrypt">
	<textarea></textarea>
</div>


<a id="next-step-button" onclick="sendText('decryptstep2.php'); return false;">
	<div class="button">
		<p>
			<span style="color:#FC1E70">N</span>EXT <span style="color:#FC1E70">S</span>TEP
		</p>
	</div>
</a>
