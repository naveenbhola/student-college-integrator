<style>
.input-txt {
    background-position: 10px 12px; 
    background-repeat: no-repeat; 
    width: 51%;
    font-size: 14px; 
    padding: 5px 0px 5px 10px; 
    border: 1px solid #ddd; 
    position: relative;
}

.l-width{
    display: inline-block;
    vertical-align: middle;
    width: 118px;
    font-weight: 600;
}

.e-msg{
    margin-left: 120px;
    padding-top: 5px;
}

.uni-table{padding-left: 210px;}
#otherFields .uni-table{padding: 5px 0;padding-left: 210px;}
#otherFields .uni-table tr td{padding: 0;}
#otherFields .uni-table tr td > div > label{vertical-align: top;}
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
}

#groupDiv .SumoSelect>.CaptionCont {
    height: auto
}


.disabled {pointer-events: none;cursor: default;}
.hideDiv{display:none;}
.post-sec{margin: 10px auto; text-align: center;}
a.post-btn{background: #fff; border: 1px solid #ccc; padding: 5px 36px; color: #333; font-weight: 600; text-decoration: none;}
.updt-sec{width:100%; margin:10px 0 10px; border-top: 1px solid #eee; clear: both; padding-top: 10px; display: inline-block;}
.updt-sec a{font-size: 16px; margin: 0 8px; display: inline-block;}
.updt-sec a.active{font-weight: 600;color:#333;}
.formInput{margin: 10px 0}
.formInput textarea{width: 85%;}
.formInput .notilink{width: 85%;}
.formInput  a{ bottom: 19px;left: 8px; margin: 0; position: relative;}

#examSearchOptions {
    list-style-type: none;
    padding: 0;
    margin: 0;
    position: absolute;
    overflow-y: auto;
    width: 377px;
    z-index: 1000;
    right: 209px;
    height: auto;
    max-height: 203px;
}


#examSearchOptions li a {
    border: 1px solid #ddd; 
    margin-top: -1px; 
    background-color: #f6f6f6; 
    padding: 5px;
    text-decoration: none;
    font-size: 13px; 
    color: black;
    display: block; 
}

#examSearchOptions li a:hover{
    background-color: #eee; 
}

</style>

