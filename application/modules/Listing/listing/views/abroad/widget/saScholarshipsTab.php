<?php 
if(isset($scholarshipCardData) && isset($scholarshipCardData['totalCount']) && $scholarshipCardData['totalCount']>0)
{
?>
<div class="course-detail-tab saScholarships-tab" id="saScholarshipDiv">
    <div class="course-detail-mid course-detail-schlrship clearfix" style="<?php echo ($scholarshipCardData['mapFlag'] == 0)?'padding: 10px 0 0 30px':''?>">
        <h2 style="margin:0 0 10px 0;" class="course-require-hd">Scholarships for this course</h2>
        <div id="scholarshipstab" style="margin-top:0px;" class="clearwidth cons-scrollbar1 scrollbar1 soft-scroller">
        	<div class="cons-scrollbar scrollbar" style="visibility:hidden; right:8px; z-index: 99;">
                <div class="track">
                    <div class="thumb"></div>
                </div>
            </div>
            <div style="height:265px;" class="viewport">
            <div class="overview dyanamic-content entry-req-list" id="tupleDiv" style="width: 98%">
	        <?php
	        if($scholarshipCardData['mapFlag'] == 1)
	        {
	        ?>
        	<div class="schlr-tuples">
	        <?php
	        foreach ($scholarshipCardData['scholarshipData'] as $key=>$value){
	        ?>
	        <div class="sch-list last">
				<div class="sch-Ldiv" style="">
				  <div class="sch-box"><a href="<?php echo $value['url'];?>"><?php echo (strlen($value['name'])>85)?(substr($value['name'],0,82).'...'):($value['name']);?></a></div>
				  <div class="sch-box">
				     <label>Scholarship Amount</label>
				     <strong><?php echo trim($value['amountStr1'],'/-').
                                                ((strpos($value['amountStr1'],'available')!==false)?'':'');?> <?php if(is_numeric($value['awards']) && $value['awards']>0){?>
                                                <span>(<?php echo moneyFormatIndia($value['awards']).' '.((is_numeric($value['awards']))?(($value['awards']==1) ? 'student award' : 'student awards'):''); ?>)</span>
                                            <?php } ?></strong>
				  </div>
				  <div class="sch-box tac" style="">
				     <a class="btns btn-trans n-btn-width" href="<?php echo $value['url'];?>">View &amp; Apply <i class="arr__new"></i></a>
				  </div>
				</div>
			</div>
		<?php 
			}
			?>
			</div>
			<?php 
		}else{
		?>
		<div class="sch-list last no-result">
            <div class="sch-Ldiv">
                No scholarships are offered by this course but you can consider applying to generic scholarships applicable for <?php echo $scholarshipCardData['genericScholarshipsText'];?>.
            </div>
        </div>
		<?php 
		}
		?>
		
		</div><!-- overview dyanamic-content entry-req-list -->
		</div><!-- viewport -->
		</div>
    </div> <!-- course-detail-mid course-detail-schlrship flLt -->
    <div class="taR">
	    <a href="<?php echo $scholarshipCardData['viewAllUrl'];?>" class="sch-link">View<?php  echo ($scholarshipCardData['totalCount']>1?(' All '.$scholarshipCardData['totalCount'].' Scholarships'):(' '.$scholarshipCardData['totalCount'].' scholarship'));?></a> <i class="arr__new"></i>
	</div>
</div>
<?php 
}
?>