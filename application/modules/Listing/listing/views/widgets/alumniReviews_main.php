<?php
$placement = array();
$infrastructure = array();
$faculty = array();
$overall = array();
$countI = 0;
$countF = 0;
$countP = 0;
$countO = 0;
foreach($alumniReviews as $alumniReview){
    
    if(($alumniReview['criteria_id'] == 1)&&($alumniReview['status'])== 'published' ){
        $infrastructure[] = $alumniReview;
        if($alumniReview['criteria_rating']!=0){
            $countI++;
        }
        $countInfrastructure += $alumniReview['criteria_rating'];
    }
    if(($alumniReview['criteria_id'] == 2)&&($alumniReview['status'])== 'published' ){
        $faculty[] = $alumniReview;
        if($alumniReview['criteria_rating']!=0){
            $countF++;
        }
        $countFaculty += $alumniReview['criteria_rating'];
    }
    if(($alumniReview['criteria_id'] == 3)&&($alumniReview['status'])== 'published' ){
        $placements[] = $alumniReview;
        if($alumniReview['criteria_rating']!=0){
            $countP++;
        }
        $countPlacement += $alumniReview['criteria_rating'];
    }
    if(($alumniReview['criteria_id'] == 4)&&($alumniReview['status'])== 'published' ){
        $overall[] = $alumniReview;
        if($alumniReview['criteria_rating']!=0){
            $countO++;
        }
        $countOverall += $alumniReview['criteria_rating'];
    }
}
if($countI!=0)
$countInfrastructure = round($countInfrastructure/$countI);

if($countP!=0)
$countPlacement = round($countPlacement/$countP);

if($countF!=0)
$countFaculty = round($countFaculty/$countF);

if($countO!=0)
$countOverall = round($countOverall/$countO);

?>

