<?php
    $headerComponents = array(
        'css'      =>        array('headerCms','raised_all','mainStyle','footer','cal_style'),
        'js'         =>            array('user','tooltip','common','newcommon','listing','prototype','CalendarPopup'),
        'jsFooter'         =>      array('prototype','scriptaculous','utils'),
        'title'      =>        'SUMS - Edit/Update Consumed Products for Subscription Id: '.$subscriptionId.'',
        'tabName'          =>        'Register',
        'taburl' =>  SITE_URL_HTTPS.'enterprise/Enterprise/register',
        'metaKeywords'  =>'Some Meta Keywords',
        'product' => '',
        'search'=>false,
        'displayname'=> (isset($sumsUserInfo['validity'][0]['displayname'])?$sumsUserInfo['validity'][0]['displayname']:""),
        'callShiksha'=>1
    );
    $this->load->view('enterprise/headerCMS', $headerComponents);
    $this->load->view('enterprise/cmsTabs',$sumsUserInfo);
    $this->load->view('common/overlay');
    $this->load->view('common/calendardiv');
?>
<script>
    var cal = new CalendarPopup("calendardiv");
    cal.offsetX = 20;
    cal.offsetY = 0;
</script>
<div id="dataLoaderPanel" style="position:absolute;display:none">
    <img src="/public/images/loader.gif"/>
</div>

<div class="mar_full_10p">
        <div style="width:223px; float:left">
            <?php 
		$leftPanelViewValue = 'leftPanelFor'.$prodId;
		$this->load->view('sums/'.$leftPanelViewValue); 
	    ?>
        </div>

        <div  style="margin-left:233px">
        <div class="mar_top_6p">
            The following Consumptions belong to Subscription Id: <b><?php echo $subscriptionId; ?></b>
        </div>
        <div class="lineSpace_10">&nbsp;</div>
        <form method="POST" id="frmSelectTransact" action="">
            <div id="populateTransacts">
           <img src="/public/images/space.gif" width="115" height="100" />
           </div>
        </form>
        </div>
</div>

</body>
</html>

<script>
    /*
function validateFormSumsSubs(){
    var checkBoxSelChk = validateCheckBox();
    if(checkBoxSelChk){
        var con = confirm("This action will disable all of the above selected Subscriptions.Are you sure to continue?");
        if(con){
        $('frmSelectTransact').submit();
        }
    }
}

function validateCheckBox()
{
    var checkedFlag = false;
    for(var i=1;i<=document.getElementById('totalUserCount').value;i++)
    {
        if(document.getElementById('userNo_'+i+'').checked == true){
            document.getElementById('checkbox_unselect_error').innerHTML = "";
            document.getElementById('checkbox_unselect_error').style.display = 'none';
            checkedFlag = true;
        }else{
            continue;
        }
    }
    if(!checkedFlag){
        document.getElementById('checkbox_unselect_error').innerHTML = "Please select a Subscription to continue !!";
        document.getElementById('checkbox_unselect_error').style.display = 'inline';
        return false;
    }else{
        return true;
    }
}
*/

function validateSubDates(id){
    var subStartDate = document.getElementById('subs_start_date'+id).value;
    var startStr = subStartDate.split('-');
    var startYear = startStr[0];
    var startMonth = startStr[1]%13;
    var startDate = startStr[2]%32;

    var startDateUTC = Date.UTC(startYear,startMonth,startDate);

    var subEndDate = document.getElementById('subs_end_date'+id).value;
    var endStr = subEndDate.split('-');
    var endYear = endStr[0];
    var endMonth = endStr[1]%13;
    var endDate = endStr[2]%32;

    var endDateUTC = Date.UTC(endYear,endMonth,endDate);
    if(subStartDate != '' && subEndDate != ''){
        if((startDateUTC-86400) > endDateUTC){
            document.getElementById('dateSanityCheck').innerHTML = "End-date can only be 1 day lesser than the start-date(to disable)!!";
            document.getElementById('dateSanityCheck').style.display = 'inline';
            return false;
        }else{
            document.getElementById('dateSanityCheck').innerHTML = "";
            document.getElementById('dateSanityCheck').style.display = 'none';
            return true;
        }
    }else{
        document.getElementById('dateSanityCheck').innerHTML = "Please fill at least one of the Start and End dates";
        document.getElementById('dateSanityCheck').style.display = 'inline';
        return false;
    }
}

