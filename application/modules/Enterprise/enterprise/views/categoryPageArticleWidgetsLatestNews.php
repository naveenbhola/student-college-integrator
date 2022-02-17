<?php $this->load->view('enterprise/subtabsArticleWidgets');
// $actionURL = MDB_SERVER."/enterprise/Enterprise/uploadLatestNewsImage";
$actionURL = site_url().'enterprise/Enterprise/setCategorypageWidgetsData';
?>
        <div style="width:100%; height: 20px;">&nbsp;</div>
        <div id="form_container" style="margin-left:100px;">
            <form  autocomplete="off" id="file_upload_form" method="post" enctype="multipart/form-data" action="<?=$actionURL?>" novalidate accept-charset="UTF-8">
                <div id="msgtext" class="errorMsg" style="margin-bottom:10px;"></div>
                <div>
                        <select id="maincategory" name="maincategory" size="1" onchange="getCategorypageWidgetsData('<?=$widgetType?>');">
                                <option value="">Select Category</option>
                                <?php
                                        foreach($catTree as $category){
                                                if($category['parentId'] == 1){
                                                        echo '<option value="'.$category['categoryID'].'">'.$category['categoryName'].'</option>';
                                                }
                                        }
                                ?>
                        </select>
                </div>
                <div id="OR2" style="margin-top:10px;margin-left:120px;font-weight:bold">
                                        <div class="lineSpace_10" >OR</div>
                </div>
                        <div>
                                        <div class="formInput" id="categoryPlace">&nbsp;</div>
                                        <div class="formInput normaltxt_11p_blk_verdana mb5" id="c_categories_combo"></div>
                        </div>
                        <div>                        
                        </div>
                        <!-- category select end -->
                        <script>
                        var completeCategoryTree = eval(<?php echo $categoryList; ?>);
                        getCategories(true,"c_categories_combo","subcategory","subcategory",false,false);
                        </script>
                <div style="margin-top:15px;"><div id="errortext" class="errorMsg"></div></div>
                <div style="width:100%;float:left;clear:both;">
                    <div style="width:40%;float:left;">
                            <div style="margin-top:30px; width:100%;"><div id="uploadPictureDiv"><input type="file" name="latestNewsPicture[]" id="latestNewsPicture"></div><br />Recommended Image size: 112 X 63 px.</div>
                            <div style="margin-top:20px; width:100%;">
                                    <span>Article ID 1</span><span style="margin-left:40px;"><input type="text" name="latest_news_text1" id="latest_news_text1" value="Enter Article ID" default= "Enter Article ID" onfocus="checkTextElementOnTransition(this,'focus')" onblur="checkTextElementOnTransition(this,'blur')" style="color:#aaaaaa"></span><br />
                                    <span>Article ID 2</span><span style="margin-left:40px;"><input type="text" name="latest_news_text2" id="latest_news_text2" value="Enter Article ID" default= "Enter Article ID" onfocus="checkTextElementOnTransition(this,'focus')" onblur="checkTextElementOnTransition(this,'blur')" style="color:#aaaaaa"></span><br />
                                    <span>Article ID 3</span><span style="margin-left:40px;"><input type="text" name="latest_news_text3" id="latest_news_text3" value="Enter Article ID" default= "Enter Article ID" onfocus="checkTextElementOnTransition(this,'focus')" onblur="checkTextElementOnTransition(this,'blur')" style="color:#aaaaaa"></span><br />
                                    <span>Article ID 4</span><span style="margin-left:40px;"><input type="text" name="latest_news_text4" id="latest_news_text4" value="Enter Article ID" default= "Enter Article ID" onfocus="checkTextElementOnTransition(this,'focus')" onblur="checkTextElementOnTransition(this,'blur')" style="color:#aaaaaa"></span><br />
                                    <span>Article ID 5</span><span style="margin-left:40px;"><input type="text" name="latest_news_text5" id="latest_news_text5" value="Enter Article ID" default= "Enter Article ID" onfocus="checkTextElementOnTransition(this,'focus')" onblur="checkTextElementOnTransition(this,'blur')" style="color:#aaaaaa"></span><br />
                            </div>
                    </div>
                    <div style="width:55%;float:right;margin-top:30px;" id="latestNewsPlaceHoder"></div>
                </div>

                <div style="margin-top:40px;margin-bottom:250px;width:100%;">
                    <div style="width:20%;margin-top:20px;float:left;text-align:center;"><input type="submit" name="bttnSave" value="Save"/></div><div style="float:right;margin-top:20px;width:80%;" id="removeArticleButtonContainer">&nbsp;</div>
                    <iframe id="upload_target" name="upload_target" src="" style="width:0;height:0;border:0px solid #fff;"></iframe>
                    <input type="hidden" name="isPictureSelected" id="isPictureSelected" value="">
                    <input type="hidden" name="dataIDs" id="dataIDs" value="">
                    <input type="hidden" name="widgetType" id="widgetType" value="<?=$widgetType?>">
                    <input type="hidden" name="country1" id="country1" value="2">
                    <input type="hidden" name="regions" id="regions" value="">
                </div></form>
        </div>	
<script language="javascript">
if(document.all) {
     $("subcategory").onchange = function(){
            getCategorypageWidgetsData('<?=$widgetType?>');
            return true;
      };
} else {
    $("subcategory").setAttribute("onChange", "getCategorypageWidgetsData('<?=$widgetType?>');" );
}

function init() {
	document.getElementById('file_upload_form').onsubmit=function() {
                var widget_info = getWidgetInfo('<?=$widgetType?>');
                var text_element_id = widget_info[0];
                // alert("text_element_id: "+text_element_id); return false;
                var thanks_msg = widget_info[1];

                if(!validateArticleWidgetForms(text_element_id, 0)){
                    return false;
                }

                $('msgtext').innerHTML = '<div class="Fnt14">Please Wait...</div>';

                var filledValues = new Array();
                for(i=1; i<=5; i++) {
                        if( $(text_element_id+i).value != $(text_element_id+i).getAttribute('default') ){
                             filledValues.push($(text_element_id+i).value);
                        }
                }

                $('dataIDs').value = filledValues.join(',');

                if($('latestNewsPicture').value != ""){
                    $('isPictureSelected').value = 1;
                } else {
                    $('isPictureSelected').value = 0;
                }

                document.getElementById('file_upload_form').target = 'upload_target'; //'upload_target' is the name of the iframe

                $('msgtext').innerHTML = '<div class="Fnt14">'+thanks_msg+'</div>';
                $('removeArticleButtonContainer').innerHTML = '<input type="button" value="Remove All Articles" onclick="removeWidgetsDataForCategory(<?=$widgetType?>);"/>';
	}
}
window.onload=init;
</script>
