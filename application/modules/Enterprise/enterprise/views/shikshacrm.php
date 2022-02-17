<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/11009/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php     define ("SHIKSHA_LISTING_DETAIL_PAGE_URL","SHIKSHA_HOME");
?>
<?php if($usergroup == "cms"){ ?>
<title>CMS Control Page</title>
<?php } ?>
<?php if($usergroup == "enterprise"){ ?>
<title>Enterprise Control Page</title>
<?php } ?>

<?php
$headerComponents = array(
'css'	=>	array('headerCms','raised_all','mainStyle','footer','cal_style'),
'js'	=>	array('common','enterprise','home','CalendarPopup','prototype','discussion','events','listing'),
'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
'tabName'	=>	'',
'taburl' => site_url('enterprise/Enterprise'),
'metaKeywords'	=>''
);
//$this->load->view('enterprise/headerCMS', $headerComponents);
?>
<style>
body{
    font-family:arial;
    font-size:12px;
    width:800px;
    margin:0 auto;
    border:1px solid #666;
    border-bottom:0;
    border-top:0;
    padding:10px;
}
</style>
</head>
<body>
				<?php // $this->load->view('enterprise/cmsTabs');
				?>

				<?php
				if($check == 'success')
{
	?> 

		
<span class="OrgangeFont">Response saved successfully.</span>
		

		<?php
}
?>



<?php
if($Resultflag == 'set'  && empty($arr)) 
{
	?> 
		<span class="OrgangeFont"> No results found.</span>
		<?php
}
?>

<div>
<form name="myForm" method="post"  action="/crm/CRM/getrecommendations" onsubmit="return validateForm()" id="formsubmit1"> 

<div class="lineSpace_25">&nbsp; </div>

<input type="radio" name="type" value="institute" <?php if(empty($variable) || $variable == 'institute') { ?>  checked="checked"  <?php } ?> > Institute
<input type="radio" name="type" value="course"  <?php if($variable == 'course') { ?>  checked="checked"   <?php } ?> > Course


<input type="text" name="textdata">
<input type=hidden name="userid" value="<?php echo $userid; ?>">
<input type=hidden name="counsellorid" value="<?php echo $counsellorid; ?>">

<input type="submit" value="Go"/>	

</form>
</div>

<div class="lineSpace_10">&nbsp;</div>
<div style="color:#FF0000" id="editPaymentMsg" class="errorMsg"></div>
<div class="lineSpace_10">&nbsp;</div>




<?php

if(!empty($arr) )
{
	?>  

		<?php

		$result = array_merge((array)$userid, (array)$counsellorid,(array)$profilebased,(array)$institutes,(array)$arr);

	$final = array(
			'userid' => $userid,
			'counsellorid' => $counsellorid,
			'finalarr' =>$profilebased,
			'institutes' => $institutes,
			'data' =>$arr,
			'listingid' => $listingid

		      );


	$this->load->view('enterprise/searchinstitutesbylisting',$final);
}
?>


<?php

if(!empty($institutes))
{
	?>  

<p>
		<span class="OrgangeFont">Institues against which responses already being made.</span>
</p>
		<table cellpadding="3"><tr><td>&nbsp;</td><td><b>Institute</b></td><td><b>Country</b></td><td><b>City</b></td><td><b>Type</b></td><td><b>Course</b></td></td></tr>

		<?php	 
		foreach($institutes as $arr)
		{

			?>
				<tr><td>
    <td><a href="<?php echo SHIKSHA_LISTING_DETAIL_PAGE_URL."/getListingDetail/".$arr['institute_id']."/institute"; ?>" target="_blank"><?php echo $arr['institute_name'];?></a></td>
    <?php if($arr['country'] != "") { ?>
    <td><?php echo $arr['country']; }?></td>
   <?php if($arr['city'] != "") { ?>
    <td><?php echo $arr['city']; }?></td>
  
   <td><?php
   
   if($arr['packtype'] == "1" ||  $arr['packtype'] == "2"  )
   { 
     echo "PAID";
    }
    else
    { echo "UNPAID";
    }
    ?>
   </td>
<td><a href="<?php echo SHIKSHA_LISTING_DETAIL_PAGE_URL."/getListingDetail/".$arr['course_id']."/course"; ?>" target="_blank"><?php echo $arr['course_name'];?></a></td>
</tr>
                                                
                                                
                                                
                                                <?php
		}
	?>

		</table>

		<?php
}
?>




