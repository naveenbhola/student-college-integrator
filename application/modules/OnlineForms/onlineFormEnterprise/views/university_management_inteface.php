<?php

if($ajaxCall=='false'){

$headerComponents = array(

        'css'   =>  array('headerCms','raised_all','mainStyle','footer','cal_style','modal-message','online-styles','common'),

        'js'    =>  array('common','enterprise','home','CalendarPopup','discussion','events','listing','blog','ajax-api','tooltip','onlineFormEnterprise','ana_common','json2'),

        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),

        'tabName'   =>  '',

        'taburl' => site_url('enterprise/Enterprise'),

        'metaKeywords'  =>'',

	'title' => 'Enterprise User Dashboard'

        );

$this->load->view('enterprise/headerCMS', $headerComponents);

?>

<?php

$this->load->view('enterprise/cmsTabs');

}

?>
<?php $data = $onlineFormEnterpriseInfo[instituteInfo][0][0];?>

<?php $totalCount = $onlineFormEnterpriseInfo[totalFormNumber][0];?>

<?php $searchArray = array("email"=>"Email",'appNum'=>'Application Number','displayName'=>'Name of candidate',/*'paymentMethod'=>'Payment Method','paymentStatus'=>'Payment status',*/'cityName'=>'Location of candidate');?>

<input type="hidden" name="instituteId" id="instituteId" value="<?php echo $data['institute_id'];?>">

<div id="UserFormId" style="display:none;">None</div>
<div id="instituteSpecId" style="display:none;">None</div>

<?php if($_REQUEST['departmentId']){ $id =$_REQUEST['departmentId']; $type='department';}else{$id =$_REQUEST['courseId']; $type='course';}?>

<div class="wrapperFxd">

<div id="appsFormWrapper">

<div id="departmentId" style="display:none;"><?php echo $_REQUEST['departmentId'];?></div>

<div id="courseId" style="display:none;"><?php echo $_REQUEST['courseId'];?></div>

<div id="totalNumberOfForms" style="display:none;"><?php echo $totalForm;?></div>
<div id="userDraftDate" style="display:none"></div>
<div id="userDraftNumber" style="display:none"></div>
<div id="userDraftPayeeBank" style="display:none"></div>

<div id="mainOnlineFormEnterpriseDiv">

	<!--Starts: breadcrumb-->

    <div id="breadcrumb">

    	<ul>

        	<li><a href="/onlineFormEnterprise/OnlineFormEnterprise/enterpriseUserDashBoard/<?php echo $data['institute_id']?>"><?php echo $data['institute_name'];?> Dashboard</a></li>
                <li class="last"><?php if($_REQUEST['departmentId']) echo $onlineFormEnterpriseInfo['courseDetails'][0][0]['departmentName'];else echo $onlineFormEnterpriseInfo['instituteDetails'][0][0]['courseTitle'];?>&nbsp;Dashboard</li>

        </ul>

    </div>

        <script>

        var parameterObj = eval(<?php echo $parameterObj; ?>);

        </script>

    <!--Ends: breadcrumb-->



<!--Pagination Related hidden fields Starts-->

	<input type="hidden" autocomplete="off" id="formStartFrom" value="<?php echo $startFrom;?>"/>

	<input type="hidden" autocomplete="off" id="formCountOffset" value="<?php echo $countOffset;?>"/>

	<input type="hidden" autocomplete="off" id="abuseFilter" value=""/>

	<input type="hidden" autocomplete="off" id="methodName" value="insertOnlineForm"/>

	<input type="hidden" autocomplete="off" id="moduleNameSel" value="<?php echo $moduleName;?>"/>

    <input type="hidden" autocomplete="off" id="formDepaermentId" value="<?php echo $_REQUEST['departmentId'];?>"/>

    <input type="hidden" autocomplete="off" id="formCorseId" value="<?php echo $_REQUEST['courseId'];?>"/>

