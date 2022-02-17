<?php
global $checkCmpIdVal;

if($isPaid || (is_object($brochureURL) && $brochureURL->getCourseBrochure($courseId) !=''))
{ 
    if(count($checkCmpIdVal)>0 && $checkCmpIdVal !='' && in_array($courseId,$checkCmpIdVal))
    {
        $cmBtn = 'style="display: none;"';
        $addBtn = 'style="display: block;"';
        }else{
        $cmBtn = 'style="display: block;"';
        $addBtn = 'style="display: none;"';
    }
    if($shortlistPageName){$shortlistPageName = $shortlistPageName;}else{$shortlistPageName=null;} 
    ?>
       
<input type="hidden" name="compare" id="compare<?php echo $instituteId;?>-<?=$courseId;?>" value="<?php echo $instituteId.'::'.$courseId;?>"/>
<a class="button gray small flRt btnCmpGlobal<?=$courseId;?>"  href="javascript:void(0);" onclick="animatePlus('<?php echo $courseId;?>');setCompareTrackingId('<?php echo $comparetrackingPageKeyId;?>');updateAddCompareList('compare<?php echo $instituteId;?>-<?=$courseId;?>','<?=$shortlistPageName;?>');
return false;" id="compare<?php echo $instituteId;?>-<?=$courseId;?>lable" <?php echo $cmBtn;?> >
<strong id="plus-icon<?=$courseId?>" class="plus-icon"></strong>
<span style="margin-left: 24px; position: relative; top: 1px;">Compare</span>
</a>
<a href="javascript:void(0);" id="compare<?php echo $instituteId;?>-<?=$courseId;?>added" onclick="removeItem('compare<?php echo $instituteId;?>-<?=$courseId;?>','<?php echo base64_encode($instituteId.'::'.$courseId);?>');return false;" class="button gray small flRt btnCmpGlobalAdded<?=$courseId;?>" <?php echo $addBtn;?> > <i class="sprite added-icn"></i><span style="relative; top: 1px;">Added</span></a>
<?php }?>