<style>
.input-txt {
    background-position: 10px 12px; 
    background-repeat: no-repeat; 
    width: 49%;
    font-size: 14px; 
    padding: 7px 11px 7px 11px; 
    border: 1px solid #ddd; 
    position: relative;
}

.l-width{
    display: inline-block;
    vertical-align: middle;
    width: 171px;
    font-weight: 600;
}

.e-msg{
    margin-left: 0px;
    padding-top: 5px;
}
.alignBox{
    width:371px;
    vertical-align: middle;
}

.uni-table{padding-left: 210px;}
#otherFields .uni-table{padding: 5px 0;padding-left: 210px;}
#otherFields .uni-table tr td{padding: 0;}
#otherFields .uni-table tr td > div > label{vertical-align: top;}
.ex-tble tr{
    display:block;
    margin-bottom: 20px;
}
#groupDiv>.SumoSelect>.CaptionCont {
    line-height: 35px;
    width: 351px;
    color: #7d7d7d;
    box-shadow: 1px 1px 2px rgba(136, 136, 136, 0.22);
    -webkit-box-shadow: 1px 1px 2px rgba(136, 136, 136, 0.22);
    -moz-box-shadow: 1px 1px 2px rgba(136, 136, 136, 0.22);
    font-size: 14px;
    font-weight: 600;
    cursor: pointer
}

#groupDiv>.SumoSelect>.optWrapper.open {
    top: 37px;
    z-index: 99;
}

#groupDiv>.SumoSelect {
    margin: 0 13px 0 0;
    text-align: left;
    vertical-align: middle;
}

#groupDiv .SumoSelect>.CaptionCont {
    height: auto
}


.disabled {pointer-events: none;cursor: default;}
.hideDiv{display:none;}
.post-sec{margin: 20px auto; text-align: center;}
a.post-btn{background: #fff; border: 1px solid #ccc; padding: 5px 36px; color: #333; font-weight: 600; text-decoration: none;}
.updt-sec{width:100%; margin:10px 0 10px; border-top: 1px solid #eee; clear: both; padding-top: 10px; display: inline-block;}
.updt-sec a{font-size: 16px; margin: 0 8px; display: inline-block;}
.updt-sec a.active{font-weight: 600;color:#333;}
.formInput{margin: 10px 0}
.formInput textarea{width: 85%;}
.formInput  a{ bottom: 19px;left: 8px; margin: 0; position: relative;}

.examSearch {
    list-style-type: none;
    padding: 0;
    margin: 0;
    position: absolute;
    overflow-y: auto;
    width: 377px;
    z-index: 1000;
    right: 156px;
    height: auto;
    max-height: 203px;
}

.examSearch #examSearchFeatured{
    right: 264px;
    width: 494px;
}

#examSearchDestOptions{
    right:156px;
}

#examSearchFeatured{
    right: 262px;
    width: 495px;
}
.customSelect input{height: auto}

.examSearch li a {
    border: 1px solid #ddd; 
    margin-top: -1px; 
    background-color: #f6f6f6; 
    padding: 5px;
    text-decoration: none;
    font-size: 13px; 
    color: black;
    display: block; 
}

.examSearch li a:hover{
    background-color: #eee; 
}

.manage-heading{
    font-size:14px;
    font-weight:600;
    margin-bottom:20px;
}

.confirm-layer{position:fixed;width:100%;height:100%;background:rgba(0,0,0,0.5);top:0;left:0;right:0;bottom:0;z-index: 999; display: none}

