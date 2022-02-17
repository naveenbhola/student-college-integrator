<div width="600">
Dear <?php echo $name; ?>,<br/>
<?php $entityType = ($entityType == 'Blog Comment'|| $entityType == 'Event Comment')?'Comment':$entityType; ?>
<p>One of the <?php echo $entityType;?> you posted on <a href="<?php echo $sectionURL;?>"><?php echo $section;?></a> has been removed from the site on account of abuse reports by community members. You have also been penalized with 30 Shiksha points.</p>
<p>We advise you to adhere to our community guidelines while making a post on Shiksha.com to avoid their removal. 
We hope for your cooperation in keeping Shiksha.com clean and useful for community members. Please <a href="<?php echo $communityUrl; ?> ">click here</a> to refer to the Shiksha community guidelines.</p>
<br/>
<b>Your <?php echo $entityType;?>:</b> <?php echo nl2br($entityText);?>
<br/>
<p>Please note that our moderators would review your <?php echo $entityType;?> and if it is found to be appropriate, 
it would be republished and penalty would be reversed. You do not need to contact us for same.</p>
<br/>
Best Regards<br/>
Shiksha.com team
</div>
