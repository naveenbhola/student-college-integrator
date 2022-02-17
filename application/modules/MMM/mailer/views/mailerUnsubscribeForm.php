<div style="width:500px;margin: 0pt auto;">
	<div class="blkRound">
		<div class="bluRound">
                        <div class="layer-title">
			<a class="close" onclick="closeUnsubscribeLayer(); return false;" href="javascript:void(0);"></a>
			<h4 class="title" id="formTitle">Unsubscribe</h4>
			</div>
		</div>
		<div class="whtRound" style="padding:10px">
			<ul id="confirm_unsubscibe">
				<li style="margin-bottom:15px"><h2 id="final-text" style="font-size:14px">Are you sure you want to unsubscribe this mail? You will not receive important communication from us.</h2></li>
				<li style="margin-bottom:15px">
					<input type="submit"  onclick="closeUnsubscribeLayer(); return false;" class="orange-button" value="No">&nbsp;&nbsp;
					<a style="text-decoration: underline; color: #0065DE; cursor: pointer;" onclick="Unsubscribe('<?php echo $encodedMail; ?>'); return false;">Yes</a>
				</li>
			</ul>
		</div>
	</div>
</div>
