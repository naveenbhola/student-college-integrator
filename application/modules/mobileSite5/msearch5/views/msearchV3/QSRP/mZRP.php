<?php $this->load->view('msearch5/msearchV3/msearchPageHeader'); ?>

<div class="srp_block">
    <div class="qnas-tuples">
        <div class="topic_card">
            <div class="topic_title">
                <p class="zrp_txt">Sorry no results found for "<?php echo $keyword; ?>"</p>
            </div>

            <div class="flwup_btns">
                <a href="javascript:void(0);" class="flw_btn" id="modifySearchQues">Modify Search</a>
                <a href="javascript:void(0);" id="askQuesZRP" class="an_btn">Ask Question</a>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('msearch5/msearchV3/QSRP/msearchPageFooter'); ?>