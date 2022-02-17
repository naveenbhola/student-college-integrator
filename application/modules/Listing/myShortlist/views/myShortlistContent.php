<?php
$elementProperties = '';
if(count($shortlistedCourses) >0) {
    $elementProperties = "style='margin-bottom:20px;'";
} 
?>
<style>.disabled {pointer-events: none;cursor: default;}</style>  <!--// this is used only for ask btn-->

<?php if(count($shortlistedCourses) > 0 && $validateuser !== 'false'){//Dont load view
			}
	else{ ?>
		<div class="shortlist-content" <?php echo $elementProperties;?>>
		<?php $this->load->view('myShortlist/aboutShortlistSec');?>
        </div>
<?php	} ?>
<div id="shrtlstdInsttsCont">
    <?php if(count($shortlistedCourses) >0) { ?>
    <?php
        $this->load->view('myShortlist/shortlistedInstitutes'); ?>
    <?php } ?>
</div>
<?php if(count($shortlistedCourses) >0) { ?>
<div class="shortlist-content">
<?php } ?>
<?php $this->load->view('myShortlist/startShortlistSec'); ?>
<?php
    echo "<div class='or-sec' style='display:none;'><div class='or-text'>Or</div></div>";
    echo "<div id='recommendation-section' style='display:none;'>";
    //$this->load->view('myShortlist/recommendedShortlistSec', array("courseObject" => $recommendedCourses, "isRecommendationsFlag" => 1));
    echo "</div>";
?>
</div>