.uni-table.ext__table td{padding:10px 0;}
.SumoSelect{border: 1px solid #e6e5e5}
#otherFields{margin-top: -10px}
#otherFields .ex-tble tr{display: table-row;}
#otherFields .ex-tble tr td{padding: 0px 0px 20px}
#groupDivDest .SumoSelect{height: 36px;width: 52%;}
#groupDivDest .CaptionCont.SlectBox, .#groupDivDest .CaptionCont > span{}
#groupDivDest .SumoSelect > .CaptionCont{height: 35px !important;line-height: 35px !important;width:93%;}
#groupDivDest .SumoSelect > .optWrapper.open{top: 37px !important}
</style>

<?php 
$customElementCount = 1;
?>
<div class="abroad-cms-wrapper" style="margin: 0px;min-height:380px;">
	<div class="abroad-cms-content">
        <div class="abroad-cms-rt-box">
        <?php $this->load->view('/examPages/cms/manageTabs',array('tab'=>$activePage));?>
            <div class="cms-form-wrapper clear-width addMainExamContent" style="margin-bottom:5px;border-bottom:none;">
            	 <form id ="featuredContentForm" name="featuredContentForm">
                        <div style="position:relative;">
        	            	<table class="uni-table" cellspacing="0" cellpadding="0">
        	            		<tr>
        	            			<td>
                                        <div>
                                            <label class="l-width">Originating Exam:</label>

                                                 <input type="text" name="examField" id="examField" class="input-txt" onkeyup="searchExam('examSearchOptions','examField')" placeholder="Search for exams.." minlength="1" maxlength="50" required="true" caption="exam" validate="validateStr" autocomplete="off">
                                                                                                 
                                                <ul id="examSearchOptions" class="examSearch" style="display:none;">
                                                    <?php foreach($examList as $id=>$name){?>
                                                    	<li><a id="exam_<?=$id?>" onclick="getGroupList(this,'<?=$id?>')"><?=$name?></a></li>
                                                    <?php } ?>
                                                </ul> 
                                                <div><div id="examField_error" class="errorMsg e-msg" style="display:none;margin-left:189px;">&nbsp;</div></div>
                                        </div>
        	            			</td>
        	            		</tr>
        	            	</table>
                        </div>
                        <div id="otherFields" class="hideDiv">
                                <div>
                                    <table class="uni-table ex-tble" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td>                                        
                                                <div id="groupDiv">
                                                <label class="l-width">Originating Exam Course Group(s):</label>
                                                <select id="groupOption" class="groupList" placeholder="Select Course Group" multiple="multiple">
                                                   
                                                </select> 
                                                <div><div id="groupOption_error" class="errorMsg e-msg" style="display:none;margin-left: 26%;">&nbsp;</div></div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php if($activePage == 'featured_institute'){?>
                                            <tr>
                                                <td>   
                                                    <label class="l-width">Enter Destination University Or College Name:</label>
                                                    <div class="findSrCnt customSelect multi-slct alignBox">
                                                        <div class="custom-srch" style="position:static;height:30px;display:block;">
                                                            <input minlength="2" maxlength="100" caption="institute or university" type="text" class="input-txt" placeholder=" Search Institute" id="instField" autocomplete="off" name="instField" value="" validate = "validateStr" required = "1"/>
                                                            <div><div id="instField_error" class="errorMsg e-msg" style="display:none;">&nbsp;</div></div>
                                                            <div class="search-college-layer" id="institute-list-container" style="display:none; width:370px;border:1px solid #ccc; top:35px;height: auto;max-height: 300px;overflow-y:auto;" ></div>
                                                        </div>
                                                        <input type="hidden" style="" id="instituteId" name="instituteId" value="" />
                                                        <input type="hidden" style="" id="instituteName" name="instituteName" value="" />
                                                    </div>                                     
                                                </td>
                                            </tr>
                                        <?php }elseif($activePage == 'featured_exam'){?>
                                            <tr>
                                                <td>    
                                                    <div style="position:relative">
                                                        <label class="l-width">Destination Exam You Want to Set As Featured:</label>

                                                         <input type="text" name="examFieldDest" id="examFieldDest" class="input-txt" onkeyup="searchExam('examSearchDestOptions','examFieldDest')" placeholder="Search for exams.." minlength="1" maxlength="50" required="true" caption="exam" validate="validateStr" autocomplete="off">
                                                                                                         
                                                        <ul id="examSearchDestOptions" class="examSearch" style="display:none;">
                                                            <?php foreach($examList as $id=>$name){?>
                                                                <li><a id="examDest_<?=$id?>" onclick="getGroupSelectionList('<?=$id?>',this)"><?=$name?></a></li>
                                                            <?php } ?>
                                                        </ul> 
                                                        <div><div id="examFieldDest_error" class="errorMsg e-msg" style="display:none;margin-left:189px;">&nbsp;</div></div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>

                                    </table>
                                </div>                       
                        </div>
                        <div id="otherField1" class="hideDiv">
                                <div style="position:relative;">
                                    <table class="uni-table ext__table" cellspacing="0" cellpadding="0" style="padding-top:0px;">
                                    <?php if($activePage == 'featured_institute'){?>
                                            <tr>
                                                <td>                                        
                                                    <div id="groupDiv">
                                                        <label class="l-width">Select Destination Course:</label>
                                                        <select id="courseOption" class="courseList" placeholder="Select Course">
                                                           
                                                        </select> 
                                                        <div><div id="courseOption_error" class="errorMsg e-msg" style="display:none;margin-left: 26%;">&nbsp;</div></div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php }elseif($activePage == 'featured_exam'){ ?>
                                            <tr>
                                                <td>                                        
                                                    <div id="groupDivDest">
                                                        <label class="l-width">Destination Exam Course Group:</label>
                                                        <select id="groupOptionDest" class="groupOption" placeholder="Select Group">
                                                           
                                                        </select> 
                                                        <div><div id="groupOptionDest_error" class="errorMsg e-msg" style="display:none;">&nbsp;</div></div>
                                                    </div>
                                                </td>
                                            </tr>

                                        <?php } ?>
                                        <?php //$this->load->view('examPages/cms/manageFeaturedExtraContent');?>
                                        <tr>
                                                <td>
                                                    <div>
                                                        <label class="l-width">CTA Text:</label>
                                                            <input type="text" name="CTA_text" id="CTA_text" class="input-txt" minlength="1" maxlength="20"caption="CTA text" validate="validateStr" autocomplete="off" value="<?=$featuredEditData['CTA_text']?>">
                                                                                                            
                                                            <div style="display:none;"><div id="CTA_text_error" class="errorMsg e-msg" style="margin-left:189px;">&nbsp;</div></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <label class="l-width">Redirection URL:</label>
                                                            <input type="text" name="redirect_url" id="redirect_url" class="input-txt" autocomplete="off" value="<?=$featuredEditData['redirection_url']?>">
                                                                                                            
                                                            <div style="display:none;"><div id="redirect_url_error" class="errorMsg e-msg" style="margin-left:189px;">&nbsp;</div></div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <tr>
                                            <td>
                                                <label style="float: left; margin-top: 22px;" class="l-width">Campaign Duration:</label>
                                                <div style="float: left; margin-top: 15px; font-size:12px;margin-right: 10px;">
                                                    From Date: <input style="width:95px;margin-right:12px; font-size:12px;" type="text" name="from_date_main" id="from_date_main" value="" readonly  validate = "validateStr" required = "1" caption="from date"  maxlength="50"/> <img name="from_date_main_img" id="from_date_main_img" src="/public/images/calender.jpg" style="cursor:pointer;position:relative; top:3px;" align="top" onClick="calMain = new CalendarPopup('calendardiv');calMain.select(document.getElementById('from_date_main'),'from_date_main_img','yyyy-MM-dd'); return false;"  />
                                                     <div><div id="from_date_main_error" class="errorMsg e-msg" style="display:none;">&nbsp;</div></div>
                                                     <div><div id="commonDate_error" class="errorMsg e-msg" style="display:none;">&nbsp;</div></div>
                                                </div>
                                                <div style="float: left; margin-right: 15px;font-size:12px;margin-top: 15px;">
                                                    To Date: <input type="text" name="to_date_main" id="to_date_main" value="" readonly style="width:95px;margin-right:12px; font-size:12px;" validate = "validateStr" required = "1" caption="to date"  maxlength="50"/> <img name="to_date_main_img" id="to_date_main_img" src="/public/images/calender.jpg" style="cursor:pointer; position:relative; top:3px;" align="top" onClick="calMain = new CalendarPopup('calendardiv');disableDatesTill(calMain,document.getElementById('from_date_main').value);calMain.select(document.getElementById('to_date_main'),'to_date_main_img','yyyy-MM-dd',document.getElementById('from_date_main').value); return false;"/>
                                                    <div><div id="to_date_main_error" class="errorMsg e-msg" style="display:none;">&nbsp;</div></div>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>                        
                        <input type="hidden" name="examIdFieldVal" id="examIdFieldVal">
                        <input type="hidden" name="destExamIdVal" id="destExamIdVal">
                        <input type="hidden" name="idsToExclude" id="idsToExclude" value="<?=$idsToExclude?>">
        			</form>
                    <div class="post-sec">
                        <a href="javascript:void(0);" id="postFeaturedForm" class="post-btn hideDiv">Activate Now</a>
                    </div>
            </div>
            <div style='text-align: center;padding-top: 10px;margin-top: 7px; margin-bottom: 10px; display:none;' id='loader-id'><img border='0' alt='' id='loadingImage1' class='small-loader' style='border-radius:50%;width: 40px;border: 1px solid rgb(229, 230, 230)' src='//<?php echo IMGURL; ?>/public/mobile5/images/ShikshaMobileLoader.gif'/></div>

		</div>	         
    </div>

    <div style="width:100%;float:left;padding:0 15px;box-sizing:border-box;">
        <p class="manage-heading" style="border-top:1px solid #ccc;padding:20px 15px 0"><?php echo 'Manage Featured '.$heading;?></p> 
        <?php 
        if($contentType == 'institute'){
            $this->load->view('examPages/cms/featuredCollegeContentList'); 
        }else if($contentType == 'featuredExam'){
            $this->load->view('examPages/cms/featuredExamContentList'); 
        } ?>
        <div style='text-align: center;padding-top: 10px;margin-top: 7px; margin-bottom: 10px; display:none;' id='loader-id-1'><img border='0' alt='' id='loadingImage1' class='small-loader' style='border-radius:50%;width: 40px;border: 1px solid rgb(229, 230, 230)' src='//<?php echo IMGURL; ?>/public/mobile5/images/ShikshaMobileLoader.gif'/></div>
    </div>
</div>
<div class="show-layer">
    <div class="alert-div">
      <p id="err_lyr" style="position:relative;"></p>
      <div class="" style="text-align:right;">
      <a id="okbtn" class="pop">Ok</a>
      </div>
    </div>
  </div>

<div class="confirm-layer">
    <div class="alert-div">
      <p id="confirm_lyr" style="position:relative;"></p>
      <div class="" style="text-align:right;">
      <a id="confirmBtn" class="pop">Confirm</a>
      <a id="cancelBtn" class="pop">Cancel</a>
      </div>
    </div>
  </div>
<?php $this->load->view('common/footerNew',array('loadUpgradedJQUERY' => 'YES')); ?>
<?php $this->load->view('examPages/cms/footer'); ?>
<?php $this->load->view('enterprise/autoSuggestorV2ForCMS'); ?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.sumoselect.min"); ?>"></script>
<link href="//<?php echo JSURL; ?>/public/css/<?php echo getCSSWithVersion("sumoselect"); ?>" rel="stylesheet" />
<script>

var featuredEditData = '<?=json_encode($featuredEditData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);?>';
$j(document).ready(function() {
    var type = '<?=$contentType;?>';
    var examId = <?=$examId;?>;
    var displayName = "<?=$featuredData['displayExam']?>";
    if(typeof examId != 'undefined' && examId>0){
        $j('#examSearch').val(displayName);
    }
    $j('#postFeaturedForm').on('click',function(){
        if(validateFields($('featuredContentForm')) != true)
        {   
            return false;
        }else{
            postFeaturedContentForm('<?=$mode?>',type);           
        }
    });

    closeExamSuggestor();
    featuredEditData =JSON.parse(featuredEditData);
    if(featuredEditData != null) {
        updateEditData();
    }
});
           
function handleInstituteAutoSuggestorMouseClick(callBackData){
    if(callBackData && autoSuggestorInstanceArray.autoSuggestorInstanceForInstitutesCMS){
        autoSuggestorInstanceArray.autoSuggestorInstanceForInstitutesCMS.hideSuggestionContainer();  
         instituteSelected(callBackData['words_achieved_id'],callBackData['words_achieved']);
    }
}


function instituteSelected(instId,instTitle){
    document.getElementById('instituteId').value = instId;
    document.getElementById('instituteName').value = instTitle;
    getCourseList(instId);
}

function handleInstituteAutoSuggestorTabPressed(e, autoSuggestorInstance) {
    autoSuggestorInstance.handleInputKeys(e);
    return false;
}

</script>