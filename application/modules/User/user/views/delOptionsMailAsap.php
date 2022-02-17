<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=us-ascii">
</head>
<body>
<div style="width:550px">
<table cellpadding="0" cellspacing="0" width="100%" border="0" style="font-size:12px; font-family:Arial,Helvetica,sans-serif">
<tbody>
<tr>
<td>
<p>Dear <?php echo $clientName;?></p>
<p>Please find the detail of the <?php echo $count; ?> new lead matching your Search Agents: <?php echo $searchAgentName; ?>'s criteria below:</p>
<p>
<b>New lead details (<?php echo $count; ?>)</b>
<br>
<?php foreach($leadDetailsArray as $temp){ ?>
<br>
<?php if(!empty($temp['First Name'])){ ?><b>First Name: </b> <?php echo $temp['First Name']; ?><br><?php } ?>
<?php if(!empty($temp['Last Name'])){ ?><b>Last Name: </b> <?php echo $temp['Last Name']; ?><br><?php } ?>
<?php if(!empty($temp['Name'])){ ?><b>Name: </b> <?php echo $temp['Name']; ?><br><?php } ?>
<?php if(!empty($temp['Full Name'])){ ?><b>Name: </b> <?php echo $temp['Full Name']; ?><br><?php } ?>

<?php if(!empty($temp['Email'])){ ?><b>Email: </b> <?php echo $temp['Email']; ?><br><?php } ?>

<?php if(!empty($temp['Mobile'])){ ?><b>Phone: </b> <?php if(!empty($temp['ISD Code'])){ echo "+".$temp['ISD Code']."-"; } ?>
<?php echo $temp['Mobile']; ?><br><?php } ?>

<?php if(!empty($temp['Stream'])){ ?><b>Stream: </b> <?php echo $temp['Stream']; ?><br><?php } ?>
<?php if(!empty($temp['Sub Stream'])){ ?><b>Sub Stream: </b> <?php echo $temp['Sub Stream']; ?><br><?php } ?>
<?php if(!empty($temp['Specialization'])){ ?><b>Specialization: </b> <?php echo $temp['Specialization']; ?><br><?php } ?>
<?php if(!empty($temp['Course'])){ ?><b>Course: </b> <?php echo $temp['Course']; ?><br><?php } ?>
<?php if(!empty($temp['Mode'])){ ?><b>Mode: </b> <?php echo $temp['Mode']; ?><br><?php } ?>
<?php if(!empty($temp['Date Of Interest'])){ ?><b>Date Of Interest: </b> <?php echo $temp['Date Of Interest']; ?><br><?php } ?>

<?php 
	if($temp['Work Experience'] <2){
		//$years = ' year';
	}else{
		//$years = ' years';
	}
?>

<?php if(isset($temp['Work Experience']) && $temp['Work Experience']!='' ){ ?><b>Work Experience: </b> <?php echo $temp['Work Experience']; echo $years; ?><br><?php } ?>

<?php if((!empty($temp['Valid Passport'])) && ($temp['Extra Flag'] == 'studyabroad')){ ?><b>Valid Passport:</b> <?php echo $temp['Valid Passport']; ?><br><?php } ?>

<?php if(!empty($temp['Field of Interest']) && ($temp['Extra Flag'] !== 'studyabroad') && $searchAgentType !== 'response'){ ?><b>Desired Course:</b> <?php echo $temp['Field of Interest']; ?><br><?php } ?>

<?php if(!empty($temp['Matched Response For']) && ($temp['Extra Flag'] !== 'studyabroad') && $searchAgentType == 'response'){ ?><b>Matched Response For:</b> <?php echo $temp['Matched Response For']; ?><br><?php } ?>

<?php if(!empty($temp['Desired Course']) && ($temp['Extra Flag'] == 'studyabroad')){ ?><b>Desired Course:</b> <?php echo $temp['Desired Course']; ?>

<?php if(!empty($temp['Desired Specialization']) && ($temp['Extra Flag'] == 'studyabroad')){ ?>(<?php echo $temp['Desired Specialization']; ?>)<?php } ?>
<br/>
<?php } ?>


<?php if(!empty($temp['Plan to start']) && ($temp['Extra Flag'] == 'studyabroad')){ ?><b>Plan to start:</b> <?php echo $temp['Plan to start']; ?><br><?php } ?>

<?php if(!empty($temp['Graduation Year']) && ($temp['Extra Flag'] !== 'studyabroad')){ ?><b>Graduation Year:</b> <?php echo $temp['Graduation Month']." ".$temp['Graduation Year']; ?><br><?php } ?>

<?php if(!empty($temp['Std XII Year']) && ($temp['Extra Flag'] !== 'studyabroad')){ ?><b>XII Year:</b> <?php echo $temp['Std XII Year']; ?><br><?php } ?>

<?php if(!empty($temp['Exams Taken'])){ ?><b>Exams Taken:</b> <?php echo $temp['Exams Taken']; ?><br><?php } ?>

<?php if(!empty($temp['Exam Taken 1'])){ ?><b>Exam Taken 1:</b> <?php echo $temp['Exam Taken 1']; ?><br><?php } ?>

<?php if(!empty($temp['Exam Taken 2'])){ ?><b>Exam Taken 2:</b> <?php echo $temp['Exam Taken 2']; ?><br><?php } ?>

<?php if(!empty($temp['Exam Taken 3'])){ ?><b>Exam Taken 3:</b> <?php echo $temp['Exam Taken 3']; ?><br><?php } ?>


<?php if(!empty($temp['Current Country'])){ ?><b>Current Country:</b> <?php echo $temp['Current Country']; ?><br><?php } ?>
<?php if(!empty($temp['Current City'])){ ?><b>Current City:</b> <?php echo $temp['Current City']; ?><br><?php } ?>	

<?php if(!empty($temp['Current Location'])){ ?><b>Current Location:</b> <?php echo $temp['Current Location']; ?><br><?php } /*else if(($searchAgentType == 'lead') && (!empty($temp['Preferred Locations'])) && ($temp['Extra Flag'] !== 'studyabroad')) { ?><b>Preferred Location:</b> <?php echo $temp['Preferred Locations']; ?><br><?php } */?>

<?php if(!empty($temp['Current Locality'])){ ?><b>Current Locality:</b> <?php echo $temp['Current Locality']; ?><br><?php } ?>

<?php if(!empty($temp['Preferred Study Locations'])){ ?><b>Preferred Study Locations:</b> <?php echo $temp['Preferred Study Locations']; ?><br><?php } ?>

<?php if(!empty($temp['Desired Countries']) && ($temp['Extra Flag'] == 'studyabroad')){ ?><b>Desired Countries:</b> <?php echo $temp['Desired Countries']; ?><br><?php } ?>

<?php if(!empty($temp['Response Date']) && ($temp['Extra Flag'] !== 'studyabroad') && $searchAgentType == 'response'){ ?><b>Response Date:</b> <?php echo $temp['Response Date']; ?><br><?php } ?>

<?php if(!empty($temp['Last Login Time']) && ($temp['Extra Flag'] == 'studyabroad')){ ?><b>Last login on:</b> <?php echo $temp['Last Login Time']; ?><br><?php } ?>

<?php if(!empty($temp['Is in NDNC list'])){ ?><b>Is in NDNC list:</b> <?php echo $temp['Is in NDNC list']; ?><br><?php } ?>
<?php if(!empty($temp['NDNC Status'])){ ?><b>NDNC Status:</b> <?php echo $temp['NDNC Status']; ?><br><?php } ?>
<br><p>--------------------------------------------------------------------------------------</p>
<?php } ?>
</p>

<p>To change the match criteria of this agent please login to <a href="<?php echo SHIKSHA_HOME;?>" >Shiksha.com</a></p>
<br>
<p>Best Regards<br>
Shiksha.com Team </p>
</td>
</tr>
</tbody>
</table>
</div>
</body>
</html>
