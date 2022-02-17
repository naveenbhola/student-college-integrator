<?php

if(count($campus_connect_data['data'])>0)
{   
    $defaultImage = "/public/images/photoNotAvailable.gif";
    $insImage = $institute->getMainHeaderImage()->getThumbURL();
    $defaultInsImage = $insImage==""?"/public/images/avatar.gif":$insImage;
    $singleCAMode = FALSE;
    if(count($campus_connect_data['data'])==1)
    {
        $singleCAMode = TRUE;
        $singleCAData = $campus_connect_data['data'][0];
        if($singleCAData['badge']=='CurrentStudent')
        {
            $singleCABadge = 'Current Student';
        }
        else if($singleCAData['badge']=='Alumni')
        {
            $singleCABadge = 'Alumni';
        }
        else
        {
            $singleCABadge = 'Official';
        }
	$singleCAQualification = $course->getName();
    }
    
?>
<div class="talk-widget" onclick = "scrollToSpecifiedElement('campus-connect-sec-id');" style="cursor:pointer">
                        <p><i class="sprite-bg talk-icon2"></i><b>Ask your queries to current students of this college</b></p>
                        
                        <div class="talk-widget-content">
                            <ul>
                                <?php if($singleCAMode ){//for single CA
					if($singleCAData['imageURL']!=''){
						$singleCAData['imageURL'] = substr_replace($singleCAData['imageURL'],"_s.",strrpos($singleCAData['imageURL'],"." ),1);
					}
				?>
                                <li>
                                    <div class="stdnt-image"><img src="<?php echo ($singleCAData['imageURL']!=''?$singleCAData['imageURL']:$defaultImage); ?>" alt="" height="60" width="60"/></div>
                                    <div class="stdt-detail"><span style="font-size:15px;"><?php echo $singleCAData['displayName'];?></span>
                                    	</br><span class="current-stdnt-btn"><?php echo $singleCABadge; ?></span>
                                    </div>
                                </li>
                                <?php } else { //more than one CAs
					$count=0;
					foreach($campus_connect_data['data'] as $caData){
						if($caData['imageURL']!=''){
							$caData['imageURL'] = substr_replace($caData['imageURL'],"_s.",strrpos($caData['imageURL'],"." ),1); 
						}
						if($count==3){break;} //show only 3
						?>
						<li><img src="<?php echo ($caData['imageURL']!=''?$caData['imageURL']:$defaultImage);?>" alt="" height="60" width="60"/></li>
					<?php $count++;
					} ?>
                                <?php } ?>
                            </ul>
                            <?php if($singleCAMode ){//for single CA only ?>
                                <div class="stdnt-edu-box clear-width">
                                <strong>Answers queries for:</strong>
                                <p><?php echo $singleCAQualification;/*$course->getName();*/?></p>
                                </div>
                            <?php } ?>    
                            <div class="talk-comment">
                                <?php if($singleCAMode && $singleCAData['commentCount']>0){ // show comment count for single CA?>
                                    <p class="flLt">
				    <?php echo $singleCAData['commentCount']." comment".($singleCAData['commentCount']==1?"":"s"); ?>
				    </p>
                                <?php } ?>    
                                <?php if($question_detail_page):?>
                                <a class="manage-contact-btn flRt participate-btn" uniqueattr = "LISTING_COURSE_PAGES/CAMPUS_REP_WIDGET_participate" onclick=" $j('#ask_question_askAQuestion').focus(); $('askQuestionFormDiv').scrollIntoView(false);">Ask Now</a>
                                <?php else :?>
                                <a  class="manage-contact-btn flRt participate-btn" uniqueattr = "LISTING_COURSE_PAGES/CAMPUS_REP_WIDGET_participate">Ask Now</a>
                                <?php endif;?>
                                <div class="clearFix"></div>
                            </div>
                        </div>
</div>
<?php } ?>
