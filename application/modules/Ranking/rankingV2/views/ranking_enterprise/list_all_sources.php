<?php
	$this->load->view(RANKING_PAGE_MODULE . '/ranking_enterprise/ranking_cms_header', array('addStudyAbroadJS' => true));
?>
<div id="ranking-cms-content">
	<h3 class="flLt">Ranking Sources</h3>
	<div class="flRt">
		<input type="button" class="gray-button" value="Add Sources" onclick="window.location.href='/<?php echo RANKING_PAGE_MODULE;?>/RankingEnterprise/manageSources/'"/>
		<input type="button" class="gray-button" value="Manage Ranking Pages" onclick="window.location.href='/<?php echo RANKING_PAGE_MODULE;?>/RankingEnterprise/index/'"/>
	</div>
	<div class="clearFix"></div>
		<?php
		if(count($sourceData) > 0){
                    ?>
        <table cellpadding="0" cellspacing="0" width="100%" border="0" class="cms-ranking-table2">
        <tr>
			<th>Ranking Source</th>
			<th >Ranking Year </th>
			<th> Action </th>
		</tr>
                <?php
			foreach($sourceData as $source){
			?>
			<tr>
				<td>
				<?php echo html_escape($source['name']); ?>
				<br>
                 <a href="<?php echo '/'.RANKING_PAGE_MODULE;?>/RankingEnterprise/manageSources/edit/<?php echo $source['publisher_id'];?>">Edit</a>
                 <td class="repeat__td">
                 <?php foreach($source['publisher_data'] as $publisher_data)
                 {
                 	echo "<p>" .$publisher_data['year'] ."</p>";
                 } ?>
                 </td>               
				<td class="repeat__td">
                 <?php
                 foreach($source['publisher_data'] as $publisher_data)
                 {
                  if($publisher_data['status'] == 'live') { ?>
                    <p class=""><a href="javascript:void(0);" onclick="updateSourceStatus(<?php echo $publisher_data['source_id'];?>,'disable')">Disable</a></p>
                    <?php } else if($publisher_data['status'] == 'disable') { ?>
                    <p><a href="javascript:void(0);" onclick="updateSourceStatus(<?php echo $publisher_data['source_id'];?>,'live');">Make Live</a></p>
                    <?php } } ?>
				</td>
			</tr>
			<?php
			} ?>
        </table>
        <?php }
                else {
                    echo "<h1>No Records to display</h1>";
                }
		?>
	
</div>
<div class="spacer10 clearFix"></div>
<div class="spacer20 clearFix"></div>
<?php
	$this->load->view('common/footerNew');
?>