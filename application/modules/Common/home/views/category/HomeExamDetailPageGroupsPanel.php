<div class="raised_lgraynoBG">
    <b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
        <div class="boxcontent_lgraynoBG">					
	        <div class="mar_full_10p">
		        <div class="lineSpace_10">&nbsp;</div>
			    <ul class="stayConnect">
				    <li>
					    <div class="OrgangeFont bld fontSize_13p">Stay Connected with Students Preparation for <?php echo $blogTitle ;?></div>
						<div class="lineSpace_10">&nbsp;</div>
				    </li>
		        </ul>
					    <div align="center">
                            <?php
                                 $groupUrl = getSeoUrl($blogId,"collegegroup",$blogTitle) .'/1/TestPreparation';
                                 $joinBtnText = $acronym == '' ? $blogTitle : $acronym ;
                            ?>
						    <button class="btn-submit13" type="submit" onclick="location.replace('<?php echo $groupUrl; ?>');">
							    <div class="btn-submit13"><p class="btn-submit14 btnTxtBlog">Join <?php echo substr($joinBtnText, 0, strpos( $joinBtnText, ' ')); ?> Test Prep group</p></div>
							 </button>
					    </div>								

			<div class="lineSpace_10">&nbsp;</div>
		</div>
	</div>
    <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
</div>

