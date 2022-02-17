<div width="600">
Dear <?php echo $name; ?>,<br/>

<?php $entityType = ($entityType == 'Blog Comment'|| $entityType == 'Event Comment')?'Comment':$entityType; ?>
<p>Our moderators have reviewed the <a href="<?php echo $entityURL;?>"><?php echo $entityType;?></a> you reported as abuse on <a href="<?php echo $sectionURL;?>"><?php echo $section;?></a> and found that to be appropriate. 
Henceforth, your abuse report has been rejected<?php echo $penalty;?>.</p>
<p>We advise you to take into account our <a href="<?php echo $communityUrl; ?>">community guidelines</a> while choosing to report any content published on Shiksha.com as abuse. We hope for your cooperation.</p>
<br/>
Best Regards<br/>
Shiksha.com team
</div>
