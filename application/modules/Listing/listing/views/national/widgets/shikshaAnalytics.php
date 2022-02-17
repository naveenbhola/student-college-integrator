<h6>Shiksha Analytics</h6>
<ul uniqueattr="NATIONAL_COURSE_PAGE/Shiksha_Analytics">
<?php if(trim($updatedDate,"/")!=""){ ?> <li><p> <?php echo "This information  was last updated on ".$updatedDate;?>.</p></li><?php } 	
if($responseCount > 0)
{ ?>
<li><p><?=$responseCount ?> student<?=($responseCount == 1 )?' has' : 's have'?> downloaded brochure.</p></li>
<?php
} ?>
	<li><p>Courses have been viewed <?=$viewCount?> time<?=($viewCount == 1)?'':'s'?>. </p></li>
</ul>
<script type="text/javascript">
var responseCount = '<?php echo $responseCount?>';
</script>

