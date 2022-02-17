<?php
$headerComponents = array(
        'css'   =>  array('headerCms','raised_all','mainStyle','footer','modal-message','online-styles','common'),
        'js'    =>  array('common','enterprise','home','CalendarPopup','discussion','events','listing','blog','ajax-api','tooltip','onlineFormEnterprise','ana_common'),
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
?>

<?php $data = $onlineFormEnterpriseInfo[instituteInfo][0][0];?>
<div class="wrapperFxd">
<div id="appsFormWrapper">
<div id="departmentId" style="display:none;"><?php echo $_REQUEST['departmentId'];?></div>
<div id="courseId" style="display:none;"><?php echo $_REQUEST['courseId'];?></div>
<div id="totalNumberOfForms" style="display:none;"><?php echo $totalForm;?></div>
<div id="mainOnlineFormEnterpriseDiv">
	<!--Starts: breadcrumb-->
    <div id="breadcrumb">
    	<ul style="display: flex;align-items: center;">
        	<li class="last"><a href="javascript:void(0);"><?php echo $data['institute_name'];?> Dashboard</a></li>

            <li  class="last">
                    <select id='oaf_inst' style="background-color: #fff;padding: 5px 10px;border-radius: 14px;outline: none;cursor: pointer;font-weight: 600;">
                <?php 
                    foreach ($all_oaf_institute as $institute_id =>$institute_name) {?>
                      <option <?php if($institute_id== $oaf_institute_id){echo "selected";} ?> value="<?php echo $institute_id;?>"><?php echo $institute_name;?></option>
                        
                    <?php }
                ?>
                    </select>

            </li>

            <button type="button" onclick="changeOAF();" style="background-color: #FC8104;color: #fff;padding: 4px 14px;border-radius: 11px;font-weight: 600;font-size: 14px;">Click Me!</button>

        </ul>
    </div>
    <!--Ends: breadcrumb-->

    <div id="appsFormInnerWrapper">
    	<div id="contentWrapper" style="margin:0">
    	<div class="managementForms">
        	<h2><?php echo $data['institute_name'];?> <strong>Dashboard</strong></h2>
			<div class="clearFix"></div>
        	<table width="100%" border="0" cellpadding="5">
            	<thead>
                	<tr>
                    	<td width="145">Department Name </td>
                        <td width="85">View Form</td>
                        <td width="108">Course Name</td>
                        <td width="145">Total Submissions</td>
                        <td width="75">Last Date</td>
                        <td width="45">Fees</td>
                        <td width="45">New</td>
                        <!--<td width="100">Download</td>-->
                    </tr>
                </thead>
                <?php for($i=0;$i<count($onlineFormEnterpriseInfo['instituteDetails'][0]);$i++){?>
                <?php $info = $onlineFormEnterpriseInfo['instituteDetails'][0];?>
                <tr <?php if($i>0 && ($i+1)%2==0){ ?>class="evenRow"<?php }?>>
                    <td valign="top"><a href="/onlineFormEnterprise/OnlineFormEnterprise/enterpriseUserDashBoard?departmentId=<?php echo $info[$i]['departmentId'];?>&institute_id=<?php echo $info[$i]['instituteId'];?>"><?php echo $info[$i]['departmentName'];?></a></td>
                    <td align="center" valign="top"><a class="viewIcon" title="View" onclick="window.open('/onlineFormEnterprise/OnlineFormEnterprise/enterpriseUserDashBoard?userId=&formId=&cId=<?php echo $info[$i]['courseId'];?>&viewForm=true');" href="javascript:void(0);">&nbsp;</a></td>
                    <td valign="top"><a href="/onlineFormEnterprise/OnlineFormEnterprise/enterpriseUserDashBoard?courseId=<?php echo $info[$i]['courseId'];?>&institute_id=<?php echo $info[$i]['instituteId'];?>"><?php echo $info[$i]['courseName'];?></a></td>
                    <td align="center" valign="top"><a href="/onlineFormEnterprise/OnlineFormEnterprise/enterpriseUserDashBoard?courseId=<?php echo $info[$i]['courseId'];?>&institute_id=<?php echo $info[$i]['instituteId'];?>"><?php echo $info[$i]['unreadCount']+$info[$i]['readCount'];?></a></td>
                    <td valign="top"><?php echo date('d-m-Y',strtotime($info[$i]['last_date']));?></td>
                    <td valign="top">INR.<?php echo $info[$i]['actualFees'];?></td>
                    <td align="center" valign="top"><?php echo $info[$i]['unreadCount'];?></td>
                    <!--<td valign="top" class="greyText"><a href="#">CSV</a> |  <a href="#">XLS</a> |  <a href="#">XML</a></td>-->
                </tr>
                <?php } ?>
               <!--<tr class="evenRow">
                	<td valign="top"><a href="#">Management</a></td>
                    <td valign="top"><a class="viewIcon" title="View">&nbsp;</a></td>
                    <td valign="top"><a href="#">MBA in Finanace</a></td>
                    <td valign="top"><a href="#">20</a></td>
                    <td valign="top">28-11-2011</td>
                    <td valign="top">INR.500</td>
                    <td valign="top">2</td>
                    <td valign="top" class="greyText"><a href="#">CSV</a> |  <a href="#">XLS</a> |  <a href="#">XML</a></td>
                </tr>
                <tr>
                	<td valign="top"><a href="#">Management</a></td>
                    <td valign="top"><a class="viewIcon" title="View">&nbsp;</a></td>
                    <td valign="top"><a href="#">MBA in Finanace</a></td>
                    <td valign="top"><a href="#">20</a></td>
                    <td valign="top">28-11-2011</td>
                    <td valign="top">INR.500</td>
                    <td valign="top">2</td>
                    <td valign="top" class="greyText"><a href="#">CSV</a> |  <a href="#">XLS</a> |  <a href="#">XML</a></td>
                </tr>
                <tr class="evenRow">
                	<td valign="top"><a href="#">Management</a></td>
                    <td valign="top"><a class="viewIcon" title="View">&nbsp;</a></td>
                    <td valign="top"><a href="#">MBA in Finanace</a></td>
                    <td valign="top"><a href="#">20</a></td>
                    <td valign="top">28-11-2011</td>
                    <td valign="top">INR.500</td>
                    <td valign="top">2</td>
                    <td valign="top" class="greyText"><a href="#">CSV</a> |  <a href="#">XLS</a> |  <a href="#">XML</a></td>
                </tr>
                <tr>
                	<td valign="top"><a href="#">Management</a></td>
                    <td valign="top"><a class="viewIcon" title="View">&nbsp;</a></td>
                    <td valign="top"><a href="#">MBA in Finanace</a></td>
                    <td valign="top"><a href="#">20</a></td>
                    <td valign="top">28-11-2011</td>
                    <td valign="top">INR.500</td>
                    <td valign="top">2</td>
                    <td valign="top" class="greyText"><a href="#">CSV</a> |  <a href="#">XLS</a> |  <a href="#">XML</a></td>
                </tr>
                <tr class="evenRow">
                	<td valign="top"><a href="#">Management</a></td>
                    <td valign="top"><a class="viewIcon" title="View">&nbsp;</a></td>
                    <td valign="top"><a href="#">MBA in Finanace</a></td>
                    <td valign="top"><a href="#">20</a></td>
                    <td valign="top">28-11-2011</td>
                    <td valign="top">INR.500</td>
                    <td valign="top">2</td>
                    <td valign="top" class="greyText"><a href="#">CSV</a> |  <a href="#">XLS</a> |  <a href="#">XML</a></td>
                </tr>-->
            </table>
        </div>
		<div class="clearFix"></div>
        </div>
        <div class="clearFix"></div>
    </div>
    <div class="clearFix"></div>
</div>
</div>
</div>
<?php
$this->load->view('enterprise/footer');
?>
