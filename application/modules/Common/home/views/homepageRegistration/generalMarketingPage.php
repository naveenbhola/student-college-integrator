<style>
.cssSprite{
background:url(/public/images/crossImg_14_12.gif) no-repeat;
};
.quesAnsBullets{
background-image: none;
};

</style>
<script>var currentPageName = 'MARKETING_PAGE';</script>
<div style="margin: 0 auto;">
			<!--Start_Right-->
            	
                	<!--Start_Form-->

                    	<div class="find-inst-form-SA">
						<?php $this->load->view('home/homepageRegistration/genralMarketingPageForm');?>
                        </div>

                    <!--End_Form-->                    
                
        	<!--End_Right-->
            <div style="clear:left;font-size:1px;line-height:1px">&nbsp;</div>
                            <?php 
								if(isset($pagename) && $pagename=="studyAbroad") {									
									$this->load->view('marketing/studyCountryOverlay');
								} else {
									$this->load->view('marketing/marketingCityOverlay');
								}
                            ?>
<div class="clear_L"></div>
<div class="lineSpace_10">&nbsp;</div>
<div id="emptyDiv" style="display:none;">&nbsp;</div>
</div>
<script>
    var userCity = "";
    <?php if($logged == "Yes") {?>
        userCity = "<?php echo $userData[0]['city']?>";
        <?php }?>
//        categoryList = eval(<?php echo json_encode($allCategories);?>);
        var isLogged = '<?php echo $logged; ?>';
</script>
