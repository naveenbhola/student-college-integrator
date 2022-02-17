<?php $this->load->view('enterprise/subtabsStudyAbroadWidgets'); ?>

        <div style="width:100%; height: 20px;">&nbsp;</div>
        <div id="form_container" style="margin-left:100px;">
                <div id="msgtext" class="errorMsg" style="margin-bottom:10px;"></div>


                        <div>
                                <select name="regions" id="regions" onchange="getCategorypageWidgetsData('<?=$widgetType?>');">
                                    <option value="">Select a Region</option>
                                    <?php
                                            foreach($regions as $key=>$region){
                                                    echo "<option value='".$key."'>".$region."</option>";
                                            }
                                    ?>
                                </select>
                        </div>
                
                        <div id="OR2" style="margin:10px;font-weight:bold"><div class="lineSpace_10" >OR</div></div>

                        <div>
                            <select name="country1" id="country1" onchange="getCategorypageWidgetsData('<?=$widgetType?>');">
                                <option value="">Select a Country</option>
                                <?php
                                        foreach($countries as $key=>$country){
                                                echo "<option value='".$key."'>".$country."</option>";
                                        }
                                ?>
                            </select>
                        </div>                        
                        <div style="margin:10px;"><div class="lineSpace_10" >&nbsp;</div></div>


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
                <div style="margin:20px 0"><div id="errortext" class="errorMsg">&nbsp;</div></div>
                <div style="margin-top:25px;">
                        <span>Article ID 1</span><span style="margin-left:40px;"><input type="text" name="quick_links_text1" id="quick_links_text1" value="Enter Article ID" default= "Enter Article ID" onfocus="checkTextElementOnTransition(this,'focus')" onblur="checkTextElementOnTransition(this,'blur')" style="color:#aaaaaa"></span><br />
                        <span>Article ID 2</span><span style="margin-left:40px;"><input type="text" name="quick_links_text2" id="quick_links_text2" value="Enter Article ID" default= "Enter Article ID" onfocus="checkTextElementOnTransition(this,'focus')" onblur="checkTextElementOnTransition(this,'blur')" style="color:#aaaaaa"></span><br />
                        <span>Article ID 3</span><span style="margin-left:40px;"><input type="text" name="quick_links_text3" id="quick_links_text3" value="Enter Article ID" default= "Enter Article ID" onfocus="checkTextElementOnTransition(this,'focus')" onblur="checkTextElementOnTransition(this,'blur')" style="color:#aaaaaa"></span><br />
                        <span>Article ID 4</span><span style="margin-left:40px;"><input type="text" name="quick_links_text4" id="quick_links_text4" value="Enter Article ID" default= "Enter Article ID" onfocus="checkTextElementOnTransition(this,'focus')" onblur="checkTextElementOnTransition(this,'blur')" style="color:#aaaaaa"></span><br />
                        <span>Article ID 5</span><span style="margin-left:40px;"><input type="text" name="quick_links_text5" id="quick_links_text5" value="Enter Article ID" default= "Enter Article ID" onfocus="checkTextElementOnTransition(this,'focus')" onblur="checkTextElementOnTransition(this,'blur')" style="color:#aaaaaa"></span><br />
                </div>
                <div style="margin-top:30px;margin-bottom:70px;width:100%;">
                    <div style="width:20%;float:left;text-align:center;"><input type="button" value="Save" onclick="setCategorypageWidgetsData('<?=$widgetType?>');"/></div><div style="float:right;width:80%;" id="removeArticleButtonContainer">&nbsp;</div>
                </div>
        </div>