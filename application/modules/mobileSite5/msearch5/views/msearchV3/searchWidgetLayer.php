<?php 
if($tabRequested == 'colleges'){
    $collegeStyle = '';
    $examStyle    = 'style="display:none"';
}
else if($tabRequested == 'exams'){
    $collegeStyle = 'style="display:none"';
    $examStyle    = '';
}
?>
<div id="searchInnerCont" class="search-inner-container">
    <div id="mainTabs" class="search-widget-tabs">
        <?php if($pageName != 'searchWidgetPage') {?>
        <a id="closeCollegeSearchLayer" data-rel="back" class="search-remove-tab" href="javascript:void(0);">×</a>
        <?php }else{ ?>
            <a id="closeCollegeSearchLayer" class="search-remove-tab" href="javascript:void(0);" onClick="window.history.go(-1)">×</a>
        <?php } ?>
        <ul>
            <li><a <?php if($tabRequested=='colleges') echo 'class="active"'; ?> tabname="colleges" href="javascript:void(0);">COLLEGES</a></li>
            <li><a <?php if($tabRequested=='exams') echo 'class="active"'; ?> tabname="exams" href="javascript:void(0);">EXAMS</a></li>
            <li><a <?php if($tabRequested=='questions') echo 'class="active"'; ?> tabname="questions" href="javascript:void(0);">QUESTIONS</a></li>
        </ul>
    </div>
</div>

<div style="" id="searchInnerCont_2" class="search-inner-container">
    <div id="srchContTab_colleges" class="tbs search-field clearfix" <?php echo $collegeStyle; ?>>
        <form formname="colleges" onsubmit="return false;">

            <ul>
                <li>
                    <?php $this->load->view('msearch5/msearchV3/collegeWidget'); ?>
                </li>
                <li id="collegeLocationBox">
                    <?php $this->load->view('msearch5/msearchV3/locationWidget'); ?>
                    <?php //$this->load->view('msearch5/msearchV3/advancedOptionsWidget', array('id' => 0)); ?>
                </li>
                <li style="display:none" class="adv-optionList">
                    <p class="adv-title">ADVANCED OPTIONS</p>
                </li>
                <li style="display:none">
                    <?php $this->load->view('msearch5/msearchV3/advancedOptionsWidget', array('id' => 1)); ?>
                </li>
                <li style="display:none">
                    <?php $this->load->view('msearch5/msearchV3/advancedOptionsWidget', array('id' => 2)); ?>
                </li>
                <li style="display:none">
                    <?php $this->load->view('msearch5/msearchV3/advancedOptionsWidget', array('id' => 3)); ?>
                </li>
               
                <li style="display:none;">
                    <a class="adv-search-link" href="#"><span>ADVANCE OPTIONS</span> <span class="search-add-icon">&#43;</span></a>
                </li>
                <li>
                    <input type="button" class="green-btn" value="SEARCH" id="submitButtonCollegeSearch" onclick="autoSuggestorInstanceArray.autoSuggestorInstanceSearch.handleInputKeys($.Event( 'keypress', { keyCode: 13 } )); event.stopPropagation();">
                </li>
                
            </ul>
        </form>
    </div>
    <div id="srchContTab_exams" <?php echo $examStyle; ?> class="tbs search-field clearfix">
        <?php echo $this->load->view('msearch5/msearchV3/exam_form'); ?>
    </div>
    <div id="srchContTab_questions" <?php echo $examStyle; ?> class="tbs search-field clearfix">
        <?php echo $this->load->view('msearch5/msearchV3/question_form'); ?>
    </div>
</div>