	<div style="margin:0">
        <div class="float_L" style="width:692px">
            <div class="wdh100">
                <div> <?php $this->load->view('blogs/articlesList'); ?> </div>
                
                <div align="right" class="lineSpace_25" style="margin-bottom:3px;">
		    	    <span>
			            <span class="pagingID" id="paginataionPlace2"><?php echo preg_replace('/\/0\/20|\/0\/50/','',$paginationHTML);?></span>
				    </span>
				    <span class="normaltxt_11p_blk bld pd_Right_6p" align="right" id="countOffset_DD2"></span>
    				<!--<span class="normaltxt_11p_blk bld pd_Right_6p" >View: 
	    			    <select class="selectTxt" name="countOffset" id="countOffset_DD2" onChange= "updateCountOffset(this,'startOffset','countOffset');" style="display:<?php echo $totalArticles > 10 ?'inline' : 'none'; ?>">
		    			    <option value="10">10</option>
			    			<option value="15">15</option>
				    		<option value="20" selected>20</option>
					    	<option value="25">25</option>
    					</select>
	    			</span>-->
                </div>
            </div>
        </div>
        <div class="float_R"  style="width:265px">
            <div class="wdh100">
                <?php
                    $bannerProperties = array('pageId'=>'ARTICLES_LIST', 'pageZone'=>'SIDE');
                	$this->load->view('common/banner', $bannerProperties);  
                 ?>
                 <div class="lineSpace_10">&nbsp;</div>
                        <!--Start_AreaOfInterest-->                        
                        <div>
							<?php $this->load->view('listing_forms/widgetConnectInstitute'); ?>                            
                        </div>
                        <!--End_AreaOfInterest-->                       
                        <!--Start_Related_Articles-->
                 <div class="lineSpace_10">&nbsp;</div>
                        <!--Start_google_Banner-->
                        <div>
							<?php
							$bannerProperties = array('pageId'=>'ARTICLE_DETAILS', 'pageZone'=>'SIDEBANNER');
							$this->load->view('common/banner', $bannerProperties);
							?>
						</div>
                        <!--End_google_Banner-->                               

            </div>
        </div>
        <div class="clear_B">&nbsp;</div>
        <div class="lineSpace_15">&nbsp;</div>
    </div>

