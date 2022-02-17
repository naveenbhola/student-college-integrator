<div class="cntry-tabs" style="z-index:90;">
    <ul id="navBarUl">
    	<?php foreach($coursesOnPage as $courseId){ ?>
    		<li <?php if($activeCourseId == $courseId){?>class="active"<?php } ?>>
    			<a href="javascript:void(0)" class="cNavButton" courseid="<?php echo $courseId;?>">
    				<span class="inner-circle">
    					<i class="study-sprite c<?php echo $courseId; ?>-icn"></i>
    				</span>
    				<p><?php echo $courseNames[$courseId]?></p>
    			</a>
    		</li>
    	<?php } ?>
    </ul>
</div>
