<?php 
?>
<div class="similar-section clearwidth exam-res-sec">
        	<h2>Popular exams for <? echo htmlentities($examWidgetTitle)?></h2>
        	<!--First widget-->
            <?php for($i=0;$i<2;$i++)
            { ?>
        	<div class="similar-tuple exam-res-tuple <?php if($i== 0)echo "flLt"; else echo "flRt";?>" style="height:272px;">
            	<div class="similar-tuple-image flLt">
   	     		 	<img alt="<?php echo htmlentities($examPageWidgetData[$i]['examName']);?>" title="<?php echo htmlentities($examPageWidgetData[$i]['examName']);?>" src="<?php echo $examPageWidgetData[$i
   	     		 	]['imageUrl']?>">
                </div>
                <div class="similar-tuple-detail exam-res-tuple-detls">
                	<div style="margin:0 0 8px !important;" class="similar-tuple-title">
                    	<strong><a target="_blank" href="<?php echo $examPageWidgetData[$i]['examPageURL'];?>"><?php echo htmlentities($examPageWidgetData[$i]['examName']);?> Exam</a></strong>
                        <p style="line-height:18px;"><?php echo (formatArticleTitle(strip_tags($examPageWidgetData[$i]['examPageDescription']),180));?></p>
                    </div>
                    </div>
                    
                    <div class="exam-point clearwidth">
                    <ul class="exam-point-list flLt">
                    <?php $j=0;
                    	  while($j<3){
                    ?>
                    <li><a href="<?php echo $examPageWidgetData[$examPageWidgetData[$i]['examName']][$j]['url'];?>" target="_blank">
                    	<?php echo htmlentities($examPageWidgetData[$examPageWidgetData[$i]['examName']][$j]['sectionTitle']);?>
                    </a></li>
                    <?php $j++;
                	}?>
                    </ul>
                    <ul class="exam-point-list flRt">
                    <?php
                    	  while($j<6){
                    ?>
                    <li><a href="<?php echo $examPageWidgetData[$examPageWidgetData[$i]['examName']][$j]['url'];?>" target="_blank">
                    	<?php echo htmlentities($examPageWidgetData[$examPageWidgetData[$i]['examName']][$j]['sectionTitle']);?>
                    </a></li>
                    <?php $j++;
                	}?>
                    </ul>
                    </div>
                    <div class="clearfix"></div>
                    <?php if($examPageWidgetData[$i]['is_downloadable'] == "yes") {?>
                    <a class="button-style dwnld-pdf" href="javascript:void(0)" style=" padding:3px 9px !important;vertical-align:middle; margin-left:5px;" onclick="downloadGuide('<?php echo base64_encode($examPageWidgetData[$i]['download_link']);?>','<?php echo $examPageWidgetData[$i]['examPageId'];?>','<?php echo $examPageWidgetData[$i]['examName']." Exam Guide";?>',17);">
                	<i class="abroad-exam-sprite pdf-icon"></i>
                	<span style="font-weight:bold" class="font-12">Download Exam Guide</span>
            		</a> 
            		<?php } ?>
            </div>
            <?php } ?>
            <!--First widget ends here-->
            <!--Second Widget-->
            <!--Second Widget Ends here -->
        </div>

        <!-- END : EXAM PAGE GUIDE WIDGET WITH GUIDE DOWNLOAD-->