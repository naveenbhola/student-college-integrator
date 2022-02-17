<h2><i class="campus-sprite campus-icon"></i>Shiksha Campus Representatives <a id="viewallLink" href="javascript:void(0);" onclick="viewDefaultCAListing();" <?php if(empty($searchInsName)){ ?> style="display:none;text-decoration: none;" <?php } ?> >( View All )</a></h2>
            <ul>
		<?php
                $totalCount = count($caDetailsForMarketingPage['caDetails']);
                $i=1;
                foreach($caDetailsForMarketingPage['caDetails'] as $key=>$value){ ?>
		<?php if($value['badge']=='Official'){$newBadge = 'OFFICIAL';}else if($value['badge']=='CurrentStudent'){$newBadge = 'CURRENT STUDENT';}else{$newBadge = 'ALUMNI';}?>
            	<li <?php if($i==$totalCount){?> class="last-child"<?php } ?>>
                	<div class="campus-rep-img"><img alt="<?php echo $value['displayName'];?> Campus Representative of <?php echo $value['insName'];?>" style="width:106px;height:106px;" src="<?php if($value['imageURL']!=''){ echo $value['imageURL'];}else{ echo '/public/images/marketingCAImages/default.jpg';}?>"/></div>
                    <div class="campus-rep-detail">
                    	<a target="_blank" href="<?php echo SHIKSHA_ASK_HOME.'/getUserProfile/'.urlencode($value['dName']); ?>" class="rep-name"><?php echo $value['displayName'];?> </a>
                        <span class="off-btn"><?php echo $newBadge;?></span>
                        <p><strong>Course:</strong> <?php echo $value['courseName'];?></p>
                        <p><strong>Institute:</strong> <?php echo $value['insName'];?></p>
                        <p><i class="campus-sprite ans-icon"></i><?php echo $value['totalAnsCount']; if($value['totalAnsCount']>1){echo " Answers";}else{ echo " Answer";} echo " this week";?></p>
                        <a target="_blank" href="<?php echo $value['landingPageUrl'];?>" class="ask-btn">Ask your question</a>
                    </div>
                    <div class="institute-logo">
                        <img alt="<?php echo $value['insName'].', '.$value['insLocation'];?>" src="<?php if(!empty($value['insLogoImage'])){echo $value['insLogoImage'];}else{ echo "/public/images/avatar.gif";}?>" />
                    </div>
                </li>
		<?php $i++;} ?>
            </ul>
            <div class="pagingID txt_align_r pagination2" id="paginataionPlace1">
                <?php echo $aa = preg_replace('/mba/resources/ask-current-mba-students-1"/','mba/resources/ask-current-mba-students"',$paginationHTML);       ?>
            </div>
<div class="clearFix"></div>
