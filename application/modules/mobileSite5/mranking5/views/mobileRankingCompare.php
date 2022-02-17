<?php
    global $checkCmpIdVal;
    if(count($checkCmpIdVal)>0 && $checkCmpIdVal !='' && in_array($courseId,$checkCmpIdVal)){
        $cmBtn = 'style="display: none;width:49%;"';
        $addBtn = 'style="display: table-cell;width:49%;"';
    } else {
        $cmBtn = 'style="display: table-cell;width:49%;"';
        $addBtn = 'style="display: none;width:49%;"';
    }
    if(!$shortlistPageName){
        $shortlistPageName=null;
    }
?>
<input type="hidden" name="compare" id="compare<?=$courseId;?>" value="<?php echo $courseId;?>"/>
<a class="btnCmpGlobal<?=$courseId;?>" data-courseid="<?php echo $courseId;?>" href="javascript:void(0);" onclick="gaTrackEventCustom('MOBILE_RANKING_PAGE_<?php echo $page_headline['page_name']; ?>', 'compare', 'add');compareCourse(this);" id="compare<?=$courseId;?>lable" data-trackid="510" <?php echo $cmBtn;?> >
    <strong id="plus-icon<?=$courseId?>" class="sprite comp-circle"></strong>
    <span>COMPARE</span>
</a>
<a href="javascript:void(0);" id="compare<?php echo $instituteId;?>-<?=$courseId;?>added" onclick="gaTrackEventCustom('MOBILE_RANKING_PAGE_<?php echo $page_headline['page_name']; ?>', 'compare', 'remove'); removeItem('compare<?php echo $instituteId;?>-<?=$courseId;?>','<?php echo base64_encode($instituteId.'::'.$courseId);?>');return false;" class="btnCmpGlobalAdded<?=$courseId;?>" <?php echo $addBtn;?> > <strong class="sprite added-icn" style="margin-right:10px;"></strong><span>ADDED</span></a>