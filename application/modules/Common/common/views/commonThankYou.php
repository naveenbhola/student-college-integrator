<div style="width:400px;margin: 0pt auto;">
	<div class="blkRound">
		<div class="bluRound layer-title">
            	<a class="close" onclick="finalAction(); return false;" ></a>
                <div class="title" id="formTitle">Register here</div>
        </div>
		<div class="whtRound" style="padding:10px">
			<ul>
				<li style="margin-bottom:15px" ><h2 id="final-text" style="font-size:14px"></h2></li>
				<li style="margin-bottom:15px"><input type="submit"  onclick="finalAction(); return false;" class="orange-button" value="OK"></li>
		</div>
		</div>
	</div>
</div>
<script>
	$j('#formTitle').html(shortRegistrationFormHeader);
	$j('#final-text').html(shortRegistrationFormFinaltext);
	function finalAction(){
		if(isUserLoggedIn){
			$('DHTMLSuite_modalBox_transparentDiv').style.display = 'none';
			closeMessage();
		}else{
			window.location.reload()
		}
	}
</script>
