<div width="600">

Dear <?php echo $userInfo[0][0]->displayname; ?>,<br/>
<p><br/></p>
<p>Here are your activity updates from <b><?php echo $fromdate; ?></b> to  <b><?php echo $todate ?></b></p>
<p>Your Q&A designation: <b><?php echo $userInfo[0][0]->designation; ?></b></p>
<p>Total Q&A Points: <b><?php echo $userInfo[0][0]->userpointvaluebymodule; ?></b></p>
<p>Reputation Index: <b><?php echo $reputationPoints; ?></b> <?php if($difference>0) { ?>(up <?php echo $difference; ?> points) <?php } elseif($difference<0){?> (down <?php echo -$difference; ?> points)  <?php }else{}  ?></p>
<!--<p>Current Reputation Rank: <b>--><?php //echo $rank; ?><!--</b></p>-->
<p>Best answers this week: <b><?php echo $userInfo[0][0]->Points->choosenbestAnswer; ?></b> </p>
<p>You received <b><?php echo $userInfo[0][0]->Points->receiveThumpUpAnswer; ?> Thumb ups</b> and <b><?php echo $userInfo[0][0]->Points->receiveThumpDownAnswer; ?> Thumb downs</b> on your answers.</p>
<p>New Followers: <b><?php echo $userInfo[0][0]->followUser->numberWithTime; ?></b>, Total Followers: <b><?php echo $userInfo[0][0]->followUser->number; ?></b></p>

<?php if($difference>0) { ?> <p>Your reputation index <!--and rankings have -->has <span style="color:green">Increased</span> since last week. Keep up the good work!</p> <?php } ?>
<?php if($difference<0) { ?><p>Your reputation index <!--and rankings have--> has <span style="color:red">Decreased</span> since last week. Improve it through quality answers and active participation. <?php if($userInfo[0][0]->days<-9){ ?> You are inactive from last <?php echo -$userInfo[0][0]->days; }?> days.</p> <?php } ?>
<p><b>Open questions in your network:</b></p>
<p><b>Q:</b><a href="<?php echo getSeoUrl($userInfo[0][0]->userQuestion[0][0]->threadId,'question',$userInfo[0][0]->userQuestion[0][0]->msgTxt); ?>"> <?php echo nl2br($userInfo[0][0]->userQuestion[0][0]->msgTxt); ?></a></p>
<p><b>Q:</b> <a href="<?php echo getSeoUrl($userInfo[0][0]->userQuestion[1][0]->threadId,'question',$userInfo[0][0]->userQuestion[1][0]->msgTxt); ?>"><?php echo nl2br($userInfo[0][0]->userQuestion[1][0]->msgTxt); ?></a></p>
<p><b>Help</b></p>
<p><a href="https://www.shiksha.com/shikshaHelp/ShikshaHelp/termCondition">Shiksha café rules and terms</a></p>
</div>
