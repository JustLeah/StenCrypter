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
	Please enter the text below you wish to Stencrypt and then click "Next Step"
</div>

<div id="message-to-encrypt">
	<textarea></textarea>
</div>

<a onclick="sendText('step2.php'); return false;">
	<div class="button">
		<p>
			<span style="color:#FC1E70">N</span>EXT <span style="color:#FC1E70">S</span>TEP
		</p>
	</div>
</a>


<a onclick="loadPage('encrypt.php'); return false;">
	<div class="button">
		<p>
			<span style="color:#FC1E70">G</span>O <span style="color:#FC1E70">B</span>ACK
		</p>
	</div>
</a>