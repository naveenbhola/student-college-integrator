<?php 	
	$this->load->view(RANKING_PAGE_MODULE . '/ranking_enterprise/ranking_cms_header', array('addStudyAbroadJS' => true));
?>

	<div id="ranking-cms-content">
        <form id ="form_<?php echo $formName?>" name="<?php echo $formName?>" action="/<?php echo RANKING_PAGE_MODULE;?>/RankingEnterprise/saveSourceData"  method="POST" enctype="multipart/form-data">
		<h3 class="flLt"><?php echo (isset($sourceData)) ? 'Edit' : 'Add';?> Ranking Sources</h3>
		<div class="flRt">
			<input type="button" class="gray-button" value="View All Sources" onclick="window.location.href='/<?php echo RANKING_PAGE_MODULE;?>/RankingEnterprise/sourcesIndex/'"/>
		</div>
                <div class="clearFix"></div>
                <div class="cms-form-wrap">
		<ul>
                    <li>
                        <label>Ranking Publisher Name:</label>
                        <div class="cms-fields">
                            <input class="universal-txt-field cms-text-field" id="sourceName" name="sourceName" type="text" caption="Publisher Name" onblur="showErrorMessage(this,'<?php echo $formName?>');" validationtype="str" required="true" maxlength="100" value="<?php echo html_escape($sourceData[0]['name']);?>"/>
                            <div id="sourceName_error" class="errorMsg" style="display: none;"></div>
                        </div>
                    </li>

                    <?php if($action == 'edit') {
                        foreach ($sourceData as $key => $value) {
                            $usedYear[] = $value['year'];
                        } ?>
                        <li>
                            <label>Year Used:</label>
                            <div class="cms-fields">
                                <p class="show__inf">
                                    <?php echo implode(', ', $usedYear); ?>
                                </p>
                            </div>
                        </li>
                    <?php } ?>

                    <li>
                        <label>Year:</label>
                        <div class="cms-fields">
                            <select multiple name="year[]" id='year' class="year__drop">
                                <?php for($i = $endYear; $i >= $startYear; $i--) {
                                    if(!in_array($i, $usedYear)) { ?>
                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                            <div id="year_error" class="errorMsg" style="display: none;"></div>
                        </div>
                    </li>
                </ul>
                    <div class="button-wrap">
                        <input class="orange-button" type="button" name="submitSourceForm" value="Submit" onclick="saveSourceForm('<?php echo (isset($sourceData)) ? 'edit' : 'save';?>','<?php echo $formName;?>',<?php echo (isset($sourceData)) ? $sourceData[0]['publisher_id'] : '0';?>,'live');"/>
                    </div>
                </div>
		<div class="spacer10 clearFix"></div>
		<div class="spacer10 clearFix"></div>
		<div class="spacer10 clearFix"></div>
                
                </form>
	</div>
	<?php $this->load->view('common/footerNew');?>
<script type="text/javascript">
    var submissionInProgress = 0;
    var allSourceNames = new Array('<?php echo implode("','",$sourceNames);?>');             
    var formname = "<?php echo $formName; ?>";
    initiateTinYMCE(formname);
</script>