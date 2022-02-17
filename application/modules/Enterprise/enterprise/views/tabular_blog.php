<div style="height:45px;background-image:url(/public/images/enterpriceBg.gif); background-repeat:repeat-x;padding:0 10px;display:none;">
    Filter by:
    <select name="blogCountry" id="blogCountry">
        <option value="">Select Country</option>
        <?php
            foreach($countryList as $countryNum)
            {
                echo '<option value="'.$countryNum['countryID'].'">'.$countryNum['countryName'].'</option>';
            }
        ?>
    </select>
    <button class="btn-submit7 w6" name="filterBlogs" id="filterBlogs" value="Filter_Blog_CMS" type="button" onClick="searchBlogsCMS();" style="margin-left:10px;width:50px">
        <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Go</p></div>
    </button>
</div>
                            </div>
<div id="blogTabContainer">
	<div id="blogNavigationTab">
		<ul>					
			<li container="community" tabName="communityMsgBoard"  class="selected" onClick="selectHomeFeaturedTab('community','MsgBoard');getPopularBlogsCMSajax();">						
				<a href="#">Articles</a>						
			</li>
			<li container="community" tabName="communityGroup" class="" onClick="selectHomeFeaturedTab('community','Group'); getChangeRequestsBlogsCMSajax();">						
				<a href="#">View Change Report</a>						
			</li>
		</ul>
	</div>
	<div class="clear_L"></div>
</div>
                            <div class="lineSpace_10" style="border-top:solid 1px #acacac;">&nbsp;</div>
                            <div id="search_area" style="margin-bottom:20px;margin-left:15px;">
                            	<div style="margin-top:10px;">
	                           	<label>Search by </label>
	                            	<select style="width:60px;" id="search_type">
						<option value="Title">Title</option>	                            	
<!-- 	                            		<option value="Tag">Tags</option> -->
	                            		<option value="Author">Author</option>
	                            	</select>
	                            	<input type="text" id="search_text" name="search_text" />
					<select style="width:100px;" id="article_type" name="article_type" >
						<option value="none" selected>Type (All)</option>
						<option value="kumkum">Kum Kum Articles</option>
						<option value="exam">Exam Articles</option>
						<option value="examstudyabroad">Exam Articles Study Abroad</option>
						<option value="user">Shiksha Articles</option>
						<option value="ht">Article powered by HT Horizons</option>
						<option value="faq">Frequently Asked Questions</option>
						<option value="iseet">ISEET Articles</option>
						<option value="news">News </option>
					</select>
                    <select autocomplete="off" style="width:100px;" id="article_status" name="article_status" >
                        <option value="all" selected="selected">Status (All)</option>
                        <option value="live">Live</option>
                        <option value="draft">Draft</option>
                    </select><br/>
	                                <span style="margin-left:2px;">
	                                <span>
	                                <input type="radio" name="posted_by" value="all" checked="checked" id="posted_all" style="margin-top:5px;">All Articles
	                                </span>
	                                <input type="radio" name="posted_by" value="me" id="posted_me">My Articles
									</span>	                            	
	                                <button class="btn-submit7 w9" id="search_button" onClick="javascript: searchArticle('button_click','no_order');">
	                                	<div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Search</p></div>
	                                </button>
                                </div>

                            </div>
                            <div class="row normaltxt_11p_blk">
                                <input type="hidden" id="methodName" value="searchArticle"/>
                                <div id="paginataionPlace1" style="display:none;"></div>
                                <!-- code for pagination start -->
                                <div class="mar_full_10p">
                                    <div class="float_R" style="padding:5px">
                                        <div class="pagingID" id="paginataionPlace2"></div>
                                    </div>
                                    <div class="float_L">
                                        <div class="normaltxt_11p_blk bld" align="right" sty>
                                            <button class="btn-submit7 w9" name="newBlogCMS" id="newBlogCMS" value="New_Blog_CMS" type="button" onClick="window.location='<?php echo site_url().'/enterprise/Enterprise/addBlogCMS/'.$prodId; ?>'">
                                                <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Add New Article</p></div>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="clear_L"></div>
                                </div>
                                <!-- code for pagination ends -->
                                <div class="lineSpace_5">&nbsp;</div>
                                <div id="articleBox" class="boxcontent_lgraynoBG bld">
                                    <div style="background-color:#EFEFEF; border-right:1px solid #B0AFB4;padding-left:2px;width:100%">
                                        <div class="float_L" style="background-color:#D1D1D3; width:55%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">&nbsp; &nbsp;Title</div>
					<div class="float_L" style="background-color:#D1D1D3; width:14%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">&nbsp; &nbsp;Article Status</div>
                                        <div class="float_L" style="background-color:#D1D1D3; width:14%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">&nbsp; &nbsp;Article Type</div>
                                        <div class="float_L" style="background-color:#EFEFEF; width:14%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF;">
                                       &nbsp; &nbsp;Date Created
                                       <span style="width:10px; float: right;">
                                        <a href="javascript:void(0);" onclick="searchArticle('order','ASC');" id="arrow_asc"><img src="/public/images/arrow-up-article.png" /></a>
                                        <a href="javascript:void(0);" onclick="searchArticle('order','DESC');" id="arrow_desc"><img src="/public/images/arrow-dwn-article.png" style="margin-top:5px;" /></a>
                                        </span>
                                        </div>
                                        <div class="clear_L"></div>
                                    </div>
                                </div>
                                <div id="changeRequestBox" class="boxcontent_lgraynoBG bld" style="display:none">
                                    <div style="background-color:#EFEFEF; border-right:1px solid #B0AFB4;padding-left:2px;width:100%">
                                        <div class="float_L" style="background-color:#D1D1D3; width:50%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">&nbsp; &nbsp;Title</div>
                                        <div class="float_L" style="background-color:#EFEFEF; width:20%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF;">&nbsp; &nbsp;Reported By</div>
                                        <div class="float_L" style="background-color:#EFEFEF; width:14%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF;">&nbsp; &nbsp;Article Type</div>
                                        <div class="float_L" style="background-color:#EFEFEF; width:14%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF;">&nbsp; &nbsp;Date Submitted</div>
                                        <div class="clear_L"></div>
                                    </div>
                            	</div>
                            </div>
                            
                            <div id="cms_blog_table" name="cms_blog_table" class="row normaltxt_11p_blk boxcontent_lgraynoBG" style="height:240px; overflow:auto">
                            </div>
                            <div class="lineSpace_5">&nbsp;</div>
                            <div class="bld mar_full_10p" style="display:none">
                                Blog Name <input type="text" id="titleName" name="titleName" value=""/>
                                <button class="btn-submit7 w9" name="searchBlogsCMS" id="searchBlogsCMS" value="Search_Blogs_CMS" type="button" onClick="searchBlogsCMS();" style="width:80px">
                                    <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Search</p></div>
                                </button>
                            </div>
                            <div class="lineSpace_5">&nbsp;</div>
<?php
//Code to set the pagination automatically fro where user had left it
if(isset($_COOKIE['articlePaginationNumber']) && $_COOKIE['articlePaginationNumber']>0){
    $paginationStarting = $_COOKIE['articlePaginationNumber'];
    echo "<script>$('startOffSet').value = '".$paginationStarting."';</script>";
    echo "<script>getPopularBlogsCMSajax();</script>";
    //echo "<script>setCookie('articlePaginationNumber','0',1,'seconds');</script>";
}
else{
    echo "<script>searchBlogsCMS();</script>";
}
?>
