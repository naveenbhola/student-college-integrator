<script>
coursesLeft = <?php echo $totalNoOfCourses; ?> </script>

<div id="connect-wrapp-widget" class="clear-width">
    <div class="callout-box">
    <div id = "coursesTuple">
    <input type="hidden" id="coursesleft" value="<?php echo $totalNoOfCourses -$count ;?>" />
        <div class="discussion-head">
            <i class="connect-icon"></i>
            <div class="connect-title">
                <h2>Campus Connect</h2>
                <?php if($isCampusPresent=='true'){ ?>
		     <p>Current students of this institute are here to answer your questions. You can view their profile below. </p>
		<?php } ?>
            </div>
        </div>
	  <?php  if(count($coursesData)>0){  echo '<ul id="coursesTuples" class="current-students">';

          $this->load->view('CA/caTuplesInnerWidget');?>
	
        </ul>
<?php
    }
?>


    </div>
<?php if($totalNoOfCourses > $count){   ?>
  <div class="btn-viewall">
	<a id ="viewMoreLink" href="javascript::void(0)" class="font-15" onclick=" showNextFourtuples('<?php echo $instituteId;?>','<?php echo $count;?>','<?php echo $courseIdToExclude; ?>','<?php echo $currentLocationId; ?>');">View more
	</a>
</div>
	<?php } ?>
        <div class="clearFix"></div>
    </div>

</div>
