<?php 
//Using Study Abroad Config
global $configData;
$studyInIndia       = $configData['dataForHeaderFooter']['studyInIndia'];
?>
<div class="shikha-tab clearfix" style="margin: 8px 0 8px 8px;position :relative; float: left;">
	<a href="javascript:void(0);" class="shiksha-home-tab flRt" style="margin-bottom:10px;" onmouseover="$j('.shiksha-home-tab').addClass('active');$j('#shikshaHomeBtnNav').show();" onmouseout="$j('.shiksha-home-tab').removeClass('active');$j('#shikshaHomeBtnNav').hide();"><i
		class="exam-sprite shiksha-home-icon"></i>Shiksha Home<i
		class="exam-sprite pointer"></i></a>
        <div id ="shikshaHomeBtnNav" style="width: 720px; right:0px; top:37px; left: auto; display: none;" class="nav-layer" onmouseover="$j('.shiksha-home-tab').addClass('active');$j('#shikshaHomeBtnNav').show();" onmouseout="$j('.shiksha-home-tab').removeClass('active');$j('#shikshaHomeBtnNav').hide();">
        	<div class="layer-details-col">
            	<p><strong>MBA Courses in India</strong></p>
                <div class="layer-items clear-width">
                    <ul>
				    <?php
					foreach ( $studyInIndia ['mbaCoursesInIndia'] as $key => $value ) {
									?>
					<li><a
						href="<?php echo $value['url']?>"><?php echo $value['title']?></a></li>
					<?php } ?>
                    </ul>
                 </div>
                 <p><strong>Courses after 12</strong></p>
                 <div class="layer-items clear-width">
                    <ul>
				    <?php
					foreach ( $studyInIndia ['bachelorsCoursesInIndia'] as $key => $value ) {
									?>
					<li><a
						href="<?php echo $value['url']?>"><?php echo $value['title']?></a></li>
				<?php } ?>
                    </ul>
                 </div>
                
            </div>
            <div class="layer-details-col">
                  <p><strong>Other Courses</strong></p>
                  <div class="layer-items clear-width">
                    <ul>
				    <?php
					foreach ( $studyInIndia ['otherCourses'] as $key => $value ) {
									?>
					<li><a
						href="<?php echo $value['url']?>"><?php echo $value['title']?></a></li>
				<?php } ?>
                      </ul>
                 </div>
            </div>
            <div class="layer-details-col last">
            	<p><strong>Shiksha Café</strong></p>
                <div class="layer-items clear-width">
                    <ul>
				   <?php
							foreach ( $studyInIndia ['shikshaCafe'] as $key => $value ) {
								?>
					<li><a
						href="<?php echo $value['url']?>"><?php echo $value['title']?></a></li>
				<?php } ?>
                    </ul>
                 </div>
                 <p><strong>Application Forms</strong></p>
                 <div class="layer-items clear-width">
                    <ul>
                    <?php
								foreach ( $studyInIndia ['applicationForms'] as $key => $value ) {
									?>
					<li><a
						href="<?php echo $value['url']?>"><?php echo $value['title']?></a></li>
				<?php } ?>
                    </ul>
                 </div>
                 <p><strong>Career Central</strong></p>
                 <div class="layer-items clear-width">
                    <ul>
                      				<?php
				foreach ( $studyInIndia ['careerCentral'] as $key => $value ) {
					?>
					<li><a
						href="<?php echo $value['url']?>"><?php echo $value['title']?></a></li>
				<?php } ?>
                    </ul>
                 </div>
            </div>
        </div>
</div>
