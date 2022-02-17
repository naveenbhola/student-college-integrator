<?php 
if((count($googleRes['title'])>0))
	{ ?>
		<div class="lineSpace_10 clear_B">&nbsp;</div>
                <div style="border-bottom:1px solid #eaeeed;"></div>
		<!--Start_SimilarQuestion-->
		<div class="Fnt14 bld mt10"><?php echo count($googleRes['title']);?> <?php if(count($googleRes['title'])==1){echo "question";}else{echo "questions";}?></div>
		<div style="width:650px; height:150px;overflow:auto" class="shik_box3 mt10" id="containerForLatestUpdateWidget0" divnumber="0">
                        <div style="width:100%; width:620px">
						<?php for($count=0;$count<count($googleRes['title']);$count++)
						{	?>
							 <div id="id<?php echo $count;?>" onClick="selectedQuestionId('<?php echo $googleRes['link'][$count];?>','<?php echo $count;?>','<?php echo count($googleRes['title']);?>');" class="shikIcons home_dot" style="height:30px;overflow:hidden;margin:3px;"><a onClick="trackEventByGA('SEARCH_RESULTS','SHIKSHA_CAFE_INTERMEDIATE_SOURCE_GOOGLE');" href="<?php echo $googleRes['link'][$count];?>" target= "_blank" class="bld" class="bld" title=""><?php echo $googleRes['title'][$count] ?></a><br/>
								<span class="grayFont Fnt11">
									<?php
									$str= array();
									if($googleRes['viewCount'][$count]>0){
										if($googleRes['viewCount'][$count] != 1){
												$caption = "Views";
										}else{
											$caption = "View";
										}
										$str[] = $googleRes['viewCount'][$count]." ".$caption;
									}
									if($googleRes['answers'][$count]>0){
										if($googleRes['answers'][$count] != 1){
												$caption = "Answers";
										}else{
											$caption = "Answer";
										}
										$str[] = $googleRes['answers'][$count]." ".$caption;
									}
									if($googleRes['comments'][$count]>0){
										if($googleRes['comments'][$count] != 1){
												$caption = "Comments";
										}else{
											$caption = "Comment";
										}
										$str[] = $googleRes['comments'][$count]." ".$caption;
									}
									echo implode($str,' ,');
									           ?>
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

