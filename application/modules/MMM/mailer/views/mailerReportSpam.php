<div style="width:500px;margin: 0pt auto;">
	<div class="blkRound">
		<div class="bluRound">
                       <div  class="layer-title">
			<a class="close" onclick="closeReportSpamLayer(); return false;" href="javascript:void(0);" ></a>
			<h4 id="formTitle">Report Spam</h4>
			</div>
		</div>
		<div class="whtRound" style="padding:10px">
			<ul id="confirm_reportSpam">
				<li style="margin-bottom:15px"><h2 id="final-text" style="font-size:14px">Are you sure the mailer sent to you was spam?</h2></li>
				<li style="margin-bottom:15px">
					<input type="submit"  onclick="closeReportSpamLayer(); return false;" class="orange-button" value="No">&nbsp;&nbsp;
					<?php if (count($reasons)) { ?>
					<a style="text-decoration: underline; color: #0065DE; cursor: pointer;" onclick="showReportSpamReasons(); return false;">Yes</a>
					<?php } else { ?>
					<a style="text-decoration: underline; color: #0065DE; cursor: pointer;" onclick="recordReportSpam(); return false;">Yes</a>
					<?php } ?>
				</li>
			</ul>
			<form id="form_reportSpam" action ="/mailer/Mailer/recordMailerReportSpam/" style="display:none">
				<ul>
					<li style="margin-bottom:15px"><h2 id="final-text" style="font-size:14px">Are you sure the mailer sent to you was spam?</h2></li>
					<li style="margin-bottom:15px">
						<?php foreach ($reasons as $key=>$value) { ?>
						<input type="checkbox" name="reportSpamReasons[]" value="<?php echo $key; ?>" /><?php echo $value; ?><br>
						<?php } ?>
					</li>
					<li style="margin-bottom:15px">
						<button type="button" onclick="submitReportSpam(); return false;" class="orange-button" id="submit_reportSpam">Submit</button>
						<img src= "/public/images/loader_hpg.gif" style="display:none" align="absmiddle" id="loader"/>&nbsp;&nbsp;
						<a style="text-decoration: underline; color: #0065DE; cursor: pointer;" onclick="closeReportSpamLayer(); return false;">Cancel</a>
					</li>
				</ul>
				<input type="hidden" value="<?php echo $mailerId; ?>" name="mailerId">
				<input type="hidden" value="<?php echo $mailId; ?>" name="mailId">
			</form>
		</div>
	</div>
</div>