<?php 
$customElementCount = 1;
if($action== 'add'){
    $style = 'active';
    $style1='';
    $addUrl = 'javascript:void(0);';
    $deleteUrl = "/examPages/ExamMainCMS/manageExamPageAnnouncements/delete";
}else{
    $style = '';
    $style1='active';
    $addUrl = '/examPages/ExamMainCMS/manageExamPageAnnouncements/add';
    $deleteUrl = 'javascript:void(0);';
}
?>
<div class="abroad-cms-wrapper" style="margin: 0px;min-height:380px;">
	<div class="abroad-cms-content">
        <div class="abroad-cms-rt-box">
        <?php $this->load->view('/examPages/cms/manageTabs',array('tab'=>$activePage));?>
            <div class="updt-sec">
                <a href="<?=$addUrl;?>" class="<?=$style;?>">Post New Update</a>
                <a> | </a>
                <a href="<?=$deleteUrl;?>" class="<?=$style1;?>">Delete Update</a>
            </div>

            <?php if($action == 'add'){?>
            <div class="cms-form-wrapper clear-width addMainExamContent" id="" style="margin-bottom:5px;border-bottom:none;">
            	 <form id ="examUpdateForm" name="examUpdateForm">
                        <div style="position:relative;">
        	            	<table class="uni-table" cellspacing="0" cellpadding="0">
        	            		<tr>
        	            			<td>
                                        <div>
                                            <label class="l-width">Exam Name:</label>
                                                 <input type="text" name="examField" id="examField" class="input-txt" onkeyup="searchExam('examSearchOptions','examField')" placeholder="Search for exams.." minlength="1" maxlength="50" required="true" caption="exam" validate="validateStr" autocomplete="off">
                                                                                                 
                                                <ul id="examSearchOptions" style="display:none;">
                                                    <?php foreach($examList as $id=>$name){?>
                                                    	<li><a onclick="getGroupList(this,'<?=$id?>')"><?=$name?></a></li>
                                                    <?php } ?>
                                                </ul> 
                                                <div><div id="examField_error" class="errorMsg e-msg" style="display:none;">&nbsp;</div></div>
                                        </div>
        	            			</td>
        	            		</tr>
        	            	</table>
                        </div>
                        <div id="otherFields" class="hideDiv">
                        <div>
                            <table class="uni-table" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td>                                        
                                        <div id="groupDiv">
                                        <label class="l-width">Course Group(s):</label>
                                        <select id="groupOption" class="groupList" placeholder="Select Course Group" multiple="multiple">
                                           
                                        </select> 
                                        <div><div id="groupOption_error" class="errorMsg e-msg" style="display:none;">&nbsp;</div></div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                       
                        <div style="border-top:1px solid #ccc;">
                                <div style="padding:23px;">
                                     <p style="font-weight:600;font-size:16px;">Mention Updates:</p>
                                </div>
                                <ul style="margin-left:85px;">
                                    <li class="updateSectionsList">
                                        <div class="custom-update-section">
                                            <div class="l-width">Update:</div>        
                                            <div class="formInput inline">
                                                <textarea class="custom-update-data" name="examUpdate[]" id="examUpdate_<?=$customElementCount?>"  validate="validateStr" minlength="1" maxlength="500" caption="update" required="true"></textarea>
                                                <a href="javascript:void(0);" style= "display:none;" class="remove-link" onclick="removeExamUpdate(this)"><i class="abroad-cms-sprite remove-icon"></i>Remove</a>
                                                <div><div id="examUpdate_<?=$customElementCount?>_error" class="examUpdateerror errorMsg" style="display:none;"></div></div>
                                                
                                            </div>
                                            <div class="l-width">URL:</div>        
                                            <div class="formInput inline">
                                                <input type="text" id="examupdateurl_<?=$customElementCount?>" class="notilink custom-update-link" name="examupdateurl[]" caption="URL" required="false">
                                                <div><div id="examupdateurl_<?=$customElementCount?>_error" class="examupdateurlerror errorMsg" style="display:none;"></div></div>
                                            </div>

                                        </div>
                                    </li>                            
                                </ul>
                                 <div class="clear-width" style="padding:10px; text-align:left">           
                                        <a href="javascript:void(0);" id="addMoreUpdateLink" class="add-more-link last-in-section custom-update-data" onclick="addMoreUpdateSection(this, 1);" style="margin-bottom:0;margin-left:0;margin-left:77px;">[+] Add More</a>
                                </div>
                            </div>
                            <div style="clear: both;border-top:1px solid #ccc;">
                                <div style="margin-left:80px">
                                  <input style="display: inline-block;margin-top:10px;" type="checkbox" name="isMailSent" id="isMailSent" value="true" checked>
                                  <p style="display: inline-block; position: relative; top: -2px;">Also send updates in the mailer to followers</p>
                                </div>
                            </div> 
                        </div>

                        <input type="hidden" name="examIdFieldVal" id="examIdFieldVal">
        			</form>
                    <div class="post-sec">
                        <a href="javascript:void(0);" id="postUpdate" class="post-btn hideDiv">Post</a>
                    </div>
                   
                   <div class="custom-update" style="display:none;">
                        <div class="l-width">Update:</div>        
                            <div class="formInput inline">
                                <textarea class="custom-update-data" validate="validateStr" minlength="1" maxlength="500" caption="update" required='true'></textarea>                                       
                                <a href="javascript:void(0);" class="remove-link" onclick="removeExamUpdate(this)"><i class="abroad-cms-sprite remove-icon"></i>Remove</a>
                                <div><div class="examUpdateerror errorMsg" style="display:none;"></div></div>
                            </div>
                        <div class="l-width">URL:</div>        
                            <div class="formInput inline">
                                <input type="text" id="" class="notilink custom-update-link" caption="URL">
                                <div><div class="examupdateurlerror errorMsg" style="display:none;"></div></div>
                            </div>
                    </div>
            </div>
            <?php }else{ 
                    $this->load->view('examPages/cms/deleteUpdateListView');
                } ?>
            <div style='text-align: center;padding-top: 10px;margin-top: 7px; margin-bottom: 10px; display:none;' id='loader-id'><img border='0' alt='' id='loadingImage1' class='small-loader' style='border-radius:50%;width: 40px;border: 1px solid rgb(229, 230, 230)' src='//<?php echo IMGURL; ?>/public/mobile5/images/ShikshaMobileLoader.gif'/></div>

		</div>	         
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

<?php $this->load->view('common/footerNew',array('loadUpgradedJQUERY' => 'YES')); ?>
<?php $this->load->view('examPages/cms/footer'); ?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.sumoselect.min"); ?>"></script>
<link href="//<?php echo JSURL; ?>/public/css/<?php echo getCSSWithVersion("sumoselect"); ?>" rel="stylesheet" />
<script>
$j(document).ready(function() {    
    $j('#postUpdate').on('click',function(){
        if(validateFields($('examUpdateForm')) != true)
        {   
            return false;
        }else{
            postExamUpdateForm();
        }
    });
    closeExamSuggestor();

    <?php if($action == 'delete'){?>
        <?php $total_pages = ceil($totalUpdates/$item_per_page);?>
        var track_click = 1; //track user click on "load more" button, righ now it is 0 click
        var total_pages = <?php echo $total_pages; ?>;
        var examId = <?php echo $examId; ?>;
        var postData = {};
        if(typeof examId != 'undefined' && examId>0){
            $j('#examField').val('<?php echo $displayExam;?>');
        }

        $j("#loadMoreBtn").click(function (e) { 
            if(typeof examId != 'undefined' && examId>0){
                postData = {'examId':examId,'page':track_click,'ajaxCall':true}
            }else{
                postData = {'page':track_click,'ajaxCall':true}
            }
            $j(this).hide();
            $j('#loader-id').show(); 
            if(track_click <= total_pages) 
            {  $j.ajax({
                type: 'POST',
                url : '/examPages/ExamMainCMS/getExamUpdatesList',
                data : postData,
                success : function(response) {
                    response = JSON.parse(response);
                    if(response != ''){
                        track_click++;
                        $j('#loader-id').hide();
                        $j("#loadMoreBtn").show(); 
                        $j('#examUpdateList').append(response.html);                                         
                        if (total_pages == track_click) {
                            $j("#loadMoreBtn").hide();
                        }
                    }
                }
            });     
                
             }
        });
    <?php } ?>

});

</script>



