<?php 
if(isset($showContributor) && ($showContributor == "1")){
  $topContributtos = (isset($topContributtingAndExpertPanel[0]) && is_array($topContributtingAndExpertPanel[0]))?$topContributtingAndExpertPanel[0]['mostcontributing']:array();
  $contriCountId = "totalContributorCount";
  $contriCountValue = count($topContributtingAndExpertPanel[0]['mostcontributing']);
}
if(isset($showParticipant) && ($showParticipant == "1")){
  $topContributtos = (isset($topContributtingAndExpertPanel[0]) && is_array($topContributtingAndExpertPanel[0]))?$topContributtingAndExpertPanel[0]['mostcontributingParticipate']:array();
  $contriCountId = "totalContributorCountP";
  $contriCountValue = count($topContributtingAndExpertPanel[0]['mostcontributingParticipate']);
}
$userProfile = site_url('getUserProfile').'/';
        /*if(!function_exists('getStarClassForLeader1')) {
	function getStarClassForLeader1($level)
	{
	    if($level!=''){
		switch($level){
		    case 'Advisor': return 'str_1lx27';
		    case 'Senior Advisor': return 'str_12x27';
		    case 'Lead Advisor': return 'str_13x27';
		    case 'Principal Advisor': return 'str_14x27';
		    case 'Chief Advisor': return 'str_15x27';
		}
	    }
	}
        }
        */
?>

<input type="hidden" value="<?php echo $contriCountValue; ?>" id="<?php echo $contriCountId;?>"/>
<ul class="tpCrntbts" style="border:1px solid #fff">
<?php
$i=0;
foreach($topContributtos as $record):
$shortDisplayName = (strlen($record['firstname'].' '.$record['lastname'])>17)?substr($record['firstname'].' '.$record['lastname'],0,17).'...':$record['firstname'].' '.$record['lastname'];
if($record['avtarimageurl']=='')
  $record['avtarimageurl'] = "/public/images/photoNotAvailable.gif";
  $liClass = ($i== (count($topContributtos)-1))?'bbtm1':'bbtm';
  $i++;
?>
<!--Start_Listing-->
<li class="<?php echo $liClass;?>" style="padding-top:10px;">
  <div><img src="<?php echo getTinyImage($record['avtarimageurl']); ?>" border='0'/></div>
  <p class="fs11" style="width:78%;">
	<span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'<?php echo $record['userid']; ?>');}catch(e){}" style="width:30px;display:inline;" ><a href="<?php echo $userProfile.$record['displayname']; ?>"><b><?php echo $shortDisplayName; ?></b></a></span><br />

	<?php if(isset($showContributor) && ($showContributor == "1")){ ?>
	<span class="fcOrg forA" style="line-height:16px;"><a href="/shikshaHelp/ShikshaHelp/upsInfo"><?php if($record['level']=='')echo "Beginner";else echo $record['level']; ?></a></span><br />
	
	<span class="fcGya" ><b style="#color:#000"><?php echo $record['totalPoints']; ?></b> Points <?php if($weekly == 1){ ?> (<b style="#color:#000"><?php echo $record['weeklyPoints']; ?></b> this week) <?php }?></span>
	<br />
	<?php } ?>

	<?php if(isset($showParticipant) && ($showParticipant == "1")){ ?>
	<span style="line-height:16px;"><?php echo $record['discussionPosts']; ?> <?php echo ($record['discussionPosts']>1)?'discussion posts':'discussion post';?></span><br />
	<span style="line-height:16px;"><?php echo $record['announcementPosts']; ?> <?php echo ($record['announcementPosts']>1)?'announcements':'announcement';?></span><br />
	<a href="/shikshaHelp/ShikshaHelp/upsInfo" style="color:#000;line-height:16px;"><?php echo $record['totalUserPointValue']; ?></a> Cafe points
	<?php } ?>

  </p>
  <s></s>
<?php if(isset($showContributor) && ($showContributor == "1")){ ?>
  <span class="ana_a fs11 lineSpace_20" style="display:block;"><?php echo $record['totalAnswers']; ?> <?php echo ($record['totalAnswers']>1)?'Answers':'Answer'; ?> </span>
<?php } ?>
</li>
<!--End_Listing-->
<?php endforeach; ?>
</ul>
