<div width="600">
Dear <?php echo $name; ?>,<br/>
<?php $entityType = ($entityType == 'Blog Comment'|| $entityType == 'Event Comment')?'Comment':$entityType; ?>
<p>Our moderators have reviewed your <?php echo $entityType?> which had  received abuse reports from other Shiksha.com users and found it to be appropriate. 
Henceforth, these abuse reports have been rejected. 
We have also informed the involved users to practice caution while choosing to report abuse on Shiksha.com</p>
<p>We regret the inconvenience caused and hope that you would continue to extend your invaluable contribution to the 
<a href="<?php echo $sectionURL;?>"><?php echo $section;?></a> and many more students would benefit from your efforts. </p>
<br/>
Best Regards<br/>
Shiksha.com team
</div>
