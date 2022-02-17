<?php $email = explode('|',$validateuser[0]['cookiestr']);?>
<div class="enroll-details" id="_regForm">
	<p class="detail-title">Your Details  </p>
	<ul>
		<li>
		<div class="enroll-detail-col">
			<label class="dafault-pointer">First Name <span style="color:#ff0000;">*</span></label>
			<input type="text" class="enroll-detailField" disabled="disabled" value="<?php echo $validateuser[0]['firstname'];?>">
		</div>
		<div class="enroll-detail-col">
			<label class="dafault-pointer">Last Name <span style="color:#ff0000;">*</span></label>
			<input type="text" class="enroll-detailField" disabled="disabled" value="<?php echo $validateuser[0]['lastname'];?>">
		</div>
		<div class="enroll-detail-col last">
			<label class="dafault-pointer">Personal Email ID <span style="color:#ff0000;">*</span></label>
			<input type="text" class="enroll-detailField" disabled="disabled" value="<?php echo $email[0];?>">
		</div>
	    </li>
	    <li>
		<?php if($validateuser[0]['cityname'] !=''){?>
		<div class="enroll-detail-col">
			<label class="dafault-pointer">Current City <span style="color:#ff0000;">*</span></label>
			<input type="text" class="enroll-detailField" disabled="disabled" value="<?php echo $validateuser[0]['cityname'];?>">
		</div>
		<?php }?>
		<div class="enroll-detail-col">
			<label class="dafault-pointer">Mobile Number <span style="color:#ff0000;">*</span></label>
			<input type="text" class="enroll-detailField" disabled="disabled" value="<?php echo $validateuser[0]['mobile'];?>">
		</div>
	    </li>
	</ul>
</div>