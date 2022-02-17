<div class="raised_pink"> 
    <b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
    <div id="askQueryContainer" class="boxcontent_pink">

    <?php
    error_log_shiksha("ask Query.php loading");
    $url = site_url('listing/Listing/askQuery');
    echo $this->ajax->form_remote_tag( array('url'=> $url,'success' => 'javascript:updateAskQueryResponse(request.responseText);','name' => 'askQuery','id' => 'askQuery'));  
    ?>
        <div class="h36 bgRequestInfo normaltxt_11p_blk bld fontSize_14p">
            <img src="/public/images/request_Free_Info.gif" width="57" height="36" align="absmiddle" />Ask Query
        </div>
        <div class="mar_left_10p">
            <div class="lineSpace_12">&nbsp;</div>
            <div class="normaltxt_11p_blk fontSize_11p float_L" style="width:150px">
                Ask your query:<br/>
    
                <?php 
                if(!isset($validateuser[0])){ 
                    ?>
                        (Already have Shiksha account then <a href="#" class="fontSize_11p" onClick="showuserLoginOverLay('myShiksha'); return false;"> Sign In</a> )
    
                <?php } ?> 
            </div>
            <div class="clear_L"></div>
            <input type="hidden" name="listing_type" id="listing_type"  value="<?php echo $listing_type; ?>"/>
            <input type="hidden" name="listing_type_id" id="listing_type_id"  value="<?php echo $type_id; ?>"/>
            <input type="hidden" name="threadId" id="threadId"  value="<?php echo $threadId; ?>"/>
            <input type="hidden" name="categoryId" id="categoryId"  value="<?php echo $boardId; ?>"/>
        </div>
    	 <div style="margin-left:10px;">
	        <textarea id="queryContent" name="queryContent"  validate="validateStr" maxlength="500" minlength="10"  style="width:80%;" caption="Query Content" required="true" ></textarea><br />
			<span class="float_L"><a href="<?php echo $discussionUrl; ?>">View all queries & answers</a></span>
			<br clear='left' />
	    </div>
        <div class="row errorPlace">
            <div class="errorMsg" id="queryContent_error" ></div>
        </div>
		<div class="mar_left_10p">
			<div id="queryPostResponse" class="float_L normaltxt_13p_blk" style="color:green;"></div>
			<div class="clear_L"></div>
		</div>

        <div class="lineSpace_13">&nbsp;</div>
            <div class="">
            <div class="buttr3" style="padding-left:38px">
                <button class="btn-submit7 w3" value="" type="submit" onClick="return askQuery(this.form);" >
                    <div class="btn-submit7">
                        <p class="btn-submit8 btnTxtBlog">Ask Query</p>
                    </div>
                </button>
            </div>
            <div class="clear_L"></div>
        </div>
        <div class="lineSpace_10">&nbsp;</div>
    </form>

    <script>

    function askQuery(objForm){
        var flag = validateFields(objForm);  
        if(flag != true){
            return false;
        }
        else {
            return true;
        }
    }

function updateAskQueryResponse(responseText)
{ 
    if(parseInt(trim(responseText)) > 0){
        document.getElementById('queryPostResponse').innerHTML = "Your Query is successfully posted.";
        document.getElementById('queryContent').value = "";
    }
    else{
        document.getElementById('queryContent_error').innerHTML = "Please check your query content.";
    }
}
    </script>
        </div>
        <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
    </div>
    <div class="lineSpace_10">&nbsp;</div>

