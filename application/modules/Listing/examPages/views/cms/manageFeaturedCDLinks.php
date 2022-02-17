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
    border: 1px solid #d6d6d6;
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
    right:334px;
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

.uni-table.ext__table td{padding:0px;}
/*.SumoSelect{border: 1px solid #e6e5e5}
*/#otherFields{margin-top: -10px}
#otherFields .ex-tble tr{display: table-row;}
#otherFields .ex-tble tr td{padding: 0px 0px 20px}
#groupDivDest .SumoSelect{height: 36px;width: 52%;}
#groupDivDest .CaptionCont.SlectBox, .#groupDivDest .CaptionCont > span{}
#groupDivDest .SumoSelect > .CaptionCont{height: 35px !important;line-height: 35px !important;width:93%;}
#groupDivDest .SumoSelect > .optWrapper.open{top: 37px !important}
.custom-textBox{width:51%;height:224px;border: 1px solid #ddd;font-size: 14px;}
</style>

<?php 
$customElementCount = 1;
?>
<div class="abroad-cms-wrapper" style="margin: 0px;min-height:380px;">
	<div class="abroad-cms-content">
        <div class="abroad-cms-rt-box">
        <?php $this->load->view('/examPages/cms/manageTabs',array('tab'=>$activePage));?>
            <div class="cms-form-wrapper clear-width addMainExamContent" style="margin-bottom:5px;border-bottom:none;">
            	 <form id ="featuredCDLinkForm" name="featuredCDLinkForm" action="/examPages/ExamMainCMS/postFeaturedCDLinkData" method="post" enctype="multipart/form-data">
                        <div style="position:relative;">
                            <table class="uni-table" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td>
                                        <div>
                                            <label class="l-width">Stream:</label>
                                                <select  class="groupList" name="streamField" id="streamField" onchange="getExamListStreamBased(this);" validate="validateSelect" required ="true" caption="stream">
                                                    <option value="">Select a stream</option>
                                                    <?php
                                                    foreach($streams as $key=>$val){?>
                                                        <option value=<?=$val['id']?> ><?php echo $val['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <div><div id="streamField_error" class="errorMsg e-msg" style="display:none;margin-left:189px;">&nbsp;</div></div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div id="examDiv" class="hideDiv" style="position:relative;">
        	            	<table class="uni-table" cellspacing="0" cellpadding="0">
        	            		<tr>
        	            			<td>
                                        <div>
                                            <label class="l-width">Originating Exam:</label>
                                               <select id="examSelection" class="examSelection" placeholder="Select Exam" onchange="getGroupList(this)" validate="validateSelect" required ="true" caption="exam">
                                                           
                                                </select> 
                                                <div><div id="examSelection_error" class="errorMsg e-msg" style="display:none;margin-left:189px;">&nbsp;</div></div>
                                        </div>
        	            			</td>
        	            		</tr>
        	            	</table>
                        </div>
                        <div id="otherFields" class="hideDiv">
                                <div style="position:relative;">
                                    <table class="uni-table ex-tble" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td>                                        
                                                <div id="groupDiv">
                                                <label class="l-width">Originating Exam Course Group(s):</label>
                                                <select id="groupOption" class="groupList" placeholder="Select Course Group" multiple="multiple">
                                                   
                                                </select> 
                                                <div><div id="groupOption_error" class="errorMsg e-msg" style="display:none;">&nbsp;</div></div>
                                                </div>
                                            </td>
                                        </tr>                               
                                            <tr id="no_of_links">
                                                <td>
                                                    <div>
                                                        <label class="l-width">No. of Links Required:</label>
                                                        <input type="radio" name="links" value="1" checked onchange="addMoreCDLinks(this);"> 1
                                                        <input type="radio" name="links" value="2" onchange="addMoreCDLinks(this);"> 2
                                                    </div>
                                                </td>
                                            </tr>
                                             <tr>
                                                <td>
                                                    <div>
                                                        <label class="l-width">Campaign Name 1:</label>
                                                            <input type="text" name="campaign[]" id="campaign_1" class="input-txt" minlength="1" maxlength="50" required="true" caption="campaign name" validate="validateStr" autocomplete="off" value="<?=$featuredEditData['campaign_name']?>">
                                                                                                            
                                                            <div><div id="campaign_1_error" class="errorMsg e-msg" style="display:none;margin-left:189px;">&nbsp;</div></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <label class="l-width">Heading of Link 1:</label>
                                                            <input type="text" name="heading[]" id="heading_1" class="input-txt" minlength="1" maxlength="50" required="true" caption="heading" validate="validateStr" autocomplete="off" value="<?=$featuredEditData['heading']?>">
                                                                                                            
                                                            <div><div id="heading_1_error" class="errorMsg e-msg" style="display:none;margin-left:189px;">&nbsp;</div></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <label class="l-width">Body of Link 1:</label>
                                                            <textarea name="body[]" id="body_1" class="custom-textBox" minlength="1" maxlength="200" required="true" caption="body" validate="validateStr" autocomplete="off"><?=base64_decode($featuredEditData['body'])?></textarea>
                                                                                                            
                                                            <div><div id="body_1_error" class="errorMsg e-msg" style="display:none;margin-left:189px;">&nbsp;</div></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <label class="l-width">CTA Text of Link 1:</label>
                                                            <input type="text" name="CTA_text[]" id="CTA_text_1" class="input-txt" minlength="1" maxlength="20" required="true" caption="CTA text" validate="validateStr" autocomplete="off" value="<?=$featuredEditData['CTA_text']?>">
                                                                                                            
                                                            <div><div id="CTA_text_1_error" class="errorMsg e-msg" style="display:none;margin-left:189px;">&nbsp;</div></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div>
                                                        <label class="l-width">Redirection URL of Link 1:</label>
                                                            <input type="text" name="redirect_url[]" id="redirect_url_1" class="input-txt" minlength="1" maxlength="200" required="true" caption="redirection url" validate="validateStr" autocomplete="off" value="<?=$featuredEditData['redirection_url']?>">
                                                                                                            
                                                            <div><div id="redirect_url_1_error" class="errorMsg e-msg" style="display:none;margin-left:189px;">&nbsp;</div></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label style="float: left; margin-top: 22px;" class="l-width">Campaign Duration:</label>
                                                    <div style="float: left; margin-top: 15px; font-size:12px;margin-right: 10px;">
                                                        From Date: <input style="width:95px;margin-right:12px; font-size:12px;" type="text" name="from_date_main[]" id="from_date_main" value="" readonly  validate = "validateStr" required = "1" caption="from date"  maxlength="50"/> <img name="from_date_main_img" id="from_date_main_img" src="/public/images/calender.jpg" style="cursor:pointer;position:relative; top:6px;" align="top" onClick="calMain = new CalendarPopup('calendardiv');calMain.select(document.getElementById('from_date_main'),'from_date_main_img','yyyy-MM-dd'); return false;"  />
                                                         <div><div id="from_date_main_error" class="errorMsg e-msg" style="display:none;">&nbsp;</div></div>
                                                         <div><div id="commonDate_error" class="errorMsg e-msg" style="display:none;">&nbsp;</div></div>
                                                    </div>
                                                    <div style="float: left; margin-right: 15px;font-size:12px;margin-top: 15px;">
                                                        To Date: <input type="text" name="to_date_main[]" id="to_date_main" value="" readonly style="width:95px;margin-right:12px; font-size:12px;" validate = "validateStr" required = "1" caption="to date"  maxlength="50"/> <img name="to_date_main_img" id="to_date_main_img" src="/public/images/calender.jpg" style="cursor:pointer; position:relative; top:6px;" align="top" onClick="calMain = new CalendarPopup('calendardiv');disableDatesTill(calMain,document.getElementById('from_date_main').value);calMain.select(document.getElementById('to_date_main'),'to_date_main_img','yyyy-MM-dd',document.getElementById('from_date_main').value); return false;"/>
                                                        <div><div id="to_date_main_error" class="errorMsg e-msg" style="display:none;">&nbsp;</div></div>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr class="hide_tr">
                                                <td>
                                                    <div>
                                                        <label class="l-width">Campaign Name 2:</label>
                                                            <input type="text" name="campaign[]" id="campaign_2" class="input-txt" minlength="1" maxlength="50" caption="campaign name" validate="validateStr" autocomplete="off">
                                                                                                            
                                                            <div><div id="campaign_2_error" class="errorMsg e-msg" style="display:none;margin-left:189px;">&nbsp;</div></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="hide_tr">
                                                <td>
                                                    <div>
                                                        <label class="l-width">Heading of Link 2:</label>
                                                            <input type="text" name="heading[]" id="heading_2" class="input-txt" minlength="1" maxlength="50" caption="heading" validate="validateStr" autocomplete="off">
                                                                                                            
                                                            <div><div id="heading_2_error" class="errorMsg e-msg" style="display:none;margin-left:189px;">&nbsp;</div></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="hide_tr">
                                                <td>
                                                    <div>
                                                        <label class="l-width">Body of Link 2:</label>
                                                            <textarea name="body[]" id="body_2" class="custom-textBox" minlength="1" maxlength="200" caption="body" validate="validateStr" autocomplete="off"></textarea>
                                                                                                            
                                                            <div><div id="body_2_error" class="errorMsg e-msg" style="display:none;margin-left:189px;">&nbsp;</div></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="hide_tr">
                                                <td>
                                                    <div>
                                                        <label class="l-width">CTA Text of Link 2:</label>
                                                            <input type="text" name="CTA_text[]" id="CTA_text_2" class="input-txt" minlength="1" maxlength="20"caption="CTA text" validate="validateStr" autocomplete="off">
                                                                                                            
                                                            <div><div id="CTA_text_2_error" class="errorMsg e-msg" style="display:none;margin-left:189px;">&nbsp;</div></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="hide_tr">
                                                <td>
                                                    <div>
                                                        <label class="l-width">Redirection URL of Link 2:</label>
                                                            <input type="text" name="redirect_url[]" id="redirect_url_2" class="input-txt" autocomplete="off">
                                                                                                            
                                                            <div><div id="redirect_url_2_error" class="errorMsg e-msg" style="display:none;margin-left:189px;">&nbsp;</div></div>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="hide_tr">
                                                <td>
                                                    <label style="float: left; margin-top: 22px;" class="l-width">Campaign Duration:</label>
                                                    <div style="float: left; margin-top: 15px; font-size:12px;margin-right: 10px;">
                                                        From Date: <input style="width:95px;margin-right:12px; font-size:12px;" type="text" name="from_date_main[]" id="from_date_main_2" value="" readonly  validate = "validateStr" caption="from date"  maxlength="50"/> <img name="from_date_main_2_img" id="from_date_main_2_img" src="/public/images/calender.jpg" style="cursor:pointer;position:relative; top:6px;" align="top" onClick="calMain = new CalendarPopup('calendardiv');calMain.select(document.getElementById('from_date_main_2'),'from_date_main_2_img','yyyy-MM-dd'); return false;"  />
                                                         <div><div id="from_date_main_2_error" class="errorMsg e-msg" style="display:none;">&nbsp;</div></div>
                                                         <div><div id="commonDate2_error" class="errorMsg e-msg" style="display:none;">&nbsp;</div></div>
                                                    </div>
                                                    <div style="float: left; margin-right: 15px;font-size:12px;margin-top: 15px;">
                                                        To Date: <input type="text" name="to_date_main[]" id="to_date_main_2" value="" readonly style="width:95px;margin-right:12px; font-size:12px;" validate = "validateStr" caption="to date"  maxlength="50"/> <img name="to_date_main_2_img" id="to_date_main_2_img" src="/public/images/calender.jpg" style="cursor:pointer; position:relative; top:6px;" align="top" onClick="calMain = new CalendarPopup('calendardiv');disableDatesTill(calMain,document.getElementById('to_date_main_2').value);calMain.select(document.getElementById('to_date_main_2'),'to_date_main_2_img','yyyy-MM-dd',document.getElementById('to_date_main_2').value); return false;"/>
                                                        <div><div id="to_date_main_2_error" class="errorMsg e-msg" style="display:none;">&nbsp;</div></div>
                                                    </div>
                                                </td>
                                            </tr>
                                    </table>
                                </div>                       
                        </div>                   
                        <input type="hidden" name="examIdFieldVal" id="examIdFieldVal">
                        <input type="hidden" name="groupIdFieldVal" id="groupIdFieldVal">
                        <input type="hidden" name="streamIdFieldVal" id="streamIdFieldVal">
                        <input type="hidden" name="mode" id="mode" value="<?=$mode;?>">
                        <input type="hidden" name="editId" id="editId" value="<?=$edit_link_id;?>">
        			</form>
                    <div class="post-sec">
                        <a href="javascript:void(0);" id="postFeaturedForm" class="post-btn hideDiv">Activate Now</a>
                    </div>
            </div>
            <div style='text-align: center;padding-top: 10px;margin-top: 7px; margin-bottom: 10px; display:none;' id='loader-id'><img border='0' alt='' class='small-loader' style='border-radius:50%;width: 40px;border: 1px solid rgb(229, 230, 230)' src='//<?php echo IMGURL; ?>/public/mobile5/images/ShikshaMobileLoader.gif'/></div>

		</div>	         
    </div>

    <div style="width:100%;float:left;padding:0 15px;box-sizing:border-box;">
        <p class="manage-heading" style="border-top:1px solid #ccc;padding:20px 15px 0"><?php echo 'Manage Featured '.$heading;?></p> 
        <?php 
            $this->load->view('examPages/cms/featuredCDLinksContentTable'); 
        ?>
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
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script>

<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.sumoselect.min"); ?>"></script>
<link href="//<?php echo JSURL; ?>/public/css/<?php echo getCSSWithVersion("sumoselect"); ?>" rel="stylesheet" />
<script>

var featuredEditData = '<?=json_encode($featuredEditData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);?>';

$j(document).ready(function() {
    var displayName = "<?=$featuredData['displayExam']?>";
    if(typeof examId != 'undefined' && examId>0){
        $j('#examSearch').val(displayName);
    }
    $j('#postFeaturedForm').on('click',function(){
        if(validateFields($('featuredCDLinkForm')) != true)
        {   
            return false;
        }else{
            postFeaturedCDLinkForm('<?=$mode?>');     
        }
    });

    closeExamSuggestor();
    $j('tr.hide_tr').hide();

    //edit link
    featuredEditData =JSON.parse(featuredEditData);
    if(featuredEditData != null) {
        updateEditFeaturedLinkData();
    }
    
    //Pagination
    <?php $total_pages = ceil($totalLinks/$item_per_page);?>
        var track_click = 1; 
        var total_pages = <?php echo $total_pages; ?>;
        var examId = <?php echo $examId; ?>;
        var postData = {};
        if(typeof examId != 'undefined' && examId>0){
            $j('#examSearch').val('<?php echo $displayExam;?>');
        }
	var eventEndDateOrder = '';
        $j("#loadMoreBtn").click(function (e) { 
            if(typeof examId != 'undefined' && examId>0){
                postData = {'examId':examId,'page':track_click,'ajaxCall':true,'eventEndDateOrder':eventEndDateOrder}
            }else{
                postData = {'page':track_click,'ajaxCall':true,'eventEndDateOrder':eventEndDateOrder}
            }
            $j(this).hide();
            $j('#loader-id').show(); 
            if(track_click <= total_pages) 
            {
             $j.ajax({
                type: 'POST',
                url : '/examPages/ExamMainCMS/getFeaturedCDLinksList',
                data : postData,
                success : function(response) {
                    response = JSON.parse(response);
                    if(response != ''){
                        track_click++;
                        $j('#loader-id').hide();
                        $j("#loadMoreBtn").show(); 
                        $j('#featuredList').append(response.html);                                         
                        if (total_pages == track_click) {
                            $j("#loadMoreBtn").hide();
                        }
                    }
                }
            });     
                
             }
        });
	
        var eventEndDateOrder = 'asc';
        $j("#cdEventEndDate").click(function (e) {
            if(eventEndDateOrder=='asc'){
                eventEndDateOrder = 'desc';
            }else{
                eventEndDateOrder = 'asc';
            }
            if(typeof examId != 'undefined' && examId>0){
                postData = {'examId':examId,'ajaxCall':true,'eventEndDateOrder':eventEndDateOrder}
            }else{
                postData = {'ajaxCall':true,'eventEndDateOrder':eventEndDateOrder}
            }
            $j('#loader-id').show(); 
            $j.ajax({
                type: 'POST',
                url : '/examPages/ExamMainCMS/getFeaturedCDLinksList',
                data : postData,
                success : function(response) {
                    response = JSON.parse(response);
                    $j('.updateRow').next('tr').addBack().remove();
                    if(response != ''){
                        $j('#loader-id').hide();
                        var eventOrderText = "("+eventEndDateOrder.toUpperCase()+")";
                        $j('#eventOrder').html(eventOrderText).show();
                        $j('#featuredList').append(response.html);                                         
                    }
                }
            });     
        });

});

</script>