<?php

if(!empty($profilebased))
{
	?>  
<p>
		<span class="OrgangeFont"> Recommended Institutes and their data.</span>
</p>

		<table cellpadding="3"><tr><td>&nbsp;</td><td><b>Institute</b></td><td><b>Country</b></td><td><b>City</b></td><td><b>Type</b></td><td><b>Course</b></td></tr>
		<form method="post" action="/crm/CRM/crmresponses" onsubmit="return checkCheckBoxes(this);" id="formsubmit"> 

		<?php
        $i = 0;
		foreach($profilebased as $arr)
		{
			?>

				<?php
				echo "<div style='float:left; '>";
			?>

				<tr><td>
        <input type="checkbox" name="listingtypeid[]" id="listingid_<?php echo $i++;?>" value="<?php echo $arr['course_id']; ?> "> </td>
        <td><a href="<?php echo SHIKSHA_LISTING_DETAIL_PAGE_URL."/getListingDetail/".$arr['institute_id']."/institute"; ?>" target="_blank"><?php echo $arr['institute_name'];?></a></td>
        <?php if($arr['country'] != "") { ?>
        <td><?php echo $arr['country']; }?></td>
       <?php if($arr['city'] != "") { ?>
        <td><?php echo $arr['city']; }?></td>
      
      <td><?php
							if($arr['pack_type'] == "1" ||  $arr['pack_type'] == "2"  )
							{ 
								echo "PAID";
							}
							else
							{ echo "UNPAID";
							}
			?>
				</td>
    <td><a href="<?php echo SHIKSHA_LISTING_DETAIL_PAGE_URL."/getListingDetail/".$arr['course_id']."/course"; ?>" target="_blank"><?php echo $arr['course_name'];?></a></td>
        </tr>


				
                                                
                                                
                                                
                                                <?php
		}
	?>
		</table>

		<input type=hidden name="userid" value="<?php echo $userid; ?>">
		<input type=hidden name="counsellorid" value="<?php echo $counsellorid; ?>">

		<input type="submit" value="Submit responses"/>								
		</form>

<div class="lineSpace_10">&nbsp;</div>
<div style="color:#FF0000" id="course" class="errorMsg"></div>
<div class="lineSpace_10">&nbsp;</div>
		<?php
}
?>

<?php
if( (empty($profilebased) && empty($institutes) ) || (empty($profilebased))){
	?>

		<div class="lineSpace_25">&nbsp; </div>
		<span class="OrgangeFont">No institutes have been found for this id</span>
		<div class="lineSpace_25">&nbsp; </div>

		<?php
}
?>

	<script type="text/javascript">
function validateForm()
{
	var x=document.forms["myForm"]["textdata"].value;
	if (x==null || x=="")
	{
		document.getElementById('editPaymentMsg').innerHTML = 'Can not be left empty';

		return false;  
	}
}


function checkCheckBoxes(theForm) {
    var i =0;
    var flag = false;
    while(document.getElementById("listingid_"+i) != null){
        if(document.getElementById("listingid_"+i).checked){
            flag = true;
            break;
        }
        i++;
    }
    if(!flag)
    {
        document.getElementById('course').innerHTML = 'No checkbox has been selected';
        return false;
    }
    return true;
}

</script>

<?php //$this->load->view('enterprise/footer');  ?></body>
</html>

