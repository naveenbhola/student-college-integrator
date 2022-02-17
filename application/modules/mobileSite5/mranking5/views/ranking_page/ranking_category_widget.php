<input type='hidden' value='<?php echo $rankingPageId;?>' id='RankingPageId'>
<div class="ranking__Result pad__all__10 clear__float brdr-top" id='catSection'>
    <h2 class="f14__semi lh__17 clr__0">Find the best college for yourself</h2>
    <div class="top__10">
        <form class="find__form">
            <ul class="ul__find">
                <?php if($filters['exam']) { ?>
                <li>

                    <select class="slct__exam" name="" id='catPageExam'>
                        <option value="">Select Exam</option>
                    </select>
                    <i class="ranking__sprite slct__i"></i>
                </li>
                <?php } ?>
                <li>
                    <select class="slct__exam" name="" id='catPageLocation'>
                        <option value="">Select Location</option>
                    </select>
                    <i class="ranking__sprite slct__i"></i>
                </li>
                <li>
                    <a class="ranking__btns __prime" ga-attr="CATEGORYINTERLINKING" onclick="sendToCategoryPage();">Search</a>
                </li>
            </ul>
        </form>
    </div>
</div>
<input type="hidden" id="stream" value='<?php echo $rankingPage->getStreamId();?>'>
<input type="hidden" id="substream" value='<?php echo $rankingPage->getSubstreamId();?>'>
<input type="hidden" id="specialization" value='<?php echo $rankingPage->getShikshaSpecializationId();?>'>
<input type="hidden" id="baseCourse" value='<?php echo $rankingPage->getBaseCourseId();?>'>