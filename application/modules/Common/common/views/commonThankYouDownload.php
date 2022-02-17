<div style="width:400px;margin: 0pt auto;">
	<div class="blkRound">
		<div class="bluRound">
            	<span class="float_R"><img class="pointer" onclick="closeThankYouLayer(); return false;" src="/public/images/fbArw.gif" border="0"/></span>
                <span class="title" id="formTitle">Thank you</span>
                <div class="clear_B"></div>
        </div>
		<div class="whtRound" style="padding:10px">
			<ul>
				<li style="margin-bottom:15px" ><h2 id="final-text" style="font-size:14px">Thank you for downloading, your download will start soon.</h2></li>
				<li style="margin-bottom:15px"><input type="submit"  onclick="closeThankYouLayer(); return false;" class="orange-button" value="OK"></li>
		</div>
		</div>
	</div>
</div>
<script>
	function closeThankYouLayer(){
			window.location = '<?php echo $_POST['redirectAfterDownload']; ?>';
	}
</script>
