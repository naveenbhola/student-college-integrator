<link href='https://fonts.googleapis.com/css?family=Open+Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>

<div id="mmm-cont" style="margin:0 auto;">

	<div class="tab-details" id="userSetTabDetails">
	
		<div class="cms-wrapper">

		    <form name="UserSetSearch" method="post" action="<?php echo $submitURL;?>" id="UserSetSearch">

		        <div id="usersetSearchBlock">

		        	<input type="hidden" name="criteriaNos[]" class="criteriaNos" value="1" />

		           	<div id="searchCriteriaBlock_<?php echo $criteriaNo;?>">         

					    <div id="searchCriteriaTitleBlock_<?php echo $criteriaNo;?>" class="clone edu-inTitle">Education Interest</div>

					    <input id="targetDBCustom" class="targetDB" type="radio" name="targetDB" value="custom" checked="true" style="display:none" >

					    <div id="searchCriteriaSummaryBlock_<?php echo $criteriaNo;?>" class="clone"></div>

					    <div id="searchCriteriaFieldsBlock_<?php echo $criteriaNo;?>" class="clone">

					        <table class="user-table"> 
					        	
					            <?php $this->load->view('userSearchCriteria/searchWidgets/stream'); ?>

					            <?php $this->load->view('userSearchCriteria/searchWidgets/mode'); ?>

					            <?php $this->load->view('userSearchCriteria/searchWidgets/city'); ?>

					            <?php $this->load->view('userSearchCriteria/searchWidgets/workExperience'); ?>
					            
					        </table>    

					    </div>  

					</div>

		        </div>

		        <div>         

		            <?php $this->load->view('userSearchCriteria/searchWidgets/dateAndSearch'); ?>
		            
		        </div>

		        <input type="hidden" name="usersettype" value="LDBSearch" />

		    </form>

		</div>

	</div>
	<form id="searchFormSub" action="/enterprise/enterpriseSearch/searchResults" method="post" style="display:none">
	</form>

	<form id="csvdownloadform" method="post" action="/enterprise/shikshaDB/ajax_submit_csv_activity_post">
		<input type="hidden" id="csvActivationIdUserActions" name="csvActivationIdUserActions" value=""/>
		<input type="hidden" id="csvldb_course_type_csv_download" name="csvldb_course_type_csv_download" value=""/>
		<input type="hidden" id="csvfilename_download" name="csvfilename_download" value=""/>
		<input type="hidden" id="csvinputData" name="csvinputData" value=""/>
		<input type="hidden" id="csvdisplayData" name="csvdisplayData" value=""/>
		<input type="hidden" id="csvinputDataMR" name="csvinputDataMR" value=""/>
		<input type="hidden" id="currentURL" name="currentURL" value=""/>
		<input type="hidden" id="csvTabFlag" name="csvTabFlag" value=""/>
	</form>
	
	<div id='searchResultDiv'></div>
	<div class="clearFix spacer15"></div>

</div>

<script type="text/javascript">
//inline JS - to be removed

function validateSendEmail(objForm)
{
	var searchCMSBinder = {};
	searchCMSBinder =  new SearchCMSBinder();
    var flag = validateFields(objForm);
    invalidateFlag = invalidateShikshaEmailId($j('#fromEmail').val());

	if(invalidateFlag === true){
		$('fromEmail_error').parentNode.style.display = 'inline';
		$('fromEmail_error').innerHTML = 'Please enter non-Shiksha domain email Id (Email Id ending with "@shiksha.com" not allowed).';
	}

    if(flag !== true) {
        $('sendEmailButton').disabled  = false;
        return false;
    }
    else {
    	if(invalidateFlag === true){
    		return false;
    	}

        $('sendEmailButton').disabled  = true;
        searchCMSBinder.showLoader();
        hideOverlay(false);
        closeMessage();
        return searchCMSBinder.submitEmailSmsRequestForm();
    }
}

function validateSendSMS(objForm)
{
	var searchCMSBinder = {};
	searchCMSBinder =  new SearchCMSBinder();
    var flag = validateFields(objForm);
    if(flag !== true) {
		$('sendSMSButton').disabled  = false;
        return false;
    }
    else {
		$('sendSMSButton').disabled  = true;
		searchCMSBinder.showLoader();
        hideOverlay(false);
        closeMessage();
		return searchCMSBinder.submitEmailSmsRequestForm();
    }

}

</script>

<?php
       $this->load->view('enterprise/searchResultViewContactDetails');
?>

