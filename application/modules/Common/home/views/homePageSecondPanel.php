    <div class="marfull_LeftRight10">
    	<div style="width:100%">
        	<div class="float_L" style="width:450px">
            	<div style="width:100%">
                	<!--Start_Form-->
                    <?php $this->load->view('home/homePageLeftLeadForm'); ?>
                    <!--End_Form-->
                    <div class="defaultAdd" style="height:28px">&nbsp;</div>
                    
                    <!--Start_Flavor_of_the_Month-->
                    <?php $this->load->view('home/homePageLeftFlavorPanel'); ?>
                    <!--End_Flavor_of_the_Month-->
		    
		    <!--Start Latest Update-->
		    <?php $this->load->view('home/homePageLeftLatestUpdatesPanel'); ?>	
		    <!--End Latest Update -->			
                </div>
            </div>
            <div style="margin-left:470px">
            	<div class="float_L" style="width:100%">
                	<!--Start_Ask_And_Answer-->
		    <!-- Modified by Ankur for Homepage-Rehash -->
                    <?php $this->load->view('home/homePageRightPanelCafe',array('categoryId' => $defaultCategoryIdForGlobalAnAWidget,'percentageOfPage' =>0.503,'title' => 'Ask &amp; Answer','showCategorySelection' =>true,'pageKeyInfo'=>'HOME_HOMEPAGE_RIGHTPANEL_')); ?>
                    <!--End_Ask_And_Answer-->

                    <!--End_Ask_And_Answer-->
                    <div class="defaultAdd" style="height:28px">&nbsp;</div>
                    <!--Start_Articles-->
					<?php $this->load->view('home/homePageRightSpotLight');?>
                    <?php $this->load->view('home/homePageRightArticlesPanel');?>
                    <!--End_Articles-->
                    <!--<div class="defaultAdd" style="line-height:32px">&nbsp;</div>-->
                </div>
            </div>
            <div class="clear_L">&nbsp;</div>
        </div>
    </div>

