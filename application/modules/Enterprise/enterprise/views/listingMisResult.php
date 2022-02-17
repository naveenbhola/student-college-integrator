<?php 

$x = $from?$from:'Start Date';
$y = $to?$to:'End Date';

?>
<div style="font-size:13px;font-weight:bold;padding:10px 0 20px 5px;">Select Date for Listing Updation to Download % completion Report </div>
<form action= "/enterprise/Enterprise/index/203" method ='post'>
<div style = "background-color: honeydew; width :220px;padding:10px 0 20px 5px;">
<div style="padding-bottom:10px;">Select Date Range</div>
<div >
<span style="float:left;clear:both;">From :</span> <span style="float:right;"><input id = "from_date" style="width:75px;" type="text" required="true" name="from_date" id="StartDateFilter" value= "<?php echo $x;?>" readonly maxlength="10" size="15" onClick="cal.select($('DOB'),'sd','yyyy-MM-dd');"/>
                <img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="sd" onClick="cal.select($('from_date'),'sd','yyyy-MM-dd');" /></span>
</div>
<div style = "clear:both;">
<span style="float:left;">To :</span> <span style="float:right;"><input id = "to_date"  style="width:75px;" type="text" required="true" name="to_date" id="EndDateFilter" value="<?php echo $y;?>" readonly maxlength="10" size="15" onClick="cal.select($('DOB'),'sd','yyyy-MM-dd');"/>
                <img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="sd" onClick="cal.select($('to_date'),'sd','yyyy-MM-dd');" /></span>
</div>
<div style="padding-top:40px;">
<button id = "submitButton" style="width:100px" value="" type="submit" class="btn-submit19" onclick = "return validateDateDiffernce($('from_date').value,$('to_date').value)";>
<div class="btn-submit19"><p class="btn-submit20" style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px">Submit</p></div>
</button>
</div>
</div>
</form>
<?php 
$flag = 0;
if($listingMis['message']!='')
{
	echo $listingMis['message'];
	$flag =1;
}
else if($flag ==0 && count($listingMis)>0){?>
<table width="100%" cellpadding="2">
<tr><td valign='top' class='border' style = "font-size :14px"><strong> Institute name</strong></td><td valign='top' class='border' style = "font-size :14px"> <strong>Location</strong></td><td valign='top' class='border' style = "font-size :14px" ><strong>Category of flagship course</strong></td><td valign='top' class='border' style = "font-size :14px" ><strong>Total no. of views</strong></td><td valign='top' class='border' style = "font-size :14px" > <strong>Views in last 45 days</strong> </td><td valign='top' class='border' style = "font-size :14px" > <strong>Name of sales person</strong></td><td valign='top' class='border' style = "font-size :14px" > <strong>% completion</strong></td><td valign='top' class='border' style = "font-size :14px" > <strong>Last updated on</strong></td></tr>

<?php foreach($listingMis as $rs){
	echo "<tr>";
	foreach($rs as $key=>$value){ 
		echo "<td  valign='top' class='border' style='font-weight:normal;'>".$value."</td>";
	}
	echo "</tr>";
		

}?>
</table>

<a href="/enterprise/Enterprise/getlistingMISForDownload/<?php echo $from?>/<?php echo $to?>">
<button style="width:120px" value="" type="button" class="btn-submit19">
<div class="btn-submit19"><p class="btn-submit20" style="padding: 15px 8px 15px 5px;color:#FFFFFF; font-size:12px">Download CSV</p></div>
</button>
</a>
<?php } ?>
<script>
var cal = new CalendarPopup("calendardiv");
cal.setYearSelectStartOffset(35);

function validateDateDiffernce(from ,to){		
	from = new Date(from);
	to = new Date(to);
	if((to.getTime() - from.getTime()) > 1000*24*60*60*29)
	{
		alert("Please select date range of maximum 30 days");
		return false;	
		
	}
	if((to.getTime() - from.getTime()) < 0)
	{
		alert("To date should be greater than From date");
		return false;	
		
	}
	else
	{
		return true;
	}
}
	
</script>
<style>
.border {
    border: 1px solid grey;
    border-collapse: collapse;
}
</style>
		


