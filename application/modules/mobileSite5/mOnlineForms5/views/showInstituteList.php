    <?php  foreach ($instituteList as $inst_id=>$instituteList_object):
    $inst_id_arry = explode("_",$inst_id);$inst_id = $inst_id_arry[0];
    $last_date_apply = strtotime($institute_features[$inst_id]['last_date']);
                $todyas_date = strtotime(date('Y-m-d'));
                $exp_date = strtotime(date('2014-09-01'));
                if($last_date_apply <= $exp_date)
                {
                            continue;
                }
                $form_expired = "0";
                if($todyas_date > $last_date_apply) {
                        $form_expired = 1;
                }

                $course = $instituteList_object->getCourses()[0];
                
                /*if(!$course || !$instituteList_object->getId()) {
                        continue;
                }*/

    /*if($inst_id=='22073'){
        $institute_features[$inst_id]['discount'] = '8.3333';
        $institute_features[$inst_id]['fees'] = '1200';
    }*/

    ?>

	<section class="content-wrap2 clearfix">
		<article class="req-bro-box shortlist-box">
		<div class="details" onClick="window.location='<?php echo $course->getURL();?>';" style="cursor: pointer">
		    <div class="comp-detail-item" style="padding-top:0;">
			<h4><?php echo $instituteList_object->getName();?>,<br>
					 <span><?php echo ((is_object($instituteList_object->getMainLocation()))?$instituteList_object->getMainLocation()->getCityName().", India":'');?></span></h4>
			<ul>
                            <?php
                            $exams = $course->getAllEligibilityExams();
                            if(count($exams) > 0){ ?>
                                    <li><p style="margin:0"><label>Exams Accepted:</label> 
                                    <?php
                                    echo implode(', ',$exams); ?>
                                    </p></li>
                            <?php } ?>                            

                            
			    <li><p style="margin:0"><label>Form fee:</label> <span <?php if($institute_features[$inst_id]['discount']):?>style="text-decoration:line-through;"<?php endif;?>>Rs.<?php echo $institute_features[$inst_id]['fees'];?></span></p></li>
			    
                            <?php if($institute_features[$inst_id]['discount']):?>
                            <li><p style="padding-top:4px;margin:0"><label>Pay Only:</label> <span><strong>Rs.<?php echo round($institute_features[$inst_id]['fees']-$institute_features[$inst_id]['discount']*$institute_features[$inst_id]['fees']/100)?></strong></span></p></li>
                            <?php endif;?>
                            
                            <?php if($institute_features[$inst_id]['last_date'] && $inst_id!='35413' && $inst_id!='35407'):?>
                            <li><p style="margin:0"><label>Last Date:</label> <span <?php if($form_expired == 1): echo "style='text-decoration:line-through;'"; endif;?>><?php echo date('d M, Y',strtotime($institute_features[$inst_id]['last_date']));?></span></p></li>
                            <?php endif;?>
			</ul>
		    </div>
		    
		</div>
		<div class="clearfix"></div>
			<a id="startApp<?=$course->getId()?>" class="button blue small flLt" href="javascript: void(0)" onClick="emailResults('<?= $course->getId() ?>', '<?=base64_encode($instituteList_object->getName()) ?>', '<?php if($institute_features[$inst_id]['externalURL']!=''){echo 'false';} else {echo 'true';} ?>',359);trackEventByGAMobile('Start_Application_<?= $course->getId() ?>_<?= date('Y-m-d H:i:s');?>');" <?php if($form_expired == 1){ echo 'disabled';} ?>><span>Start Application</span></a>
		<div class="clearfix"></div>
	    </article>
	</section>
    <?php endforeach;?>
