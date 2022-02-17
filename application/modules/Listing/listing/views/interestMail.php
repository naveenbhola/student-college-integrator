A Shiksha.com member <?php echo $nameOfUser ;?> is interested in your institute.you might want to contact them at the number mentioned or email them to further explore his admission interest.<br/><br/> Given below are <?php echo $nameOfUser ;?> details:
<br/>

<br/>

<b>Name:</b> <?php  echo $nameOfUser; ?> <br/>
<b>Cell:</b> <?php echo $mobile; ?> <br/>
<b>Email:</b> <?php echo $usernameemail; ?> <br/>
<b>Education Interest:</b> <?php echo $educationInterest; ?> <br/>
<b>Highest Education Level: </b><?php echo $educationLevel; ?> <br/>
<b>Age:</b> <?php echo $age; ?> <br/>
<b>Residence city:</b> <?php echo $usercity ?> <br/>
<?php
if($preferred_city && $preferred_locality)
{
    echo "<b>Locality:</b> $preferred_locality, $preferred_city<br/>";
}
?>
<br />
Regards
<br/>
Shiksha Team
