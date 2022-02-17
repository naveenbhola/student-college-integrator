
<br>
<?php if(!empty($temp['Full Name'])){ ?><b>Name: </b> <?php echo $temp['Full Name']; ?><br><?php } ?>

<?php if(!empty($temp['Email'])){ ?><b>Email: </b> <?php echo $temp['Email']; ?><br><?php } ?>

<?php if(!empty($temp['Mobile'])){ ?><b>Phone: </b> <?php if(!empty($temp['ISD Code'])){ echo "+".$temp['ISD Code']."-"; } ?>
<?php echo $temp['Mobile']; ?><br><?php } ?>

<?php if(!empty($temp['Exams Taken'])){ ?><b>Exams Taken:</b> <?php echo $temp['Exams Taken']; ?><br><?php } ?>


<?php if(!empty($temp['Current Country'])){ ?><b>Current Country:</b> <?php echo $temp['Current Country']; ?><br><?php } ?>

<?php if(!empty($temp['Current City'])){ ?><b>Current City:</b> <?php echo $temp['Current City']; ?><br><?php } ?>	

<?php if(!empty($temp['Current Location'])){ ?><b>Current Location:</b> <?php echo $temp['Current Location']; ?><br><?php } ?>

<?php if(!empty($temp['Current Locality'])){ ?><b>Current Locality:</b> <?php echo $temp['Current Locality']; ?><br><?php } ?>

<?php if(!empty($temp['EducationData']['12'])){ ?><b>XII Passing Year:</b> <?php echo date("Y", strtotime($temp['EducationData']['12']['CourseCompletionDate']) ); ?><br><?php } ?>

<?php if(!empty($temp['EducationData']['UG'])){ ?><b>Graduation Year:</b> <?php echo date("Y", strtotime($temp['EducationData']['UG']['CourseCompletionDate']) ); ?><br><?php } ?>

<?php if(!empty($response_time_map[$temp['userId']])){ ?><b>Response Date:</b> <?php echo $response_time_map[$temp['userId']][$temp['group_id']]; ?><br><?php } ?>

<?php if(!empty($temp['NDNC Status'])){ ?><b>NDNC Status:</b> <?php echo $temp['NDNC Status']; ?><br><?php } ?>

<?php if(!empty($temp['Work Experience'])){ ?><b>Work Experience:</b> <?php echo $temp['Work Experience']; ?><br><?php } ?>

<?php if(!empty($temp['exam_name'])){ ?><b>Exam Name:</b> <?php echo $temp['exam_name']; ?><br><?php } ?>

<?php if(!empty($temp['group_name'])){ ?><b>Course Name:</b> <?php echo $temp['group_name']; ?><br><?php }
unset($temp);
 ?>
<br><p>--------------------------------------------------------------------------------------</p>

</p>