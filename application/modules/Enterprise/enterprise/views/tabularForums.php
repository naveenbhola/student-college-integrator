<div style="height:45px;background-image:url(/public/images/enterpriceBg.gif); background-repeat:repeat-x;padding:0 10px;">
    Filter by:
    <select name="forumCountry" id="forumCountry">
        <option value="">Select Country</option>
        <?php
            foreach($countryList as $countryNum)
            {
                echo '<option value="'.$countryNum['countryID'].'">'.$countryNum['countryName'].'</option>';
            }
        ?>
    </select>
    <button class="btn-submit7 w6" name="filterForums" id="filterForums" value="Filter_Forum_CMS" type="button" onClick="searchForumsCMS();">
        <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Go</p></div>
    </button>
    <input type="checkbox" onclick="searchForumsCMS();" id="abuseonly" autocomplete="off">Show Reported Abuse Forums
</div>
<div id="messageAfterAjax" style="background:#FFF1A8;line-height:18px;"></div>
                            </div>
                            <div class="lineSpace_10">&nbsp;</div>
                            <div class="row normaltxt_11p_blk">
                                <input type="hidden" id="methodName" value="getPopularForumsCMSajax"/>
                                <div id="paginataionPlace1" style="display:none;"></div>
                                <div class="boxcontent_lgraynoBG bld">
                                    <div style="background-color:#EFEFEF; border-right:1px solid #B0AFB4;padding-left:2px;width:100%">
                                        <div class="float_L" style="background-color:#D1D1D3; width:35%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">&nbsp; &nbsp;Title</div>
                                        <div class="float_L" style="background-color:#EFEFEF; width:37%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">&nbsp; &nbsp;Author</div>
                                        <div class="float_L" style="background-color:#EFEFEF; width:13%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF; border-right:1px solid #B0AFB4">&nbsp; &nbsp;Creation Date</div>
                                        <div class="float_L" style="background-color:#EFEFEF; width:13%; padding-top:5px; padding-bottom:5px; border-left:1px solid #FFFFFF;">&nbsp; &nbsp;Popularity</div>
                                        <div class="clear_L"></div>
                                    </div>
                                </div>
                            </div>
                            <div id="cms_forum_table" name="cms_forum_table" class="row normaltxt_11p_blk boxcontent_lgraynoBG" style="height:240px; overflow:auto">
                            </div>
                            <!-- code for pagination start -->
                            <div class="pagingID" id="paginataionPlace2"></div>
                            <!-- code for pagination ends -->
                            <div class="lineSpace_5">&nbsp;</div>
                            <div class="bld" style="display:none">
                                Discussion Title <input type="text" id="titleName" name="titleName" value=""/>
                                Author <input type="text" id="authorName" name="authorName" value=""/>
                                <button class="btn-submit7 w9" name="searchForumCMS" id="searchForumCMS" value="Search_Forum_CMS" type="button" onClick="searchForumsCMS();">
                                    <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Search Forums</p></div>
                                </button>
                            </div>
                            <div class="lineSpace_5">&nbsp;</div>
                            <script>
                                searchForumsCMS();
                            </script>

