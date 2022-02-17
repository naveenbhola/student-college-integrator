	<div id ='myActivity' class="prf-tabpane" style='display:none'>
		<?php if(!$publicProfile || ($publicProfile && $privacyDetails['activitystats'] == 'public' && !empty($stats))){ ?>
			<?php $this->load->view('profileActivityStatsSection');?>
			<?php if(!empty($activities)) { ?>
			<div class="act-show">
				<div class="data-open">
					<input type="hidden" id="ajaxCallCounterRecent" value="0"/>
					<input type="hidden" id="iter" value="<?php echo count($activities); ?>"/>
					<input type="hidden" id="remCount" value="<?php echo count($activities); ?>"> 
					<div class="data-box" id="user_all_activities">
						<?php $this->load->view('profileAllActivity'); ?>
					</div>
					<div style="text-align: center; margin-top: 10px; display: none;" id="loadingNew">
		                <img class="small-loader" border="0" alt="" id="loadingImageNew" src="//<?php echo IMGURL; ?>/public/mobile5/images/ShikshaMobileLoader.gif">
		            </div>
				</div>	
			</div>
			<?php } else { ?>
				<div class='activiy-data no_show' id='nothing_show'><p class='n-show'>There is no activity to display.</p></div>
			<?php } ?>
		<?php } else { ?>
			<div class='activiy-data no_show' id='nothing_show'><p class='n-show'>There is no activity to display.</p></div>
		<?php } ?>
	</div>