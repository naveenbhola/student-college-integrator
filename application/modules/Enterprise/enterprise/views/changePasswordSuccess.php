<?php $headerComponents = array(
								'css'	=>	array('headerCms','raised_all','mainStyle','footer','cal_style'),
								'js'	=>	array('common','prototype','tooltip','md5'),
								'displayname'=> (isset($validity[0]['displayname'])?$validity[0]['displayname']:""),
								'tabName'	=>	'',
								'taburl' => site_url('enterprise/Enterprise'),
								'metaKeywords'	=>''
								);
$this->load->view('enterprise/headerCMS', $headerComponents); ?>
<div style="line-height:10px">&nbsp;</div>
<?php $this->load->view('enterprise/cmsTabs'); ?>
<div style="line-height:10px">&nbsp;</div>
<div class="mar_full_10p">
			<div style="width:223px; float:left">
				<div class="raised_greenGradient">
					<b class="b1"></b><b class="b2"></b><b class="b3" style="background-color:#F5FDE6"></b><b class="b4" style="background-color:#F5FDE6"></b>
					<div class="boxcontent_greenGradient1">
						<div class="mar_full_10p">
							<div class="lineSpace_5">&nbsp;</div>
							<div class="fontSize_12p bld">Basic Account Information</div>
							<div class="lineSpace_10">&nbsp;</div>
							<div class="OrgangeFont bld">College Name:</div>
							<div><?php echo $details['businessCollege'];?></div>
							<div class="lineSpace_10">&nbsp;</div>
							<div class="OrgangeFont bld">Display Name:</div>
							<div><?php echo $details['displayname'];?></div>
							<div class="lineSpace_10">&nbsp;</div>
							<div class="OrgangeFont bld">Login Email ID:</div>
							<div><?php echo $details['email'];?></div>
							<div class="lineSpace_20">&nbsp;</div>
							<div><a href="/enterprise/Enterprise/profile" class="bld">Manage Account Profile</a></div>
							<div class="lineSpace_20">&nbsp;</div>
						</div>
					</div>
				  <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
				</div>
			</div>
			<div style="margin-left:233px">
					<div class="OrgangeFont fontSize_14p bld">Change Password</div>
					<div class="lineSpace_10">&nbsp;</div>
					<div style="float:left; width:100%">
					<div class="raised_lgraynoBG">
						<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
							<div class="boxcontent_lgraynoBG">
									<div class="lineSpace_10">&nbsp;</div>
									    <?php if($error == 1):?>
									    <div class="mar_full_10p">Password change was unsuccessful, please enter a valid old password and <a href="/enterprise/Enterprise/changePassword">retry.</a></div>
									    <?php else:?>
										<div class="mar_full_10p">Password changed successfully.</div>
										<?php endif;?>
									<div class="lineSpace_10">&nbsp;</div>
							</div>
						<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
					</div>
					</div>
			</div>
			<div class="clear_L"></div>
</div>
<div class="lineSpace_35">&nbsp;</div>
<div style="line-height:150px">&nbsp;</div>
<?php $this->load->view('enterprise/footer'); ?>