<!--Pagination Related hidden fields Ends  -->



    <div id="appsFormInnerWrapper">

    	<div id="contentWrapper">

    	<div class="managementForms">
        	<h2 class="welcome" style="margin-bottom: 0;"><?php if($_REQUEST['departmentId']) echo $onlineFormEnterpriseInfo['courseDetails'][0][0]['departmentName'];else echo $onlineFormEnterpriseInfo['instituteDetails'][0][0]['courseTitle'];?>&nbsp;<strong>Dashboard</strong></h2>
                <?php if(count($mapping) >= 1){ ?>
				<div class="flRt">
					<button onclick="window.location = '/onlineFormEnterprise/EnterpriseDataGrid/showTab/<?=$onlineFormEnterpriseInfo['courseDetails'][0][0]['courseId'];?>'" class="orange-button" style="text-decoration: none;color: #FFFFFF;padding: 4px 8px;">Manage Form Data</button>
				</div>
				<?php } ?>

		<div class="flRt">&nbsp;</div>
		<div id="dashboard-tab">
		  <ul>
            <?php 
                $url = 'departmentId='.$_GET['departmentId'];
                if(isset($courseIdSet) && $courseIdSet>0){
                    $url = 'courseId='.$courseIdSet;
                }
            ?>
		    <li <?php if($tab == 'notawaitedForms'){ ?> class="active" <?php } ?>><a href="/onlineFormEnterprise/OnlineFormEnterprise/enterpriseUserDashBoard?<?php echo $url;?>&tab=notawaitedForms">Confirmed submission <?php if($tab == 'awaitedForms'){ echo '('.$formTabCount.')';}else {echo '('.$totalCount.')'; } ?></a></li>
		    <li <?php if($tab == 'awaitedForms'){ ?> class="active" <?php } ?>><a href="/onlineFormEnterprise/OnlineFormEnterprise/enterpriseUserDashBoard?<?php echo $url;?>&tab=awaitedForms">Pending draft <?php if($tab == 'notawaitedForms'){ echo '('.$formTabCount.')'; }else {echo '('.$totalCount.')'; } ?></a></li>
		  </ul>
		  </div>
		<div class="spacer10 clearFix"></div>


            <div class="searchByCont">

                <form id="searchEnterpriseDash" action="/onlineFormEnterprise/OnlineFormEnterprise/enterpriseUserDashBoard" method="get" onsubmit="if(checkVal()==true){return true;}else{return false;}">

                    <input type="hidden" name="<?php echo $type;?>Id" value="<?php echo $id;?>"/>

                    <input type="hidden" name="instituteId" value="<?php echo $data['institute_id'];?>"/>

                    <input type="hidden" name="type" value="<?php echo $type;?>"/>

		    <input type="hidden" name="sortBy" id="sortBy" value="<?php echo $sortBy;?>"/>
    	            <input type="hidden" name="tab" id="tab" value="<?php echo $tab;?>"/>
                    <div class="searchFieldsCol">

            			<strong>Search by :</strong><br />
                                <select  id="searchCriteria" name="searchType"><option value=''>Select</option><?php foreach($searchArray as $key=>$value){?><option value="<?php echo $key;?>" <?php if($key==$_REQUEST['searchType']) echo "selected=selected";?>><?php echo $value;?></option> <?php }?></select> &nbsp;&nbsp;<input type="text" class="textboxLarge" id="searchText" name="searchTextValue" value="<?php if(isset($_REQUEST['searchTextValue']) && $_REQUEST['searchTextValue']!='')echo $_REQUEST['searchTextValue'];else echo 'keyword';?>" onblur="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" default="keyword"  style="color: rgb(173, 166, 173);"/>


                    </div>

                    <?php $this->load->view('common/calendardiv'); ?>

                    <div class="searchFieldsSmallCol">

                    	<label>Submission Start Date</label>

                        <span class="calenderBox">

                            <input type="text" value="<?php if(isset($_REQUEST['from_date_main_first']) ) echo $_REQUEST['from_date_main_first']; else echo 'dd/mm/yyyy';?>" class="calenderFields"  name="from_date_main_first" id="from_date_main_first"  readonly  />

                            <a href="#" class="pickDate" title="Calendar" name="from_date_main_img" id="from_date_main_img"  src="/public/images/calender.jpg" style="cursor:pointer" align="top" onClick="calMain = new CalendarPopup('calendardiv');calMain.select(document.getElementById('from_date_main_first'),'from_date_main_img','dd/mm/yyyy'); return false;">&nbsp;</a>

                        </span>

                    </div>

                    <div class="searchFieldsSmallCol2">

                        <label>Submission End Date</label>

							<span class="calenderBox">

                            <input type="text" value="<?php if(isset($_REQUEST['from_date_main_second']) ) echo $_REQUEST['from_date_main_second']; else echo 'dd/mm/yyyy';?>" class="calenderFields"  name="from_date_main_second" id="from_date_main_second"  readonly  />

                            <a href="#" class="pickDate" title="Calendar" name="from_date_main_img" id="from_date_main_second"  src="/public/images/calender.jpg" style="cursor:pointer" align="top" onClick="calMain = new CalendarPopup('calendardiv');calMain.select(document.getElementById('from_date_main_second'),'from_date_main_img','dd/mm/yyyy'); return false;">&nbsp;</a>

                        </span>

                        <div class="enterpriseSearch"><input type="submit" class="orange-button marginL40" value="Search" title="Search" id="19"/></div>

                    </div>

                    <!--Layer Starts here gdpi-->

                



				<!--Layer Ends here-->

                   <!--Layer Starts here-->

                   

				<!--Layer Ends here-->

				</form>

            </div>

            <?php $resultCount = count($onlineFormEnterpriseInfo['instituteDetails'][0]);?>

            <?php if($resultCount>0){?>

            <div class="clearFix"></div>

			<div class="pagingID" id="paginataionPlace1" align="right" style="line-height:23px;width:180px; float:right"></div>

            <?php }?>

           <div class="clearFix spacer10"></div>

           

           <div class="enterpriseDataCont enterpriseDataContTitle">

            	<ul>

                	<li>
                        <div class="entSelectCol">
			<?php if($_REQUEST['tab']!='awaitedForms'){?>
                        	<input type="checkbox" onClick="selectAllRACheckbox(checkboxStatusEnterpriseDashboard);" id="SelectAllForms" value="SelectAllForms"/>
			<?php }else{ ?>
                            <span style="width:20px;">&nbsp;</span>
            <?php } ?>


                        </div>

                        <div class="entAppsNo"><a href="javascript:void(0)" onClick="sortEnterpriseForms('appNum');">Application No.</a><?php if($sortBy=='appNum'){echo "<div><img src='/public/images/arrow_down.png' border=0 /></div>";}else if($sortBy=='appNumD'){echo "<div><img src='/public/images/arrow_up.png' border=0 /></div>";}?></div>

                        <div class="entFromStage"><a href="javascript:void(0)" onClick="sortEnterpriseForms('formstage');">Form Stage</a><?php if($sortBy=='formstage'){echo "<div><img src='/public/images/arrow_down.png' border=0 /></div>";}else if($sortBy=='formstageD'){echo "<div><img src='/public/images/arrow_up.png' border=0 /></div>";}?></div>

                        <div class="entLocation"><a href="javascript:void(0)" onClick="sortEnterpriseForms('location');">Location</a><?php if($sortBy=='location'){echo "<div><img src='/public/images/arrow_down.png' border=0 /></div>";}else if($sortBy=='locationD'){echo "<div><img src='/public/images/arrow_up.png' border=0 /></div>";}?></div>

                        <div class="entGDPI"><a href="javascript:void(0)" onClick="sortEnterpriseForms('gdpi');"><?=$gdPiName?></a><?php if($sortBy=='gdpi'){echo "<div><img src='/public/images/arrow_down.png' border=0 /></div>";}else if($sortBy=='gdpiD'){echo "<div><img src='/public/images/arrow_up.png' border=0 /></div>";}?></div>

                        <div class="entFromStatus"><a href="javascript:void(0)" onClick="sortEnterpriseForms('formstatus');">Form Status</a><?php if($sortBy=='formstatus'){echo "<div><img src='/public/images/arrow_down.png' border=0 /></div>";}else if($sortBy=='formstatusD'){echo "<div><img src='/public/images/arrow_up.png' border=0 /></div>";}?></div>

                        <div class="entPayment"><a href="javascript:void(0)" onClick="sortEnterpriseForms('payment');">Payment</a><?php if($sortBy=='payment'){echo "<div><img src='/public/images/arrow_down.png' border=0 /></div>";}else if($sortBy=='paymentD'){echo "<div><img src='/public/images/arrow_up.png' border=0 /></div>";}?></div>

                        <div class="entAlerts">Alerts</div>

                       <div class="entDownloadDocs">Download documents</div>

                   </li>

                </ul>

                <div class="clearFix"></div>

             </div>

             <div class="clearFix spacer5"></div>

                

             <div style="display:none;" id="requestDocumentContent"><?php echo $onlineFormEnterpriseInfo['instituteDetails'][0][0]['documentsRequired'];?></div>

             <div style="display:none;" id="requestPhotographContent"><?php echo $onlineFormEnterpriseInfo['instituteDetails'][0][0]['imageSpecifications'];?></div>

             

             <?php if($resultCount>0){ ?>

             

		      <div class="enterpriseDataCont" id="mainOnlineFormEnterpriseDivLoader">

			  <?php $this->load->view('onlineFormEnterprise/show_form_list'); ?>

			</div>



			<?php }else{?>

		      <div>

			  <ul>

			    <li>

				<div class="clearFix spacer20"></div>

				<h3 class="searchMsg">No Matching Result Found!!!</h3>

			    </li>

                	<?php }?>

			</ul>

		      </div>

		<?php if($resultCount>0){?>

             <div class="clearFix spacer10"></div>

            <div class="pagingID" id="paginataionPlace2" align="right" style="width:180px;float:right;"></div>

            <?php } ?>

        </div>

        

        <div class="clearFix"></div>

        <?php if($resultCount>0){ ?>
         <div class="buttonBlock buttonWrapper">
	 <?php if($tab == 'awaitedForms'){?>
            <!--<input type="button" value="Draft received" class="orange-button" id="19"/> &nbsp;
-->
	<?php }else{ ?>
	    <!--<input type="button" value="Draft received" class="orange-button" id="19"/> &nbsp;
-->
            <input type="button" value="Request photographs" class="orange-button" id="2"/> &nbsp;
            <input type="button" value="Request Documents" class="orange-button" id="3" /> &nbsp;
            <div class="spacer10 clearAll" ></div>
            <input type="button" value="Confirm Acceptance" class="orange-button" id="4"/> &nbsp;
            <input type="button" value="Update <?=$gdPiName?>" class="orange-button" id="5"/> &nbsp;
            <input type="button" value="Reject Application" class="orange-button" id="6"/> &nbsp;
            <input type="button" value="Shortlist Application" class="orange-button" id="18"/>
	<?php } ?>
		</div>
        <?php }?>

        <div class="clearFix"></div>

        </div>

        <div class="clearFix"></div>

    </div>

	<div class="clearFix"></div>

