<?php
	$this->load->view(RANKING_PAGE_MODULE.'/ranking_enterprise/ranking_cms_header');
?>
<div id="ranking-cms-content">
	<h3 class="flLt">Edit Source Page</h3>
	<div class="flRt"><input type="button" class="gray-button" value="Ranking Main Page" onclick="window.location.href='/<?=RANKING_PAGE_MODULE?>/RankingEnterprise/index/'"/></div>
	
	<div class="ranking-grey-cont" id="ranking-grey-cont">
		<div class="floatL">
			Ranking Page Name: <h5><?php echo $ranking_page['ranking_page_text'];?></h5><br/>
			Current status: <h5><?php echo ucfirst($ranking_page['status']);?></h5><br/>
		</div>
		<div class="spacer20 clearFix"></div>
	</div>
	
	<div class="ranking-cate-section clear-width" style="box-sizing:border-box;">
        <div class="ranking-cate-cols" style="margin-top:10px;">
			<label>Select Source*:</label>
			<div>
				<select multiple name="source" id="source" style="height: 100px">
					<?php foreach($sources as $source) {
						$selected = "";
						if(in_array($source['source_id'], $ranking_page_sources)) {
							$selected = "selected='selected'";
						} ?>
						<option <?php echo $selected;?> value="<?php echo $source['source_id'];?>"><?php echo $source['name'] . " ".$source['year'] ;?></option>
					<?php } ?>
				</select>
			</div>
			<input type="button" value="Save" class="gray-button" disable="disable" onclick="updateSourceRankingPage();" style="margin:10px 0 10px 0px;"/>
		</div>
    </div>
	
	<div class="ranking-error-cont" id="ranking-error-cont" style="display:none;">
		<div class="floatL" id="ranking-error-value-cont"></div>
	</div>
	
</div>

<div class="spacer20 clearFix"></div>
<?php
	$this->load->view('common/footerNew');
?>
<script>
	var ranking_page_details = eval(<?php echo json_encode($ranking_page); ?>);
	Ranking.setRankingPageDetails(ranking_page_details);
</script>