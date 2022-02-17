<div style="width:295px; float:left">
	<div class="raised_career">
		<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
			<div class="boxcontent_career fontSize_12p">			
				<div class="lineSpace_4">&nbsp;</div>
				<div class="mar_full_10p">
                    <div class="inline" style="float:right; margin-right:10px;"><a href="/shikshaHelp/ShikshaHelp/kumkum" title="Kum Kum View Profile">View Profile</a></div>
                    <div class="inline-l bld OrgangeFont fonstSize_13p">Kum Kum  Tandon's Section</div>
                    <div class="clear_R"></div>
                </div>
				<div class="lineSpace_3">&nbsp;</div>
				<div class="mar_left_10p fontSize_10p">India's pioneering career counsellor </div>
				<div class="lineSpace_10">&nbsp;</div>
				<div class="mar_left_10p">
					<div class="bld">Search Articles</div>
					<div class="lineSpace_5">&nbsp;</div>
					<div>
							<input id="blogKeyword" name="blogKeyword" style="color:#acacac;" onfocus="if(trim(this.value) =='Search In Kum Kum\'s Articles') this.value = '';this.style.color='#000';" onblur="if(this.value =='') { this.value='Search In Kum Kum\'s Articles';this.style.color='#acacac';}" onkeyup="if((event.keyCode == 13) && (trim(this.value)!='')) searchInSubProperty(document.getElementById('blogKeyword').value,'blog','');" type="text" value="Search In Kum Kum's Articles" size="22"/>&nbsp;
							<button onclick="if(trim(document.getElementById('blogKeyword').value) != 'Search In Kum Kum\'s Articles' && trim(document.getElementById('blogKeyword').value) != '') {searchInSubProperty(document.getElementById('blogKeyword').value,'blog','');} else{ document.getElementById('kumkumSearchPanel_error').style.display = ''; return false; } " type="submit" value="" class="smallSearchBtn" style="position:relative;*top:-1px;*left:3px">Search
							</button>
							<div class="errorMsg" style="display:none" id="kumkumSearchPanel_error">Please enter some keyword.</div>
					</div>
				</div>
				<?php
					if(count($blogs) > 0) {
				?>
				<div class="lineSpace_15">&nbsp;</div>
				<div class="mar_full_10p">
					<div class="bld">Browse Articles</div>
					<div class="lineSpace_5">&nbsp;</div>
					<div>
					<?php
						foreach($blogs as $blog) {
							$blogTitle = $blog['blogTitle'];
							$displayBlogTitle = $blogTitle;
							$blogUrl = $blog['url'];
					?>
						<div style="margin-bottom:2px" class="quesAnsBullets">
                        				<a title="<?php echo $blogTitle; ?>" href="<?php echo $blogUrl; ?>" class="fontSize_12p">
											<?php echo wordwrap($displayBlogTitle, 35, "<wbr />\n"); ?>
										</a>
                        </div>
						<div class="lineSpace_5">&nbsp;</div>
					<?php
						}
					?>
	
					</div>
				</div>
				<?php
					}
				?>
				<div class="lineSpace_10">&nbsp;</div>
				<div align="right" class="mar_full_10p"><a href="/blogs/shikshaBlog/showArticlesList?type=kumkum&c=<?php echo rand(); ?>"  title="View All Kum Kum Tandon Articles">View All</a></div>
				<div class="lineSpace_10">&nbsp;</div>
			</div>
		<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
	</div>
</div>
<div class="clear_L"></div>
