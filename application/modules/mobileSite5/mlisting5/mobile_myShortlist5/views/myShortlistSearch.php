 <?php  $formName         = 'myShortListFindCollege'; ?>
<div data-role="content" style="background:#e6e6dc">
    <div data-enhance="false">
        <?php if($addMoreColg) { ?>
            <div class="add-more-header">Add more colleges to your shortlist</div>
        <?php } ?>
        <section class="content-section bgcolorWhite" <?php if(!$addMoreColg) { ?> style="margin-top:10px;" <?php } ?> >
            <section class="clearfix content-wrap" style="box-shadow:none; border-radius:none;">
                <?php if(!$addMoreColg) { ?>
                    <header class="content-inner content-header">
                        <div class="shortlist-msg">You have zero shortlist</div>
                        <h2 class="title-txt">Start Shortlisting</h2>
                    </header>
                <?php } ?>
                <article class="clearfix content-inner" style="padding-bottom:12px">
                    <div class="shortlist-tab-details">
                        <article class="search-box" style="margin:0; display:block">
                            <button class="search-btn2 flRt" id="shortListSearchBtn"  href="javascript:void(0);" onclick="gaTrackEventCustom('MY_SHORTLIST_PAGE_MOBILE', 'search_btn', 'institute'); return searchShortListInstitute();"><i class="msprite search-icn"></i></button>
                            <div class="serach-field" style="position: relative">
                                <input type="text" id="keywordSuggestMyShortlist" onclick="$(window).scrollTop($('#keywordSuggestMyShortlist').offset().top-$('#page-header').height()-20)" name="keywordSuggestMyShortlist" default="Enter college name to shortlist" value="Enter college name to shortlist" class="shortlist-txtfield" onfocus="this.hasFocus=true; checkTextElementOnTransition(this,'focus');" onblur="this.hasFocus=false; checkTextElementOnTransition(this,'blur');" autocomplete="off" onchange="window.L_tracking_keyid = <?php echo ( isset($searchCollegeWidgetKey) ? $searchCollegeWidgetKey : '' );?>;"/>
                                <ul id="suggestions_container_shortlist">
                                </ul>
                            </div>
                            <p id="noInstituteFoundError" style="margin-top: 5px; color: #939393; font-size: 17px; width: 400px;display: none;">Sorry no institutes found.</p>
                            <p id="customError" style="margin-top: 5px; color: #939393; font-size: 17px; width: 400px;display: none;"></p>
                        </article>
                        <form id ="form_<?=$formName?>" name="<?=$formName?>" action=""  method="POST" enctype="multipart/form-data">                       
                            <article class="find-options" style="display:none">
                                <div class="tac" style="margin:15px 0 3px">
                                    <a href="javascript:void(0)" onClick ="gaTrackEventCustom('MY_SHORTLIST_PAGE_MOBILE', 'search_btn', 'exam'); return searchCollegeByExam('<?=$formName?>');" class="btn btn-default btn-med" style="width:auto; padding:4px 15px">
                                        <i class="msprite search-icn" style="margin-right:4px;"></i>Search
                                    </a>
                                </div>
                            </article>
                        </form>
                    </div>
                    <div class="comp-divider" style="margin:35px 0 30px"><p class="comp-vs tac">Or</p></div>   
                    <div id="recommendationWidget"></div>
                    <div id="recommendationWidgetLoader"></div>
                    <!----- Shortlist Recommendation starts here---------->
                    <?php // $this->load->view('mobile_myShortlist5/myShortlistRecommendationWidget'); ?>
                    <!----- Shortlist Recommendation ends here---------->
                </article>
            </section>
        </section>
            <?php $this->load->view('mcommon5/footerLinks'); ?>
    </div>
</div>