							<!--Start_Find_And_Browse_Articles_Box-->
                            <div style="width:100%">
                            	<div class="shik_skyBorder">                                	
                                    <div class="shik_roundCornerHeaderSpirit shik_skyGradient"><span class="Fnt16" style="padding-left:10px"><b>Articles in <?php echo $categoryData['displayName'];?></b></span></div>
                                    <div class="marfull_LeftRight10">
                                        <!--Start_Articles_in_Management-->
                                        <div style="width:100%">
                                            
                                            <div style="padding:10px 0 5px 0"><div class="homeShik_Icon home_FeaturedTab"><span class="blackColor">Find Articles</span></div></div>
                                            <div style="width:100%">
                                                <div class="float_R" style="width:145px">
                                                    <div style="width:100%">
                                                        <div style="margin-top:2px">
<!--                                                        <input type="button" class="spirit_header homeShik_FindBtn" value="" />-->
                                                        <input class="spirit_header homeShik_FindBtn" type="button" onclick="if(trim(document.getElementById('blogKeyword').value) != 'Enter keyword to search in articles' && trim(document.getElementById('blogKeyword').value) != '') {searchInSubProperty(document.getElementById('blogKeyword').value,'blog','');} else{ document.getElementById('kumkumSearchPanel_error').style.display = ''; return false; } " value="Find"/>


</div>

                                                    </div>
                                                </div>
                                                <div style="margin-right:155px">
                                                    <div class="float_L" style="width:100%">
                                                        <div style="margin-left:15px">
                                                            <div style="width:100%">
                                                                <div class="homeShik_textBoxBorder">
<!--                                                                    <input type="text" class="homeShik_searchtextBox" value="Enter keyword  to search in articles" />-->
<input id="blogKeyword" class="homeShik_searchtextBox" type="text" onblur="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" onkeyup="if((event.keyCode == 13) && (trim(this.value)!='')) searchInSubProperty(document.getElementById('blogKeyword').value,'blog','');" default="Enter keyword to search in articles" value="Enter keyword to search in articles" style="color:#ADA6AD;font-size:12px;font-family:arial" name="blogKeyword"/>
							<div class="errorMsg" style="display:none" id="kumkumSearchPanel_error">Please enter some keyword.</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="clear_B">&nbsp;</div>
                                            </div>
                                            <div style="border-top:1px solid #f1f1f1;margin:14px 0  4px 0">&nbsp;</div>                        
                                        </div>
                                        <!--End_Articles_in_Management-->
                    
                                        <!--Start_Browse_Articles-->
                                        <div style="width:100%">
                                            <div style="padding-bottom:6px"><div class="homeShik_Icon home_FeaturedTab"><span class="blackColor">Browse Career Articles</span></div></div>
                                            <div style="margin-left:25px">
                                                <div style="width:100%">                    	
                                        <?php for($i = 0;$i<count($blogs['results']);$i++) { ?>
                                                    <div style="padding-bottom:9px">
                                                        <div style="padding-bottom:1px"><a href="<?php echo $blogs['results'][$i]['url']?>"><?php echo $blogs['results'][$i]['blogTitle'];?></a></div>
                                                        <div style="color:#5d5d5d"><?php echo substr(strip_tags($blogs['results'][$i]['blogText']),0,180);?> ...<a href="<?php echo $blogs['results'][$i]['url']?>">more</a></div>
                                                    </div>
                                        <?php } ?>
                                                </div>                                                
                                            </div>
                                            <!--SHow All -->
<?php
                            $urlParams = '';
                            if($categoryId > 1) {
                                $urlParams .= 'category='. $categoryId;
                            }
                            if($urlParams != '') {$urlParams .='&';}
                            if($countryId > 1 && strpos($countryId, ',')=== false) {
                                $urlParams .= 'country='. $countryId;
                            }
                    ?>
                                            <div style="padding:15px 0 15px 15px"><a href="<?php echo SHIKSHA_HOME;?>/blogs/shikshaBlog/showArticlesList?<?php echo $urlParams .'&c='. rand(); ?>"><b>View All Articles</b></a></div>
                    <?php
                        ?>

                                            <!-- Show All -->
                                        </div>
                                        <!--End_Browse_Articles-->                                                                        
