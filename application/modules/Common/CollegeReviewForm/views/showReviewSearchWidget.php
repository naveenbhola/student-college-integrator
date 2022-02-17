 <style>
    #suggestions_container li{     border-bottom: 1px solid #f6f6f6;color: #808080; cursor: pointer; display: block; font-size: 12px;padding: 8px 10px;text-align: left;text-decoration: none;}
    #suggestions_container li span strong{  color: #000;  display: inline; font-size: 12px; color:#808080; font-weight: bold;}

    .collge-revw-layer{background:#fff; -moz-border-radius:4px; -webkit-border-radius:4px; border-radius:4px; width:540px; margin:0 auto; }
    .collge-revw-head{color:#ff9631; font-size:24px; padding:10px 10px 10px 15px; font-family:Tahoma, Geneva, sans-serif; font-weight:normal;border-bottom:1px solid #f8a14d;}
    .rvw-close-mark{color:#727272 !important; text-decoration:none !important; border:1px solid #333; padding:2px; font-size:29px; font-weight:normal;line-height:16px; font-family:'Times New Roman',Geneva,sans-serif; }
    .collge-rvw-detail{color:#0d0d0d; font-size:12px; padding:15px;}
    ul.rvw-rating-list{font-size:14px;}
    ul.rvw-rating-list li{margin-bottom:8px; float:left}
    ul.rvw-rating-list li a{width:320px; float:left;display: block;}

    .revwListBx2 .rv_ratng { color: #999999; font-size: 12px; display: inline-block; }
    .revwListBx2 .rv_ratng span { display: inline-block; background-color: #a5a4a4; color: #ffffff; font-size: 13px; border-radius: 3px; -moz-border-radius: 3px; -webkit-border-radius: 3px; padding: 4px 12px; margin-left: 2px;  width:20px; text-align: center;}
    .revwListBx2 .rv_ratng span b { font-size: 10px; font-weight: normal; color: #ffffff; position: relative; top: 2px; clear: both }
    .bordRdus3px{margin-bottom: 20px;border: 1px solid #C2C2C2;box-shadow: none !important; }
    .permalink-inst-title a{color: #006FA2;text-decoration: none;}
    .cr-inpt{border: medium none !important;outline: medium none !important;width: 90% !important;font-size: 12px !important; color: #CCC !important;}
    .sugstrReviewP{ overflow: visible; }
    .sugstrReviewP .openCollegeReviewSuggester{position: absolute;top: 35px;left: -1px;right: 0px;background-color: #fff;border-bottom: 1px solid #C2C2C2;border-left: 1px solid #C2C2C2;border-right: 1px solid #C2C2C2;overflow: hidden;width: 100%;}
    .mrgnbtm{  display: inline-block; height: 1px;}
    .rvw-srch{background-position: -285px 166px;height: 26px;width: 24px;background: url("<?php echo SHIKSHA_HOME;?>/public/images/permalink-review-sprite-new.png") repeat scroll 0% 0% transparent;background-position: -116px -40px;}
</style>

<div id="searchOpacityLayer"></div>
<div id="searchReviewLayer" style="display: none;position:absolute;left: 29%;z-index: 10000;"></div>

<h2 style="color: #575757;margin-bottom: 10px;font-size: 20px;text-align: left;display: inline-block;font-weight: normal;line-height: 31px;">Search Reviews for Colleges</h2>

<div class="cr_inputsrchBx bordRdus3px sugstrReviewP">
    <div class="cr_inputsrchBxInnr" id="searchBox">
        <i class="rvw-srch icns-colgrvw  icn-search" onclick="gotoSearchPage('<?php echo SHIKSHA_HOME;?>','keywordSuggest');" style="cursor: pointer;"></i>
        <input type="text" class="cr-inpt" placeholder="Search Reviews by College Name" name="keyword" id="keywordSuggest" minlength="1" onfocus="getSuggestedSearch('','focus');" autocomplete="off"/>
    </div>
    <div class="cr_sugestd openCollegeReviewSuggester" id="tileSuggested" style="display: none;">
        <ul id="suggestions_container" style="text-align: left; display: block;"></ul>
        <ul id="suggestedList"></ul>
    </div>             
</div>

<p class="mrgnbtm"></p>

<?php
    $this->load->view('home/homepageRedesign/autoSuggestorCollegeReviewWidget');
?>

<script>
//hide autosuggestor container click on outside of container
$j(document).click(function (e)
{  
    var container = $j("#tileSuggested");
    var container2 = $j("#keywordSuggest, #searchBox");
    if (!container.is(e.target) && container.has(e.target).length === 0 && !container2.is(e.target) && container2.has(e.target).length === 0)
    {   
        if ($j('#tileSuggested').length>0) {
        container.slideUp(400);
    }
    }
});
</script>