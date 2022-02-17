                    <!--Start_Articles_in_Management-->
                    <div style="width:100%">
                    	<div class="Fnt15" style="padding:1px 0 1px 0"><b>Expert Speak by Kumkum Tandon</b>&nbsp; <a href="/shikshaHelp/ShikshaHelp/kumkum" title="Kum Kum View Profile" class="Fnt12">View Profile</a></div>
                        <div style="font-size:1px;border-bottom:2px solid #c5e3fd;margin-bottom:11px">&nbsp;</div>
                        <div style="padding:2px 0 5px 0"><div class="shikIcons home_FeaturedTab"><span class="blackColor">Find Articles</span></div></div>
                        <div style="width:100%">
                            <div class="float_R" style="width:100px">
                            	<div style="width:100%">
                                	<div style="margin-top:2px"><input type="button" class="spirit_header homeShik_FindBtn" value="Find" onclick="checkTextElementOnTransition(document.getElementById('blogKeyword'),'focus');if(trim(document.getElementById('blogKeyword').value) != 'Search In Kum Kum\'s Articles' && trim(document.getElementById('blogKeyword').value) != '') {searchInSubProperty(document.getElementById('blogKeyword').value,'blog','');} else{ document.getElementById('kumkumSearchPanel_error').style.display = ''; return false; } " /></div>
                                </div>
                            </div>
                            <div style="margin-right:110px">
                                <div class="float_L" style="width:100%">
                                    <div style="margin-left:15px">
                                        <div style="width:100%">
                                            <div class="homeShik_textBoxBorder">
                                                <input id="blogKeyword" name="blogKeyword"  type="text" style="color:#AdA6Ad" class="homeShik_searchtextBox" value="Enter keyword  to search in articles" default="Enter keyword  to search in articles" onkeyup="if((event.keyCode == 13) && (trim(this.value)!='')) searchInSubProperty(document.getElementById('blogKeyword').value,'blog','');" onfocus="checkTextElementOnTransition(this,'focus')" onblur="checkTextElementOnTransition(this,'blur')"/>
                                            </div>
							                <div class="errorMsg" style="display:none" id="kumkumSearchPanel_error">Please enter some keyword.</div>
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
                        <div style="padding-bottom:6px"><div class="shikIcons home_FeaturedTab"><span class="blackColor">Browse Articles</span></div></div>
                        <div style="margin-left:25px">
                        	<div style="width:100%" id="articlesContainer">
                                <?php
                                    foreach($articles as $article) {
                                        $articleTitle = $article['blogTitle'];
                                        $articleUrl = $article['url'];
                                        $articleSummary = $article['summary'];
                                        $articleDescription = $article['blogText'];
                                        $snippet = trim($articleSummary) != '' ? $articleSummary : $articleDescription ;
                                ?>
                            	<div style="padding-bottom:9px">
                                	<div style="padding-bottom:1px"><a href="<?php echo $articleUrl; ?>" title="<?php echo $articleTitle; ?>"><?php echo $articleTitle; ?></a></div>
                                    <div style="color:#5d5d5d"><?php echo substr(strip_tags($snippet), 0, 250); ?> ...<a href="<?php echo $articleUrl; ?>">more</a></div>
                                </div>
                                <?php
                                    }
                                ?>
                            </div>
						    <div><a href="/blogs/shikshaBlog/showArticlesList?type=kumkum&c=<?php echo rand(); ?>"  title="View All Kum Kum Tandon Articles"><b>View All Articles »</b></a></div>
                        </div>
                    </div>
                    <!--End_Browse_Articles-->                    
