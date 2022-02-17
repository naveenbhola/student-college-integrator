<div class="coachmark-bgLayer" style="display:none;">
   	<div class="countryHome-coachmark">
	        <div>
		    	<div class="cntry-tabs cmark-homeNav">
			    	<ul>
			        	<?php foreach($courseURLs as $courseId => $url){ ?>
				    		<li <?php if($ldbCourseID == $courseId){?>class="active"<?php } ?>><a href="<?php echo $url; ?>"><span class="inner-circle"><i class="study-sprite c<?php echo $courseId; ?>-icn"></i></span><p><?php echo $courseNames[$courseId]?></p></a></li>
				    	<?php } ?>
			        </ul>
	                </div>
    	    		<p class="select-text"><i class="study-sprite coach-arrw"></i>Tap to change course</p>
		</div>
        	<div style="margin:0 auto; text-align:center;  position:fixed; bottom:50px; display:block; left:0; right:0;">
	        	<a href="#" class="Ok-btn">OK</a>
	        </div>
	</div>
</div>
<?php
// if we need to add other elements having data-role = page it can be passed as below
    $footerComponents = array(
    	'commonJSV2'=>true,
		'pages'=>array(),
		'js' => array('countryHomePageSA'),
		'trackingPageKeyIdForReg' => 701
    );
    $this->load->view('commonModule/footerV2',$footerComponents);
?>
<script>
var coursesOnPage = <?php echo json_encode($coursesOnPage);?>;
</script>