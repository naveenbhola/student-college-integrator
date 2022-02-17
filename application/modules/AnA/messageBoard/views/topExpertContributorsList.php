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

function getStarClassForLeader($level)
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

?>

<input type="hidden" value="<?php echo $contriCountValue; ?>" id="<?php echo $contriCountId;?>"/>
<div class="clear_B">&nbsp;</div>
<ul class="tpCrntbts" style="padding:0;margin:0;background:#fff url(/public/images/tc_bg.jpg) left bottom repeat-x">
<div id="topCAccordion">
<?php
$i=0;$r=0;
shuffle($topContributtos);
foreach($topContributtos as $record):
if(round($record['rpoints'])<=10) {$record['rank']='N/A';}
$shortDisplayName = (strlen($record['displayname'])>17)?substr($record['displayname'],0,17).'...':$record['displayname'];
if($record['avtarimageurl']=='')
$record['avtarimageurl'] = "/public/images/photoNotAvailable.gif";
$liClass = ($i== (count($topContributtos)-1))?'bbtm1':'bbtm';
$i++;

?>
<dt style="cursor:pointer;">
<div class="lineSpace_10">&nbsp;</div>
<div style="width:100%;background:#fff url(/public/images/tc_bg.jpg) left bottom repeat-x">
<div style="margin:0 10px;float:none">
<div style="float:left; width: 36px;"><img src="<?php echo getTinyImage($record['avtarimageurl']); ?>" border='0'/></div>
<p style="float:left;margin:0;padding:0 0 0 10px; width:82%;" class="fs11 <?php if(isset($showContributor) && ($showContributor == "1")){  echo getStarClassForLeader($record['level']); } ?>">
<span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'<?php echo $record['userid']; ?>');}catch(e){}" style="width:30px;display:inline;" ><a href="<?php echo $userProfile.$record['displayname']; ?>"><b class="Fnt14"><?php echo $shortDisplayName; ?></b></a></span><br />
<?php if(isset($showContributor) && ($showContributor == "1")){ ?>
    <span class="fcOrg forA" style="line-height:16px;"><a href="/shikshaHelp/ShikshaHelp/upsInfo"><?php if($record['level']=='')echo "Beginner";else echo $record['level']; ?></a></span><br />
        <?php } ?>
        </p>
        <s></s>                 
        </div>
        <div style="height:10px;overflow:hidden">&nbsp;</div>
        </div>          
        </dt>
        <?php if(isset($record['expertize']) && $record['expertize']!='') $height=''; else $height=' height:135px;'; ?>
        <dd style="margin-left:0px;width:100%;float:left;">
        <div style="width:100%;<?php echo $height;?>background:#fff url(/public/images/tc_bg.jpg) left bottom repeat-x">
        <div style="margin:0 10px;float:none;">
        <?php if(isset($showContributor) && ($showContributor == "1")){ ?>
        <span class="Fnt14 bld" style="display:block;margin:10px 0 5px 0">Contribution Stats</span>
        <span class="ana_a fs11 lineSpace_20 mb10" style="display:block;">
        <span style="display:block;"><strong class="aqIcn rUp"><?php echo $record['likes']; ?></strong> upvotes</span>
        <span style="display:block;"><?php echo $record['totalAnswers']; ?> <?php echo ($record['totalAnswers']>1)?'Answers':'Answer'; ?></span>
        <span style="display:block;"><?php echo $record['totalPoints']; ?> Points (<?php echo $record['userPointValue']; ?> this week)</span>
        </span>
        <?php } ?>
        <?php if($record['expertize']!=''){ ?>
        <span class="Fnt14 bld" style="display:block;">Expertise</span>
        <span class="Fnt11 mb10" style="display:block;"><?php echo $record['expertize']; ?></span>
        <?php } ?>

        <?php if($record['designation']!=''){ ?>
        <span class="Fnt14 bld" style="display:block;">Current Position</span>
        <span class="Fnt11 mb10" style="display:block;"><?php echo $record['designation']; ?></span>
        <?php } ?>

        <?php if($record['highestQualification']!=''){ ?>
        <span class="Fnt14 bld" style="display:block;">Education</span>
        <span class="Fnt11 mb10" style="display:block;"><?php echo $record['highestQualification']; ?></span>
        <?php } ?>

        <?php if($record['aboutCompany']!=''){ ?>
        <span class="Fnt14 bld" style="display:block;">Company</span>
        <span class="Fnt11 mb10" style="display:block;"><?php echo substr($record['aboutCompany'],0,30); ?></span>
        <?php } ?>

        </div>
        <div class="clear_B" style="width:100%">&nbsp;</div>
        </div>
        </dd>
        <?php endforeach; ?>
        </div>
        </ul>
        <div class="clear_B" style="background:#efedee">&nbsp;</div>
