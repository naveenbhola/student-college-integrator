<title>Add Courses To Groups</title>
<?php 
$headerComponents = array(
        'css'   =>  array('headerCms','raised_all','mainStyle','footer','cal_style'),
        'js'    =>  array('common','enterprise','home','CalendarPopup','discussion','events','listing','blog'),
        'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
        'tabName'   =>  '',
        'taburl' => site_url('enterprise/Enterprise'),
        'metaKeywords'  =>''
        );
$this->load->view('enterprise/headerCMS', $headerComponents);
$this->load->view('enterprise/cmsTabs');
?>

<div class="mar_full_10p">
	<div class="lineSpace_10">&nbsp;</div>
	<div class="fontSize_14p bld">DB Credit Consumption Policy for Groups</div>
    <div class="lineSpace_10">&nbsp;</div>    
    <div>    	
    	<table width="800" border="0" cellpadding="5" cellspacing="0" bordercolor="#e2e2e2">
        <tr>
        	<td width="150"><b>Group Name</b></td>
            <td width="100"><b>Shared View Cr</b></td>
            <td width="100"><b>Email Cr</b></td>
            <td width="100"><b>SMS Cr</b></td>
	    <td width="100"><b>Shared View Limit</b></td>
            <td width="100"><b>Premium View Cr</b></td>
            <td width="100"><b>Premium View Limit</b></td>
	    <td width="100"><b>Email Limit</b></td>
            <td width="100"><b>SMS Limit</b></td>
            <td width="100"><b>View Limit</b></td>
		</tr>
            <?php $groupCount=count($groupList)/8;
                   $groupArray = array();
                   foreach($groupList as $group)
                   {
                        $groupArray[$group['groupId']][$group['actionType']]= $group['deductcredit'];
                   }
                foreach($groupArray as $groupId=>$groupDetails)
                {
            ?>
        <tr>
              <form name="input" action="/enterprise/shikshaDB/updateGroupCreditConsumptionPolicy" method="post">
            <td><input type="hidden" name="groupId" value="<?php echo $groupId?>" />Edit Group <?php echo $groupId?></td>
            <td><input type="text" name="view" value="<?php echo $groupDetails['view']?>" style="width:90px" /></td>
            <td><input type="text" name="email" value="<?php echo $groupDetails['email']?>" style="width:90px"/></td>
            <td><input type="text" name="sms" value="<?php echo $groupDetails['sms']?>" style="width:90px"/></td>        
	    <td><input type="text" name="shared_view_limit" value="<?php echo $groupDetails['shared_view_limit']?>" style="width:90px"/></td>
            <td><input type="text" name="premimum_view_cr" value="<?php echo $groupDetails['premimum_view_cr']?>" style="width:90px"/></td>
            <td><input type="text" name="premimum_view_limit" value="<?php echo $groupDetails['premimum_view_limit']?>" style="width:90px"/></td>
	    <td><input type="text" name="email_limit" value="<?php echo $groupDetails['email_limit']?>" style="width:90px"/></td>
            <td><input type="text" name="sms_limit" value="<?php echo $groupDetails['sms_limit']?>" style="width:90px"/></td>
            <td><input type="text" name="view_limit" value="<?php echo $groupDetails['view_limit']?>" style="width:90px"/></td>
            <td><input type="submit" value="Edit" /></td>
            </form>
        </tr>
            <?php } ?>
        <tr>            
            <form name="input" action="/enterprise/shikshaDB/updateGroupCreditConsumptionPolicy" method="post">
            <td><input type="hidden" name="groupId" value="<?php echo ($groupCount+1)?>" />Add Group </td>
            <td><input type="text" name="view" value="Enter Credits" style="width:90px"/></td>
            <td><input type="text" name="email" value="Enter Credits" style="width:90px"/></td>
            <td><input type="text" name="sms" value="Enter Credits" style="width:90px"/></td>        	
	    <td><input type="text" name="shared_view_limit" value="Enter Credits" style="width:90px"/></td>
            <td><input type="text" name="premimum_view_cr" value="Enter Credits" style="width:90px"/></td>
            <td><input type="text" name="premimum_view_limit" value="Enter Credits" style="width:90px"/></td>
            <td><input type="text" name="email_limit" value="Enter Credits" style="width:90px"/></td>
            <td><input type="text" name="sms_limit" value="Enter Credits" style="width:90px"/></td>
            <td><input type="text" name="view_limit" value="Enter Credits" style="width:90px"/></td>
            <td><input type="submit" value="Add" /></td>
            </form>
        </tr>        
        </table>
    </div>
</div>

