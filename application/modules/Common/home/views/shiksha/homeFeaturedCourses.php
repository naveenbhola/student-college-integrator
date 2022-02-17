<div class="raised_lgraynoBG">
	<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
	<div class="boxcontent_lgraynoBG">
	  <div class="pps5 float_R" style="height:112px; padding:0;margin-right:5px"></div>
	  <div class="mar_full_10p" style="margin-right:105px">
			<div class="lineSpace_5">&nbsp;</div>
			<div><h3><span class="myHeadingControl bld">Featured Courses</span></h3></div>
			<br class="lineSpace_15" />
            <?php
                foreach($featuredCoursesList as $featuredCourse => $featuredCourseUrl) {
            ?>
			<div class="quesAnsBullets"><a href="<?php echo $featuredCourseUrl; ?>" class="fontSize_12p" title="<?php echo $featuredCourse; ?>"><?php echo $featuredCourse; ?></a></div>
			<br class="lineSpace_10" />
            <?php
                }
            ?>
	   </div>
	  <div class="clear_R"></div>
	</div>
  <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
</div>
<div class="lineSpace_10">&nbsp;</div>
