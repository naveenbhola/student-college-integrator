
<div class="cnt_bg" id='catSection'>
    <div class="rnk_contnr">
        <ul class="bst_clg">
            <li><label class="f-16">Find the best college for yourself</label></li>
            <?php if($filters['exam']) { ?>
            <li><select class="f-12 slt_fld" id='catPageExam'>
            <option value=''>Select Exam</option>
            </select>
            </li>
            <?php } ?>
            <li><select class="f-12 slt_fld" id='catPageLocation'>
            <option value=''>Select Location</option>
            </select></li>
            <li><a href="javascript:void(0);" onclick="sendToCategoryPage();" class="btn-org"  ga-attr="CATEGORYINTERLINKING">Search</a></li>
        </ul>
    </div>
</div>
<input type="hidden" id="stream" value='<?php echo $rankingPage->getStreamId();?>'>
<input type="hidden" id="substream" value='<?php echo $rankingPage->getSubstreamId();?>'>
<input type="hidden" id="specialization" value='<?php echo $rankingPage->getShikshaSpecializationId();?>'>
<input type="hidden" id="baseCourse" value='<?php echo $rankingPage->getBaseCourseId();?>'>