<div width="600">
Dear <?php echo $name; ?>,<br/>
<?php $entityType = ($entityType == 'Blog Comment'|| $entityType == 'Event Comment')?'Comment':$entityType; ?>
<p>Our moderators have reviewed your <?php echo $entityType;?> which was removed on account of abuse reports and found it to be appropriate. 
The same has been republished on <a href="<?php echo $sectionURL;?>"><?php echo $section;?></a>. 
We have also reversed the penalty of 30 points applied earlier on removal.</p>
<p>We regret the inconvenience caused and hope that you would continue to extend your invaluable contribution to the <a href="<?php echo $sectionURL;?>"><?php echo $section;?></a> 
and many more students would benefit from your efforts. </p>
<br/>
Best Regards<br/>
Shiksha.com team
</div>
