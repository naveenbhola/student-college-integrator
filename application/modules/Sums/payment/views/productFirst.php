<?php 
error_log_shiksha("LOGGGGGGGGGGGGGGEEEEEEEEEDDDDDDDDDD = ".$logged);
echo '<div id="Page1" style="display:inline; float:left; width:100%">
			<div class="raised_lgraynoBG"> 
				<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
				<div class="boxcontent_lgraynoBG">
					<div class="lineSpace_10">&nbsp;</div>';
                    if($logged == "No") {
                    echo '
            					<!--<div class="mar_left_10p mar_right_30p">
            						<div class="raised_skyn">
            								<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
            								<div class="boxcontent_skyn">
            								  <div class="h22 raisedbg_sky normaltxt_11p_blk fontSize_13p bld"><img id="loginImg1" src="/public/images/arrowOrgange.gif" width="18" height="18" align="absmiddle" class="mar_left_10p" onclick="toggleLoginPanel1();" style="cursor: pointer"/> Already Have A Product<span id="wrongUser1" style="display:none"> Wrong User Name Or Password</span><span id="empty1" style="display:none"> Please Enter Email And Password</span><br></div>
            									<div id="loginPanel1" style="display:none">
            									  <div class="lineSpace_10">&nbsp;</div>
            									  <div class="row">
            										  <div class="mar_left_20p normaltxt_11p_blk float_L" style="position:relative; top:2px">Email Id: <input id="Email1" type="text" />	&nbsp; &nbsp; Password: <input id="Passwd1" type="password" /></div>
             										  <div class="buttr2">
            												<button class="btn-submit11 w9" value="" type="button" onclick="loginAndCheck()">
            													<div class="btn-submit11"><p class="btn-submit12">Login &amp; Continue</p></div>
            												</button>
            										  </div>
            										  <span class="pos_t12">&nbsp;<a href="#">Forgot Password</a></span>
            										  <div class="clear_L"></div>
            									  </div>
            									</div>
            								</div>
            								<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
            						</div>
						     </div>-->';
                    }
                    echo '
            		<div class="lineSpace_10">&nbsp;</div>

					<div class="normaltxt_11p_blk fontSize_13p" style="margin-bottom:10px"><span class="mar_left_10p">There are following types of listing products:</span></div>
					<div class="grayLine"></div>
					<!--Start_Header_Title-->
					<div class="row">					
						<div class="normaltxt_11p_blk bld">
							<div class="float_L mar_top_4p OrgangeFont w17_per"><span class="mar_left_10p">Listing Pack</span></div>
							<div class="float_L mar_top_4p OrgangeFont w12_per"><span>No of listings</span></div>
							<div class="float_L mar_top_4p OrgangeFont w12_per"><span>Duration</span></div>
							<div class="float_L mar_top_4p OrgangeFont w15_per"><span>Media content</span></div>
							<div class="float_L mar_top_4p OrgangeFont w15_per"><span>Featured Logo</span></div>
							<div class="float_L mar_top_4p OrgangeFont w15_per"><span>Featured Presence</span></div>
							<div class="float_L mar_top_4p OrgangeFont w12_per"><span class="pd_left_5p">Price</span></div>						
						</div>
					</div>
					<div class="row">					
						<div class="normaltxt_11p_blk bld">
							<div class="float_L w17_per"><span>&nbsp;</span></div>
							<div class="float_L w12_per"><span>&nbsp;</span></div>
							<div class="float_L w12_per"><span class="fontSize_9p grayFont nbld">(in months)</span></div>
							<div class="float_L w15_per"><span>&nbsp;</span></div>
							<div class="float_L w15_per"><span>&nbsp;</span></div>
							<div class="float_L w15_per"><span>&nbsp;</span></div>
							<div class="float_L w12_per"><span>&nbsp;</span></div>						
						</div>
						<br clear="left" style="height:1px" />
					</div>
					<div class="lineSpace_1">&nbsp;</div>
					<div class="grayLine"></div>
					<!--End_Header_Title-->';
                    //print_r($productDataList);
                    for($i = 0; $i < count($productDataList); $i++) {
                    error_log_shiksha(print_r($productDataList,true));
                    $flagGold = "";
                    if($productDataList[$i]["ProductName"] == "GOLD 1") { 
                        $productDataList[$i]["ProductName"] = "GOLD";
                        $flagGold = "true";
                        $start = $i;
                    }
				        echo '
					<!--Data_Container_Platinum-->
					<div class="applyBg bgcolor_pay_green">
							<!--Start_Details_row1-->
							<div class="row">
								<div class="lineSpace_5">&nbsp;</div>					
								<div class="normaltxt_11p_blk">
									<div class="float_L w17_per bld"><span class="mar_left_10p"><img src="/public/images/listingpack.gif" width="23" height="14" align="absmiddle" />'.$productDataList[$i]["ProductName"].'</span></div>
									';
                                    if($flagGold == "true"){
                                        echo '<div class="float_L w12_per"><span><select id="goldTypeSelect" onchange="changeVal()">';
                                        $isGold = "true";
                                        while($isGold=="true"){
                                            echo '<option id="'.$productDataList[$i]["Property"]["Num"].'" dur="'.$productDataList[$i]["Property"]["Duration"].'" price="Rs. '.$productDataList[$i]["Price"].'" butVal="'.($i+1).'">'.$productDataList[$i]["Property"]["Num"].'</option>';
                                            $i++;
                                            if($i >= count($productDataList)) {
                                                break;
                                            }
                                            error_log_shiksha("1234");
                                            error_log_shiksha("1234".substr($productDataList[$i]["ProductName"],0,4));
                                            error_log_shiksha("11111111111".$i);
                                            if(substr($productDataList[$i]["ProductName"],0,4) == "GOLD") {
                                                $isGold = "true";
                                            }else {
                                                $isGold = "false";
                                                $i--;
                                            }
                                            
                                        }
                                        echo '</select></span></div>';
                                        echo '<div class="float_L w12_per"><span id="duration">'.$productDataList[$start]["Property"]["Duration"].'</span></div>';
                                    }else {
                                        echo '<div class="float_L w12_per"><span>'.$productDataList[$i]["Property"]["Num"].'</span></div>
                                            <div class="float_L w12_per"><span>'.$productDataList[$i]["Property"]["Duration"].'</span></div>';
                                    }

                                    echo '<div class="float_L w15_per">';
                                    if((isset($productDataList[$i]["Property"]["M_Presentation"]))||(isset($productDataList[$i]["Property"]["M_Photos"]))||(isset($productDataList[$i]["Property"]["M_Video"]))) {
                                    echo '<span>Upload</span><br />';
                                        if(isset($productDataList[$i]["Property"]["M_Presentation"])){ echo '<span><img src="/public/images/smallBullets.gif" width="11" height="8" align="absmiddle" />Documents('.$productDataList[$i]["Property"]["M_Presentation"].')</span><br />';}
                                        if(isset($productDataList[$i]["Property"]["M_Photos"])){ echo '<span><img src="/public/images/smallBullets.gif" width="11" height="8" align="absmiddle" />Images('.$productDataList[$i]["Property"]["M_Photos"].')</span><br />';}
                                        if(isset($productDataList[$i]["Property"]["M_Video"])){ echo '<span><img src="/public/images/smallBullets.gif" width="11" height="8" align="absmiddle" />Videos</span>';}
                                    }else {
                                        echo '&nbsp;';
                                    }
									echo '</div>';
                                    echo '<div class="float_L w15_per">';
                                    if(isset($productDataList[$i]["Property"]["Featured Logo"])){
									echo '<span class="mar_right_10p">';
                                         echo $productDataList[$i]["Property"]["Featured Logo"];
                                        echo '</span>';
                                    }else {
                                        echo '&nbsp;';
                                    }
                                    echo '

									</div>
                                    <div class="float_L w15_per">';
                                        if(isset($productDataList[$i]["Property"]["Featured Presence"])){ 
                                        echo '<span class="pd_Right_10p">';
                                        echo $productDataList[$i]["Property"]["Featured Presence"];
                                        echo '</span>';
                                        }else {
                                            echo '&nbsp;';
                                        }
                                        echo '</div>
									<div class="float_L w12_per">
										<span>';
                                        if(isset($productDataList[$i]["Price"])){ 
                                            if($flagGold == "true"){
                                                echo "<div>Email <a href=\"mailto:support@shiksha.com\">support@shiksha.com</a> for purchasing this product</div>"; // Temp change FIXME
                                                echo "<div id='priceGold' style='display:none;'>Rs. ".$productDataList[$start]["Price"]."</div>"; // Temp change FIXME
                                            }else {
                                                if($productDataList[$i]["Price"] == 0) {echo "Free";} else {echo "Rs. ".$productDataList[$i]["Price"];}
                                            }
                                        }

                                        echo '</span><br />';
                                        if($productDataList[$i]["Price"] == 0) {
echo ' <!--
										<span>
											<div class="buttr3" >
												<button id="buttonValGold" class="btn-submit13 w12" value="" type="button" onclick=\'window.location="/payment/paymentFree"\'>
													<div class="btn-submit13"><p class="btn-submit14 btnTxtBlog">Post</p></div>
												</button>
											</div>
											<div class="clear_L"></div>
                                                                                    </span>					-->				';
                                                                                    //FIXME Temp changes
                                                                                    if( (sizeof($transactHistory)>0 && $myProducts['Bronze']['remaining']>0) || (sizeof($transactHistory)==0 && $myProducts['Bronze']['remaining']==0) ){
                                                                                    echo "
                                                                                    <div style='cursor:pointer;'><a onClick='window.location=\"/payment/paymentFree/index/course\"' >Add Course-College</a><br/>
                                                                                        <a onClick='window.location=\"/payment/paymentFree/index/scholarship\"' >Add Scholarship</a><br/>
                                                                                        <a onClick='window.location=\"/payment/paymentFree/index/notification\"' >Add Notification</a></div>
                                                                                    ";
                                                                                }
                                        }else {

                                            echo '
                                                <span style="display:none;">
                                                    <div class="buttr3" >
                                                <button id="buttonValGold" class="btn-submit13 w12" value="" type="button" onclick="submitSelect(';
                                            if($flagGold == "true") {
                                                echo ($start+1);
                                            }else {
                                                echo ($i+1);
                                            }
                                            echo ')">
                                                <div class="btn-submit13"><p class="btn-submit14 btnTxtBlog">Buy Now</p></div>
                                            </button>
                                                </div>
                                                <div class="clear_L"></div>
                                                </span>';
                                        }
                                        echo '
									</div>
								</div>
								<div class="clear_L"></div>
							</div>
 							<!--End_Details_row1-->
							<div class="lineSpace_5">&nbsp;</div>
					</div>
					<!--End_Date_Container_Platinum-->
                    ';
                    if($flagGold == "true") {
                        $flagGold = false;
                    }
                    $i++;
                    if($i < count($productDataList)) {
                    error_log_shiksha("Iiiiiiiiiiiiiiiiiii = ".$i);
                    echo '
					<div class="lineSpace_5">&nbsp;</div>

					<!--Data_Container_Gold-->
					<div>
							<!--Start_Details_row1-->
							<div class="row">
								<div class="lineSpace_5">&nbsp;</div>					
								<div class="normaltxt_11p_blk">
									<div class="float_L w17_per bld"><span class="mar_left_10p"><img src="/public/images/listingpack.gif" width="23" height="14" align="absmiddle" />'.$productDataList[$i]["ProductName"].'</span></div>
									<div class="float_L w12_per"><span>'.$productDataList[$i]["Property"]["Num"].'</span></div>
									<div class="float_L w12_per"><span>'.$productDataList[$i]["Property"]["Duration"].'</span></div>';
                                    echo '<div class="float_L w15_per">';
                                    if((isset($productDataList[$i]["Property"]["M_Presentation"]))||(isset($productDataList[$i]["Property"]["M_Photos"]))||(isset($productDataList[$i]["Property"]["M_Video"]))) {
                                    echo '<span>Upload</span><br />';
                                        if(isset($productDataList[$i]["Property"]["M_Presentation"])){ echo '<span><img src="/public/images/smallBullets.gif" width="11" height="8" align="absmiddle" />Documents('.$productDataList[$i]["Property"]["M_Presentation"].')</span><br />';}
                                        if(isset($productDataList[$i]["Property"]["M_Photos"])){ echo '<span><img src="/public/images/smallBullets.gif" width="11" height="8" align="absmiddle" />Images('.$productDataList[$i]["Property"]["M_Photos"].')</span><br />';}
                                        if(isset($productDataList[$i]["Property"]["M_Video"])){ echo '<span><img src="/public/images/smallBullets.gif" width="11" height="8" align="absmiddle" />Videos</span>';}
                                    }else {
                                        echo '&nbsp;';
                                    }

									echo '</div>';
echo '<div class="float_L w15_per">';
                                    if(isset($productDataList[$i]["Property"]["Featured Logo"])){
									echo '<span class="mar_right_10p">';
                                         echo $productDataList[$i]["Property"]["Featured Logo"];
                                        echo '</span>';
                                    }else {
                                        echo '&nbsp;';
                                    }
                                    echo '

									</div>
									<div class="float_L w15_per">';
                                        if(isset($productDataList[$i]["Property"]["Featured Presence"])){ 
                                        echo '<span class="pd_Right_10p">';
                                        echo $productDataList[$i]["Property"]["Featured Presence"];
                                        echo '</span>';
                                        }else {
                                            echo '&nbsp;';
                                        }
                                        echo '</div>
									<div class="float_L w12_per">
										<span>';
                                        //if(isset($productDataList[$i]["Price"])){ if($productDataList[$i]["Price"] == 0) {echo "Free";} else {echo "Rs. ".$productDataList[$i]["Price"];}} // Temp FIXME
                                        if(isset($productDataList[$i]["Price"])){ if($productDataList[$i]["Price"] == 0) {echo "Email <a href=\"mailto:support@shiksha.com\">support@shiksha.com</a> for purchasing this product";} else {echo "Email <a href=\"mailto:support@shiksha.com\">support@shiksha.com</a> for purchasing this product";}}

echo '</span><br />';
                                        if($productDataList[$i]["Price"] == 0) {
echo '<!--
										<span>
											<div class="buttr3" >
												<button id="buttonValGold" class="btn-submit13 w12" value="" type="button" onclick=\'window.location="/payment/paymentFree"\'>
													<div class="btn-submit13"><p class="btn-submit14 btnTxtBlog">Post</p></div>
												</button>
											</div>
											<div class="clear_L"></div>
                                                                                    </span>						-->			';
                                        }else {

                                            echo '
                                                <span>
                                                <!--<div class="buttr3" >
                                                <button id="buttonValGold" class="btn-submit13 w12" value="" type="button" onclick="submitSelect(';
                                            if($flagGold == "true") {
                                                echo ($start+1);
                                            }else {
                                                echo ($i+1);
                                            }
                                            echo ')">
                                                <div class="btn-submit13"><p class="btn-submit14 btnTxtBlog">Buy Now</p></div>
                                                </button>
					     </div>-->
                                                <div class="clear_L"></div>
                                                </span>';
                                        }
                                        echo '
									</div>
								</div>






											<div class="clear_L"></div>
										</span>										
									</div>
								</div>
								<div class="clear_L"></div>
							</div>
 							<!--End_Details_row1-->
							<div class="lineSpace_5">&nbsp;</div>
					</div>

					<!--End_Date_Container_Gold-->
                    ';
					echo '<div class="lineSpace_5">&nbsp;</div>';
                    }
                    }


					
		echo '<div class="lineSpace_20">&nbsp;</div>
				</div>
				<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>				
			</div>	
	</div>
        ';

    ?>
    <div class="normaltxt_11p_blk bld">
        <button class="btn-submit7 w9" id="goBkEnterp" value="Go_Back_Enterprise" type="button" onClick="window.location='<?php echo site_url().'/enterprise/Enterprise'; ?>'" style="width:125px">
            <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Go Back</p></div>
        </button>
    </div>
<div style="line-height:150px">&nbsp;</div>