</div>

<div class="clearFix"></div>

</div>

</div>

<?php

if($ajaxCall=='false'){

$this->load->view('enterprise/footer');

}

?>

<?php if($totalCount>0){

		echo "<script>

			setStartOffset(0,'formStartFrom','formCountOffset');

			doPagination(".$totalCount.",'formStartFrom','formCountOffset','paginataionPlace1','paginataionPlace2','methodName',3);

			</script>";

}
?>
<script>
function sortEnterpriseForms(sortId){
	if($('sortBy').value==sortId){
		$('sortBy').value = sortId+'D';
	}
	else{
		$('sortBy').value = sortId;
	}
	$('from_date_main_first').value = '<?php if(isset($_REQUEST['from_date_main_first']) ) echo $_REQUEST['from_date_main_first']; else echo 'dd/mm/yyyy';?>';
	$('from_date_main_second').value = '<?php if(isset($_REQUEST['from_date_main_second']) ) echo $_REQUEST['from_date_main_second']; else echo 'dd/mm/yyyy';?>';
	$('searchText').value = "<?php if(isset($_REQUEST['searchTextValue']) && $_REQUEST['searchTextValue']!='')echo $_REQUEST['searchTextValue'];else echo 'keyword';?>";	
	$('searchCriteria').value = '<?php echo $_REQUEST['searchType']; ?>';
	$('searchEnterpriseDash').submit();
}
</script>
