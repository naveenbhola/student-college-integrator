<div id="course-title">
<?php if(!empty($keyword)){ ?>
	<h1>Search Results for <span class="cate-color"><strong>&ldquo;<?php echo htmlspecialchars($keyword);?>&rdquo;</strong></span></h1>  
<?php }  else { ?>
        <h1>Please enter a Search keyword.</span></h1>  
<?php } ?>
<script>
	var rmcPageTitle = "<?=base64_encode('Search Page');?>";
</script>
</div>
<?php if($total_count) { $spanFlag = 0; if($sa_course_count>0 && $university_count>0){ $spanFlag =1;} ?>
<div class="institute-nav clearwidth">
	<ul class="institute-tab">
		<li class="search-menu active">
			<strong><?php echo $total_count ?> Results found :</strong>
                        <?php if($university_count>0){ ?>
                        <a href="javascript:void(0);" onclick="goToUniversityTupples();"><?php echo $university_count;?> <?php echo ($university_count == 1)? "University" : "Universities"; ?></a>
                        <?php }?>
						<?php if($spanFlag) { ?> <span>|</span> <?php } ?>
						<?php if($sa_course_count>0){ ?>
                        <a href="javascript:void(0);" onclick="goToCourseTupples();"><?php echo $sa_course_count;?> <?php echo ($sa_course_count == 1)? "Course" : "Courses"; ?></a>
						<?php }?>
			<i class="cate-sprite pointer"></i>           
		</li>
		<!--<li><a href="#"><i class="cate-sprite shrtlist-icon"></i>Shortlisted institutes (3)</a> <i class="cate-sprite pointer"></i></li>-->
	</ul>
</div>
<?php }  else { ?>
        <div class="zero-searchResult clearwidth">
			<?php if(ENABLE_ABROAD_SEARCH){ ?>
        	<p>No results found.</p>
            <p>Please change your keyword and Search again.</p>
			<?php } else{ ?>
			<p>Something went wrong.</p>
			<p>Please try again later.</p>
			<?php } ?>
        </div>  
<?php } ?>