<?php if((count($infrastructure)!=0)||(count($placements)!=0)||(count($faculty)!=0)||(count($overall)!=0)){?>
<div style="height:20px;overflow:hidden;vertical-align:middle;">
<div class="Fnt14 float_L" style="vertical-align:middle;">
	<h2>Hear what alumni have to say about their institute collected exclusively on Shiksha.com</h2>
</div>
<div class="float_R">
<!-- Google Plus Code -->
<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
<span style="float: right; width: 66px;">
	<g:plusone size="medium"></g:plusone>
</span>
<!-- Google Plus Code -->
</div>
</div>
<?php if(count($placements)!=0){?>
<a name="placements"></a>
<div class="nl_aluminiSpk mt13">
<div class="float_L Fnt16 bld pt3"><h3>Placements</h3>
<?php for($i=0;($i<$countPlacement)&&($countPlacement!=0);$i++){ ?>
<img src ="/public/images/nlt_aSpk_Star.gif" border="0">
<?php } ?>
    <?php //for($i=5;($i>$countPlacement)&&($countPlacement!=0);$i--){?>
<!--<img src ="/public/images/nlt_str_blk.gif" >-->
<?php //} ?>
</div>
    <?php if($countPlacement!=0){?>
<div class="float_L Fnt14 pt6" style="margin-left:12px"><?php echo $countPlacement?>/5 <span class="sepClr">
&nbsp; | &nbsp; </span></div>
    <?php }?>
<div class="float_L pt2">
<div class="nl_aluniniU Fnt14 pt3" style="height:20px; line-height:20px"><?php echo count($placements); ?> alumni reviews</div>
</div>
</div>
<?php foreach($placements as $pArray){?>
<div class="wdh100 mt10">
<?php if($pArray['criteria_rating']!=0){?>
<div class="float_R w130">
    <?php for($i=0;$i<$pArray['criteria_rating'];$i++){ ?>
<img src ="/public/images/nlt_str_full.gif" border="0">
<?php } ?>
    <?php for($i=5;$i>$pArray['criteria_rating'];$i--){?>
<img src ="/public/images/nlt_str_blk.gif" >
<?php } echo $pArray['criteria_rating']; ?>/5

</div>
<?php }?>
<div class="mr140 bld"><?php echo $pArray['name']?>, Class of <?php echo $pArray['course_comp_year']."-".$pArray['course_name'] ;?></div>
<div class="clear_B">&nbsp;</div>
<div class="wdh100">
<p><?php echo $pArray['criteria_desc'];?></p>
<div class="ln">&nbsp;</div>
<?php
				    $replyThreadId = $pArray['thread_id'];
				    if($replyThreadId!='' && $replyThreadId!=0){
				      foreach($alumniReviewsReply[$replyThreadId] as $temp){
					      if($temp['parentId'] == $replyThreadId){
						if($temp['msgTxt']!=''){
				  ?>
				  <div style="width:100%;">
				    <div  style="padding:0px 10px 10px 10px;_padding:0px 10px 10px 10px;">
				      <div style="padding:10px 10px 10px 10px;_padding:10px 10px 10px 10px;margin-right:35px;margin-left:30px;background-color:white;border-left:solid 2px #fcecc8;text-align:justify;">
					      <div style="font-size:11px;color:#7c7c7c;padding-left:10px;_padding-left:10px;margin-right:95px"><FONT COLOR="black"><b>Re: </b></FONT><b>by <?php echo $pArray['institute_name'];?></b></div>
					      <div style="margin-right:20px;padding-left:10px;_padding-left:10px;"><span><?php echo nl2br($temp['msgTxt']); ?></span></div>
				      </div>
				    </div>
				    </div>
				  <?php

						}
					      }
					  }
				    }
				  ?>
</div>
</div>
<?php }?>


<?php }?>

<?php if(count($infrastructure)!=0){?>
<a name="infrastructure"></a>

<div class="nl_aluminiSpk mt13">
<div class="float_L Fnt16 bld pt3"><h3>Infrastructure / Teaching Facilities</h3>

<?php for($i=0;($i<$countInfrastructure)&&($countInfrastructure!=0);$i++){ ?>
<img src ="/public/images/nlt_aSpk_Star.gif" border="0">
<?php } ?>
    <?php //for($i=5;($i>$countInfrastructure)&&($countInfrastructure!=0);$i--){?>
<!--<img src ="/public/images/nlt_str_blk.gif" >-->
<?php //}?>
</div>
    <?php if($countInfrastructure!=0){?>
<div class="float_L Fnt14 pt6" style="margin-left:12px"><?php echo $countInfrastructure?>/5 <span class="sepClr">
&nbsp; | &nbsp; </span></div>
    <?php }?>
<div class="float_L pt2">
<div class="nl_aluniniU Fnt14 pt3" style="height:20px; line-height:20px"><?php echo count($infrastructure); ?> alumni reviews</div>
</div>
</div>
<?php foreach($infrastructure as $iArray){?>
<div class="wdh100 mt10">
<?php if($iArray['criteria_rating']!=0){?>
<div class="float_R w130">
    <?php for($i=0;$i<$iArray['criteria_rating'];$i++){ ?>
<img src ="/public/images/nlt_str_full.gif" border="0">
<?php } ?>
    <?php for($i=5;$i>$iArray['criteria_rating'];$i--){?>
<img src ="/public/images/nlt_str_blk.gif" >
<?php } echo $iArray['criteria_rating']; ?>/5

</div>
<?php }?>
<div class="mr140 bld"><?php echo $iArray['name']?>, Class of <?php echo $iArray['course_comp_year']."-".$iArray['course_name'] ;?></div>
<div class="clear_B">&nbsp;</div>
<div class="wdh100">
<p><?php echo $iArray['criteria_desc'];?></p>
<div class="ln">&nbsp;</div>
<?php
				    $replyThreadId = $iArray['thread_id'];
				    if($replyThreadId!='' && $replyThreadId!=0){
				      foreach($alumniReviewsReply[$replyThreadId] as $temp){
					      if($temp['parentId'] == $replyThreadId){
						if($temp['msgTxt']!=''){
				  ?>
				  <div style="width:100%;">
				    <div  style="padding:0px 10px 10px 10px;_padding:0px 10px 10px 10px;">
				      <div style="padding:10px 10px 10px 10px;_padding:10px 10px 10px 10px;margin-right:35px;margin-left:30px;background-color:white;border-left:solid 2px #fcecc8;text-align:justify;">
					      <div style="font-size:11px;color:#7c7c7c;padding-left:10px;_padding-left:10px;margin-right:95px"><FONT COLOR="black"><b>Re: </b></FONT><b>by <?php echo $iArray['institute_name'];?></b></div>
					      <div style="margin-right:20px;padding-left:10px;_padding-left:10px;"><span><?php echo nl2br($temp['msgTxt']); ?></span></div>
				      </div>
				    </div>
				    </div>
				  <?php

						}
					      }
					  }
				    }
				  ?>
</div>
</div>
<?php }?>
<?php }?>



<?php if(count($faculty)!=0){?>
<a name="faculty"></a>

<div class="nl_aluminiSpk mt13">
<div class="float_L Fnt16 bld pt3"><h3>Faculty</h3>
<?php for($i=0;($i<$countFaculty)&&($countFaculty!=0);$i++){ ?>
<img src ="/public/images/nlt_aSpk_Star.gif" border="0">
<?php } ?>
    <?php //for($i=5;($i>$countFaculty)&&($countFaculty!=0);$i--){?>
<!--<img src ="/public/images/nlt_str_blk.gif" >-->
<?php //} ?>
</div>
    <?php if($countFaculty!=0){?>
<div class="float_L Fnt14 pt6" style="margin-left:12px"><?php echo $countFaculty?>/5 <span class="sepClr">
&nbsp; | &nbsp; </span></div>
    <?php }?>
<div class="float_L pt2">
<div class="nl_aluniniU Fnt14 pt3" style="height:20px; line-height:20px"><?php echo count($faculty); ?> alumni reviews</div>
</div>
</div>
<?php foreach($faculty as $fArray){?>
<div class="wdh100 mt10">
<?php if($fArray['criteria_rating']!=0){?>
<div class="float_R w130">
    <?php for($i=0;$i<$fArray['criteria_rating'];$i++){ ?>
<img src ="/public/images/nlt_str_full.gif" border="0">
<?php } ?>
    <?php for($i=5;$i>$fArray['criteria_rating'];$i--){?>
<img src ="/public/images/nlt_str_blk.gif" >
<?php } echo $fArray['criteria_rating']; ?>/5

</div>
    <?php }?>
<div class="mr140 bld"><?php echo $fArray['name']?>, Class of <?php echo $fArray['course_comp_year']."-".$fArray['course_name'] ;?></div>
<div class="clear_B">&nbsp;</div>
<div class="wdh100">
<p><?php echo $fArray['criteria_desc'];?></p>
<div class="ln">&nbsp;</div>
<?php
				    $replyThreadId = $fArray['thread_id'];
				    if($replyThreadId!='' && $replyThreadId!=0){
				      foreach($alumniReviewsReply[$replyThreadId] as $temp){
					      if($temp['parentId'] == $replyThreadId){
						if($temp['msgTxt']!=''){
				  ?>
				  <div style="width:100%;">
				    <div  style="padding:0px 10px 10px 10px;_padding:0px 10px 10px 10px;">
				      <div style="padding:10px 10px 10px 10px;_padding:10px 10px 10px 10px;margin-right:35px;margin-left:30px;background-color:white;border-left:solid 2px #fcecc8;text-align:justify;">
					      <div style="font-size:11px;color:#7c7c7c;padding-left:10px;_padding-left:10px;margin-right:95px"><FONT COLOR="black"><b>Re: </b></FONT><b>by <?php echo $fArray['institute_name'];?></b></div>
					      <div style="margin-right:20px;padding-left:10px;_padding-left:10px;"><span><?php echo nl2br($temp['msgTxt']); ?></span></div>
				      </div>
				    </div>
				    </div>
				  <?php

						}
					      }
					  }
				    }
				  ?>
</div>
</div>
<?php }?>

<?php }?>

<?php if(count($overall)!=0){?>
<a name="overall"></a>
<div class="nl_aluminiSpk mt13">
<div class="float_L Fnt16 bld pt3"><h3>Overall Feedback</h3>
<?php for($i=0;($i<$countOverall)&&($countOverall!=0);$i++){ ?>
<img src ="/public/images/nlt_aSpk_Star.gif" border="0">
<?php } ?>
    <?php //for($i=5;($i>$countOverall)&&($countOverall!=0);$i--){?>
<!--<img src ="/public/images/nlt_str_blk.gif" >-->
<?php //} ?>
</div>
<?php if($countOverall!=0){?>
<div class="float_L Fnt14 pt6" style="margin-left:12px"><?php echo $countOverall?>/5 <span class="sepClr">
&nbsp; | &nbsp; </span></div>
<?php }?>
<div class="float_L pt2">
<div class="nl_aluniniU Fnt14 pt3" style="height:20px; line-height:20px"><?php echo count($overall); ?> alumni reviews</div>
</div>
</div>
<?php foreach($overall as $oArray){?>
<div class="wdh100 mt10">
<?php if($oArray['criteria_rating']!=0){?>
<div class="float_R w130">
    <?php for($i=0;$i<$oArray['criteria_rating'];$i++){ ?>
<img src ="/public/images/nlt_str_full.gif" border="0">
<?php } ?>
    <?php for($i=5;$i>$oArray['criteria_rating'];$i--){?>
<img src ="/public/images/nlt_str_blk.gif" >
<?php } echo $oArray['criteria_rating']; ?>/5

</div>
    <?php }?>
<div class="mr140 bld"><?php echo $oArray['name']?>, Class of <?php echo $oArray['course_comp_year']."-".$oArray['course_name'] ;?></div>
<div class="clear_B">&nbsp;</div>
<div class="wdh100">
<p><?php echo $oArray['criteria_desc'];?></p>
<div class="ln">&nbsp;</div>
<?php
				    $replyThreadId = $oArray['thread_id'];
				    if($replyThreadId!='' && $replyThreadId!=0){
				      foreach($alumniReviewsReply[$replyThreadId] as $temp){
					      if($temp['parentId'] == $replyThreadId){
						if($temp['msgTxt']!=''){
				  ?>
				  <div style="width:100%;">
				    <div  style="padding:0px 10px 10px 10px;_padding:0px 10px 10px 10px;">
				      <div style="padding:10px 10px 10px 10px;_padding:10px 10px 10px 10px;margin-right:35px;margin-left:30px;background-color:white;border-left:solid 2px #fcecc8;text-align:justify;">
					      <div style="font-size:11px;color:#7c7c7c;padding-left:10px;_padding-left:10px;margin-right:95px"><FONT COLOR="black"><b>Re: </b></FONT><b>by <?php echo $oArray['institute_name'];?></b></div>
					      <div style="margin-right:20px;padding-left:10px;_padding-left:10px;"><span><?php echo nl2br($temp['msgTxt']); ?></span></div>
				      </div>
				    </div>
				    </div>
				  <?php

						}
					      }
					  }
				    }
				  ?>
</div>
</div>
<?php }?>
<?php }?>

<div class="Fnt11 fcGya mtb10">The views expressed above do not represent the opinion of Info Edge (India) Limited</div>
<?php }?>