function fetchLatestConsumedForSubs(){
        var subsId = '<?php echo $subscriptionId; ?>';
    var data = "SubscriptionId="+subsId;
    new Ajax.Updater('populateTransacts','/sums/Subscription/fetchConsumedIdsForSubs/',{onBeforeAjax:beforeAjax(),parameters:(data),onComplete:function(){
            hideDataLoader($('populateTransacts'));
            }}); return false;
}

function beforeAjax(){
    $('populateTransacts').innerHTML = "&nbsp;"; 
    showDataLoader($('populateTransacts'));
}
/*
function selectAllKeyIds(){
    for(var i=1; i <= $('totalUserCount').value; i++) {
        var keyId = i;
        document.getElementById('userNo_'+keyId+'').checked = document.getElementById('selectAll').checked;
    }
}

function selectAllCheck(){
    if($('selectAll').checked == true){
        $('selectAll').checked = false;
    }
}

function showHideCancelComment(type){
    if(type=='show'){
        $('cancelDiv').style.display = 'inline';
    }else if(type='hide'){
        $('CancelComments').value = '';
        $('cancelDiv').style.display = 'none';
        $('checkbox_unselect_error').innerHTML='';	
    }
}
*/
function changeDates(id){
        var dateSanity = validateSubDates(id);
        if(dateSanity){
                var con = confirm("This action will change this Consumption's Dates.Are you sure to continue?");
                if(con){
                        var consumedId = $('consumed_id'+id).value;
                        var consumedIdType = $('consumed_type'+id).value;
                        var startDate = $('subs_start_date'+id).value;
                        var endDate = $('subs_end_date'+id).value;
                        var statu = $('status_'+id).value; 
                        var subscrId = $('subs_id'+id).value; 
                        var data = "ConsumedId="+consumedId+"&SubscriptionId="+subscrId+"&ConsumedIdType="+consumedIdType+"&startDate="+startDate+"&endDate="+endDate+"&status="+statu;
                        new Ajax.Request('/sums/Subscription/changeConsumedIdDates/',{method:'post',onBeforeAjax:beforeAjax(),parameters:(data),onSuccess:function(xmlHttp){
                                    hideDataLoader($('populateTransacts'));
                                    var ajaxResp = eval("eval("+xmlHttp.responseText+")");
                                    if(ajaxResp.result=="SUCCESS"){
                                            alert("Date(s) changed successfully!!");
                                        }else{
                                            alert("Please change at least one of Start/End Date(s)!!");
                                    }
                                    fetchLatestConsumedForSubs();
                        }});
                }
        }
    }

    /*
function changeStatus(id){
        var con = confirm("This action will change this Subscription's Status.Are you sure to continue?");
        if(con){
            var subsId = $('userNo_'+id).value;
            var statu = $('status_'+id).value; 
            var data = "SubscriptionId="+subsId+"&status="+statu;
            new Ajax.Request('/sums/Subscription/changeSubsStatus/',{method:'post',onBeforeAjax:beforeAjax(),parameters:(data),onSuccess:function(xmlHttp){
                    hideDataLoader($('populateTransacts'));
                    var ajaxResp = eval("eval("+xmlHttp.responseText+")");
                    if(ajaxResp.result=="SUCCESS"){
                       alert("Status changed successfully!!");
                    }else{
                    alert("Subscription can only be Activated/De-activated!!");
                    }
                    fetchLatestConsumedForSubs();
                    }});
        }
    }
    */
fetchLatestConsumedForSubs();

</script>
