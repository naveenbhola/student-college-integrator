<!--Start_Mid_Panel-->
<div id="mid_Panel_noRpanel" style="margin-left:0px;margin-right:275px">
	<div style="float:left; width:100%;">
		<div class="raised_lgraynoBG"> 
			<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
			<div class="boxcontent_lgraynoBG">
					<div class="lineSpace_10">&nbsp;</div>
					<div>
				        <div class="mar_right_265p normaltxt_11p_blk" style="margin-right:360px">
                            <div class="fontSize_16p float_L">
						        <strong class="OrgangeFont mar_left_10p">
                                <?php 
                                switch($details['status']){
                                    case 'expired':
                                        echo "This Listing has expired.";
                                        break;
                                    case 'deleted':
                                        echo "This Listing has been deleted.";
                                        break;
                                    case 'cancelled':
                                        echo "This listing has been cancelled";
                                        break;
                                    default:
                                        echo "This Listing does not exist any more.";
                                        break;
                                }
                                ?>
                                
                                </strong>
					        </div>
							<div style="clear:left;line-height:1px;font-size:1px">&nbsp;</div>
						    <div class="lineSpace_5">&nbsp;</div>
                        </div>
                    </div>
                    <div class="lineSpace_10">&nbsp;</div>
			    </div>
    			<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
	    	</div>		
		    <div class="lineSpace_11">&nbsp;</div>
    	</div>	
    </div>
<div style="clear:left;line-height:1px;font-size:1px">&nbsp;</div>
	<!--End_Mid_Panel-->
