<?php
	$this->load->view(RANKING_PAGE_MODULE.'/ranking_enterprise/ranking_cms_header');
?>
<div id="ranking-cms-content">
	<h3 class="flLt">Ranking Page Actions</h3>
	<?php
	if(!empty($ranking_page) && in_array($action, $valid_actions)){
	?>
		<div class="ranking-grey-cont" id="ranking-grey-cont">
			<div class="floatL">
				Ranking Page Name: <h5><?php echo $ranking_page['ranking_page_text'];?></h5><br/>
				Current status: <h5><?php echo ucfirst($ranking_page['status']);?></h5><br/>
			</div>
			<div class="floatL" style="text-align:center;">
				Are you sure you want to change this ranking page status from <b><?php echo ucfirst($ranking_page['status']);?></b> to <b><?php echo ucfirst($action);?></b>.
			</div>
			<div class="spacer20 clearFix"></div>
			<div class="floatL" style="text-align:center;">
				<input type="button" class="gray-button" value="Change status" onclick="changeStatus(<?php echo $ranking_page['id'];?>, '<?php echo $action;?>');"/>
				<input type="button" class="gray-button" value="Go back"  onclick="window.location.href='/'+RANKING_PAGE_MODULE+'/RankingEnterprise/index/'"/>
			</div>
			<div class="spacer10 clearFix"></div>
			<div class="errorMsg" id="action_error_msg1" style="display:block;"></div>
		</div>
	<?php
	} else {
		?>
		<div class="ranking-grey-cont" id="ranking-grey-cont">
			<div class="floatL" style="text-align:center;">
				<h5>Not a valid request.</h5>
			</div>
		</div>
		<?php
	}
	?>
	
</div>
<div class="spacer20 clearFix"></div>
<?php
	$this->load->view('common/footerNew');
?>
