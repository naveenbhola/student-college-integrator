<?php
    $featuredTitle = ((isset($acronym) && $acronym != '') ? $acronym : $blogTitle );
    $criteriaArray = array(
            'category' => '',
            'country' => '',
            'city' => '',
            'keyword'=> $featuredTitle
            );
?>
<div class="raised_lgraynoBG">
    <b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
    <div class="boxcontent_lgraynoBG">
        <div class="pd_lft_rgt">
            <div class="OrgangeFont bld fontSize_13p"><?php echo $featuredTitle; ?> Preparation Institutes</div>
		</div>
		<div class="lineSpace_10">&nbsp;</div>
	    <div class="mar_full_10p">
           <div class="float_R">
            <?php
    	        $bannerProperties = array('pageId'=>'CATEGORY', 'pageZone'=>$categoryData['page'] .'_SIDE1', 'shikshaCriteria' => $criteriaArray);
                $this->load->view('common/banner', $bannerProperties);
            ?>
           </div>
           <div>
            <?php
    	        $bannerProperties = array('pageId'=>'CATEGORY', 'pageZone'=>$categoryData['page'] .'_SIDE2', 'shikshaCriteria' => $criteriaArray);
                $this->load->view('common/banner', $bannerProperties);
            ?>
           </div>
		</div>
	</div>
  	<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
</div>

