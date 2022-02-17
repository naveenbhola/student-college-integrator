<?php
if($googleRes['general']['numfound']>0)
	{ 
    $results_to_be_shown = $googleRes['general']['numfound'] < 10 ? $googleRes['general']['numfound'] : 10;
    
    ?>
		<div class="lineSpace_10 clear_B">&nbsp;</div>
                <div style="border-bottom:1px solid #eaeeed;"></div>
		<!--Start_SimilarQuestion-->
		<div class="Fnt14 bld mt10"><?php echo $results_to_be_shown;?> <?php if($results_to_be_shown==1){echo "question";}else{echo "questions";}?></div>
		<div style="width:650px; height:150px;overflow:auto" class="shik_box3 mt10" id="containerForLatestUpdateWidget0" divnumber="0">
                        <div style="width:100%; width:620px">
						<?php $count =0;
                            $questionObjArr = $googleRes['content'];
                            foreach($questionObjArr as $questionObj)
						    {	
                        ?>
							 <div id="id<?php echo $count;?>" onClick="selectedQuestionId('<?php echo $questionObj->getUrl();?>','<?php echo $count;?>','<?php echo $results_to_be_shown;?>');" class="shikIcons home_dot" style="height:auto;overflow:hidden;margin:3px;background:none;padding-left:0px;"><input type="radio" name="linkedThread[]" class="relatedQuestionSearchRadioBtn" /><a onClick="trackEventByGA('SEARCH_RESULTS','SHIKSHA_CAFE_INTERMEDIATE_SOURCE_SHIKSHA_SEARCH');" href="<?php echo $questionObj->getUrl();?>" target= "_blank" class="bld" class="bld" title=""><?php echo $questionObj->getTitle(); ?></a><br/>
								<span class="grayFont Fnt11" style="padding-left:15px;">
									<?php
									$str= array();
									if($questionObj->getViewCount() > 0){
										if($questionObj->getViewCount() != 1){
												$caption = "Views";
										}else{
											$caption = "View";
										}
										$str[] = $questionObj->getViewCount()." ".$caption;
									}
									if($questionObj->getAnswerCount()>0){
										if( $questionObj->getAnswerCount() != 1){
												$caption = "Answers";
										}else{
											$caption = "Answer";
										}
										$str[] = $questionObj->getAnswerCount()." ".$caption;
									}
									if($questionObj->getCommentCount()>0){
										if($googleRes['comments'][$count] != 1){
												$caption = "Comments";
										}else{
											$caption = "Comment";
										}
										$str[] = $questionObj->getCommentCount()." ".$caption;
									}
									echo implode($str,' ,');
									 $count++;          ?>
                            </span>
                       </div>

    <?php
    }	?>

            </div>
            &nbsp;</div>
                <div class="lineSpace_10 clear_B">&nbsp;</div>

                <div style="padding-top:10px"><input type="Submit" value="Redirect Question" onclick="addQuestionToRelatedQuestionList(questionLinkedGoogleSearchId,document.getElementById('mainQuestionIdPU').innerHTML,document.getElementById('userIdPU').innerHTML,document.getElementById('owernIdPU').innerHTML,document.getElementById('entityType').innerHTML)" class="fbBtn" id="submitButtonreportAbuse"> &nbsp; <a href="javascript:void(0);" onclick="hideOverlay();">Cancel</a></div>

                <div class="lineSpace_10 clear_B">&nbsp;</div>
			
		<?php 
	}else{
		echo '<div class="bld mt10">No Result found.</div>';
	}
?>                                               

