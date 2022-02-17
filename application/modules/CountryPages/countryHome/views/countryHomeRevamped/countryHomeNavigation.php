<div class="left-col flLt">
    <ul id="courseNavigationBar">
    	<?php foreach(array_keys($coursesData) as $courseId){?>
        <li <?php if($courseId == $activeCourseId) echo "class='active'"; ?> id="courseNav<?php echo $courseId?>">
        	<a href="javascript:void(0);" class="courseNavigationButton" courseId="<?php echo $courseId?>">
			<!-- <span class="course-circle"><i class="study-sprite c<?php echo $courseId;?>-icon"></i></span> -->
			<span class="course-Link"><?php echo htmlentities($courseNames[$courseId]=="BE/Btech"?"BTech":$courseNames[$courseId]); ?></span>
			</a>
			<i class="study-sprite study-pointer"></i>
        </li>
        <?php } ?>
    </ul>
</div>
