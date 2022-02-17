<div id="tags-layer" class="tags-layer"></div>
<div id="posting-layer" class="posting-layer">
        <div class="tags-head">Add more Tags <a id="cls-add-tags" class="cls-cross" href="javascript:void(0);"></a></div>
        <div class="tag-body" id="tag-more-div">
            <div class="opacticy-col"><img border="0" src="//<?php echo IMGURL; ?>/public/mobile5/images/ShikshaMobileLoader.gif" id="tags-loader" alt="" class="small-loader"></div>
            <div class="rel-qstn">Tags associated with your <span id='add_tags_layer_head'><?=htmlentities(ucfirst($type), ENT_QUOTES, 'UTF-8');?></span></div>
            <div class="tags-slctd">
                <div id="tags-selctd-links">
                    <!--a class="choosen-tag" status = "checked" href="javascript:void(0);" tagId ="12" classification="manual"><span><i></i></span><span class="i-s">Science &amp; Engineerng</span></a>
                    <a class="choosen-tag" status = "checked" href="javascript:void(0);" tagId ="12" classification="manual"><span><i></i></span><span class="i-s">Science &amp; Engineerng</span></a-->
                    
                </div>
                <p class="clr">
            </p></div>
            <div class="tags-block">
                <input type="text" class="" placeholder="Type here..." autofocus="" id="tagSearch">
                <p class="err0r-msg" id='tagSearch_error'>The Answer must contain atleast 20 characters.</p>
                <div class="search-college-layer" id="tagSearch_container" style="display:none">
                </div>
            </div>
            <div class="btns-col">
                <span class="tag-note">Add relevant tags to get quick responses.</span>
                <span class="right-box">
                    <a id="ext-add-tags" class="exit-btn" href="javascript:void(0);">Cancel</a>
                    <a id="done-add-tags" class="prime-btn" href="javascript:void(0);">Done</a>
                </span>
                <p class="clr"></p>
            </div>
        </div>
    </div>