<?php
   $headerComponents = array(
      'css'			=> array('headerCms','raised_all','mainStyle','cal_style','footer'),
      'js'			=> array('user','tooltip','common','newcommon','listing','prototype','scriptaculous','utils','imageUpload','CalendarPopup'),
      'title'			=> "Listings Entities for Priority Leads",
      'product' 		=> '',
                          					'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
   );
   $this->load->view('enterprise/headerCMS', $headerComponents);
   $this->load->view('enterprise/cmsTabs',$sumsUserInfo);
                   $this->load->view('common/calendardiv');
?>
<div style="width:100%">
	<div style="margin:0 10px">
    	<div style="width:100%">
            <div class="raised_lgraynoBG">
                <b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
<form  action="/enterprise/Enterprise/addEntityForPriorityLeads" method="post"  name = "TopForm" id = "TopForm">
                    <div class="boxcontent_lgraynoBG">
                    	<div style="width:100%">
                        	<div style="padding:10px">
                            	<div class="orangeColor fontSize_14p bld" style="padding-bottom:5px">Set lead subscription for clients</div>
                                <div class="grayLine_1">&nbsp;</div>
                                <div class="lineSpace_10">&nbsp;</div>
                                
                                <!---Start_Upate-->
                                <div style="width:100%;padding-bottom:10px">
                                	<div>
                                    	<div style="width:100%">
                                        <div style="line-height:20px">
                                        Enter Institute ID  &nbsp;
                                        <input type="text" name = "listingId" id = "listingId" style="width:70px;height:18px" validate = "validateInteger" caption = "institute id"> &nbsp; &nbsp; &nbsp; &nbsp;
                                        Enter End Date &nbsp;
			            	<input style="width:75px;" type="text" name="endDate" id="endDate" validate="validateDate" maxlength="10" size="15" readonly class="" onClick="cal.select($('endDate'),'ed','yyyy-MM-dd');"  caption="End Date"/>
                   			<img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="ed" onClick="cal.select($('endDate'),'ed','yyyy-MM-dd');" />
 &nbsp; &nbsp;
<input type="submit" class="btnSubmitted" value="Save" style="font-size:13px" />
                                            <div><div class="errorMsg" id = "listingId_error"></div></div>
                                            <div><div class="errorMsg" id = "mainerror"></div></div>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="grayLine_1">&nbsp;</div>
                                <div class="lineSpace_10">&nbsp;</div>                                
<div class="mar_full10p">
<?php
if(is_array($entitiesForPriorityLeads) && !empty($entitiesForPriorityLeads)) {
?>
<table style="background:#e2e2e2" cellspacing=5 cellpadding=10>
    <tr style="font-weight:bold">
        <td>Listing Id</td>
        <td>Listing Title</td>
        <td>End Date</td>
        <td>Delete</td>
    </tr>
<?php
    foreach($entitiesForPriorityLeads as $entity) {
        $listingId = $entity['listingId'];
        $listingName = $entity['listing_title'];
        $endDateArr = explode(' ',$entity['endDate']);
		$dateArray = explode("-",$endDateArr[0]);
		$year = $dateArray[0];
		$month = $dateArray[1];
		$day = $dateArray[2];
		$endDate = date("d-M-y", mktime(0, 0, 0, $month, $day, $year));
        ?>
            <tr>
                <td><?php echo $listingId; ?></td>
                <td><?php echo $listingName; ?></td>
                <td><?php echo $endDate; ?></td>
                <td><a href="/enterprise/Enterprise/deleteEntityFromPriorityLeads/<?php echo $listingId; ?>" onclick="return confirm('Do you really want to delete the Leads Subscription for <?php echo $listingName; ?> ?');">Delete</a></td>
            </tr>
            <?php
    }
    ?>
    </table>
    <?php
} else {
?>
    <div>No Records</div>
<?php
}
?>
</div>

                            </div>
                        </div>
                    </div>
</form>
                <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
            </div>
        </div>
    </div>
</div>

<script>
	var cal = new CalendarPopup("calendardiv");
</script>
