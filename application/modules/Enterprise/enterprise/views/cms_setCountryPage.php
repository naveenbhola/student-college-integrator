<?php
   $headerComponents = array(
      'css'			=> array('headerCms','raised_all','mainStyle','footer'),
      'js'			=> array('user','tooltip','common','newcommon','listing','prototype','scriptaculous','utils','imageUpload'),
      'title'			=> "Listings ".$type."D",
      'product' 		=> '',
  	  'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
   );
   $this->load->view('enterprise/headerCMS', $headerComponents);
   $this->load->view('enterprise/cmsTabs',$sumsUserInfo);
?>

<div style="width:100%">
	<div style="margin:0 10px">
    	<div style="width:100%">
            <div class="raised_lgraynoBG">
                <b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
<form enctype="multipart/form-data" onSubmit="if(submitForms() == false){ return false; } AIM.submit(this, {'onStart' : startCallback, 'onComplete' : showPublishResponse});"  action="/enterprise/Enterprise/cmsCountryPage" method="post"  name = "TopForm" id = "TopForm" autocomplete = "off">
                    <div class="boxcontent_lgraynoBG">
                    	<div style="width:100%">
                        	<div style="padding:10px">
                            	<div class="orangeColor fontSize_14p bld" style="padding-bottom:5px">Country Page</div>
                                <div class="grayLine_1">&nbsp;</div>
                                <div class="lineSpace_10">&nbsp;</div>
                                
								<!---Start_UploadBanner-->
                                <?php global $countries;
                                ?>
                                <div style="width:100%;padding-bottom:10px">
                                	<div style="width:150px" class="float_L">
                                    	<div style="width:100%">
                                        	<div class="txt_align_r bld Fnt14" style="line-height:20px">Select Country &nbsp;</div>
                                        </div>
                                    </div>
                                    <div style="width:800px" class="float_L">
                                    	<div style="width:100%">
                                        	<div id = "addcountry"><select style="width:220px" name = "countryselector" id = "countryselector" validate = "validateSelect" required = "true" onChange = "getCmsCountryOptions(this.value);">
                                            <option value = ''>Select</option>
											<?php foreach($countries as $key=>$value){ 
											if($value['id'] == 2){ 
											continue;
                                            } else { ?>
											<option value = "<?php echo $value['id']?>" ><?php echo $value['value']?></option>
											<?php }} ?>
                                            </select></div>
                                        	
                                            <div><div class="errorMsg" id = "countryerror"></div></div>
                                        </div>
                                    </div>
                                    <div class="clear_L">&nbsp;</div>
                                </div>
                                <!---End_UploadBanner-->
                                <!---Start_Upate-->
                                <div style = "padding-left:153px;width:250px;" id = "setquestionlink" onClick = "ShowTextBoxes('setquestion');return false;">
<img src = "/public/images/plusIcons.gif" border = "0" id = "imgsetquestion"/>
                                <a href = "#">Add Questions</a>
                                </div>
                                <div id = "setquestion" style = "display:none">
                                <div class="lineSpace_10">&nbsp;</div>
                                <?php 
                                for($i = 1;$i<=2;$i++){
                                ?>
                                <div style="width:100%;padding-bottom:10px">
                                	<div style="width:150px" class="float_L">
                                    	<div style="width:100%">
                                        	<div class="txt_align_r" style="line-height:20px">Enter Question ID <?php echo $i;?> &nbsp;</div>
                                        </div>
                                    </div>
                                    <div style="width:800px" class="float_L">
                                    	<div style="width:100%">
                                            <div style="width:100%">
                                                <div class="float_L"><input type="text" name = "questionid[]" id = "<?php echo 'questionid'.$i?>" style="width:70px;height:18px" validate = "validateInteger" caption = "institute id"></div>
                                                <div class="float_L">&nbsp;&nbsp;<input type = "button" class="cms_topAddBtn" id = "<?php echo 'addupdatebtnquestionid' .$i?>"  onClick = "saveOption(document.getElementById('<?php echo 'questionid'.$i?>').value,'question',<?php echo $i?>,document.getElementById('countryselector').value);"/></div>
                                                <div class="float_L"><div style="padding:4px 0 0 10px" id = "<?php echo 'namequestionid'.$i?>"></div></div>
                                                <div class="clear_L">&nbsp;</div>
                                            </div>
                                            <div><div class="errorMsg" id = "<?php echo 'errorquestion' .$i?>"></div></div>
										</div>
                                    </div>
                                    <div class="clear_L">&nbsp;</div>
                                </div>
                                <?php } ?>
                                </div>
                                <!---End_Update-->
                                
                                <div class="lineSpace_10">&nbsp;</div>                                
                                <div style = "padding-left:153px;width:250px;" id = "setarticlelink" onClick = "ShowTextBoxes('setarticle');return false;">
<img src = "/public/images/plusIcons.gif" border = "0" id = "imgsetarticle"/>
                                <a href = "#">Add Articles</a>
                                </div>
                                <div id = "setarticle" style = "display:none">
                                <div class="lineSpace_10">&nbsp;</div>
                                <?php 
                                for($i = 1;$i<=3;$i++){
                                ?>
                                <div style="width:100%;padding-bottom:10px">
                                	<div style="width:150px" class="float_L">
                                    	<div style="width:100%">
                                        	<div class="txt_align_r" style="line-height:20px">Enter Article ID <?php echo $i;?> &nbsp;</div>
                                        </div>
                                    </div>
                                    <div style="width:800px" class="float_L">
                                    	<div style="width:100%">
                                            <div style="width:100%">
                                                <div class="float_L"><input type="text" name = "articleid[]" id = "<?php echo 'articleid'.$i?>" style="width:70px;height:18px" validate = "validateInteger" caption = "institute id"></div>
                                                <div class="float_L">&nbsp;&nbsp;<input type = "button" class="cms_topAddBtn" id = "<?php echo 'addupdatebtnarticleid' .$i?>"  onClick = "saveOption(document.getElementById('<?php echo 'articleid'.$i?>').value,'article',<?php echo $i?>,document.getElementById('countryselector').value);"/></div>
                                                <div class="float_L"><div style="padding:4px 0 0 10px" id = "<?php echo 'namearticleid'.$i?>"></div></div>
                                                <div class="clear_L">&nbsp;</div>
                                            </div>
                                            <div><div class="errorMsg" id = "<?php echo 'errorarticle' .$i?>"></div></div>
										</div>
                                    </div>
                                    <div class="clear_L">&nbsp;</div>
                                </div>
                                <?php } ?>
                                </div>
                                
                                <div class="lineSpace_10">&nbsp;</div>                                
                                <div style = "padding-left:153px;width:250px;" id = "setfaqlink" onClick = "ShowTextBoxes('setfaq');return false;">
<img src = "/public/images/plusIcons.gif" border = "0" id = "imgsetfaq"/>
                                <a href = "#">Add FAQs</a>
                                </div>
                                <div id = "setfaq" style = "display:none">
                                <div class="lineSpace_10">&nbsp;</div>
                                <?php 
                                for($i = 1;$i<=3;$i++){
                                ?>
                                <div style="width:100%;padding-bottom:10px">
                                	<div style="width:150px" class="float_L">
                                    	<div style="width:100%">
                                        	<div class="txt_align_r" style="line-height:20px">Enter FAQ ID <?php echo $i;?> &nbsp;</div>
                                        </div>
                                    </div>
                                    <div style="width:800px" class="float_L">
                                    	<div style="width:100%">
                                            <div style="width:100%">
                                                <div class="float_L"><input type="text" name = "faqid[]" id = "<?php echo 'faqid'.$i?>" style="width:70px;height:18px" validate = "validateInteger" caption = "institute id"></div>
                                                <div class="float_L">&nbsp;&nbsp;<input type = "button" class="cms_topAddBtn" id = "<?php echo 'addupdatebtnfaqid' .$i?>"  onClick = "saveOption(document.getElementById('<?php echo 'faqid'.$i?>').value,'faq',<?php echo $i?>,document.getElementById('countryselector').value);"/></div>
                                                <div class="float_L"><div style="padding:4px 0 0 10px" id = "<?php echo 'namefaqid'.$i?>"></div></div>
                                                <div class="clear_L">&nbsp;</div>
                                            </div>
                                            <div><div class="errorMsg" id = "<?php echo 'errorfaq' .$i?>"></div></div>
										</div>
                                    </div>
                                    <div class="clear_L">&nbsp;</div>
                                </div>
                                <?php } ?>
                                </div>
                                <div class="lineSpace_10">&nbsp;</div>                                
                                <div style = "padding-left:153px;width:250px;" id = "ugexamlink" onClick = "ShowTextBoxes('ugexam');return false;">
<img src = "/public/images/plusIcons.gif" border = "0" id = "imgugexam"/>
                                <a href = "#">Add Under Graduate Exams</a>
                                </div>
                                <div id = "ugexam" style = "display:none">
                                <div class="lineSpace_10">&nbsp;</div>
                                <?php 
                                for($i = 1;$i<=3;$i++){
                                ?>
                                <div style="width:100%;padding-bottom:10px">
                                	<div style="width:150px" class="float_L">
                                    	<div style="width:100%">
                                        	<div class="txt_align_r" style="line-height:20px">Enter UG Exam ID <?php echo $i;?> &nbsp;</div>
                                        </div>
                                    </div>
                                    <div style="width:800px" class="float_L">
                                    	<div style="width:100%">
                                            <div style="width:100%">
                                                <div class="float_L"><input type="text" name = "ugexamid[]" id = "<?php echo 'ugexamid'.$i?>" style="width:70px;height:18px" validate = "validateInteger" caption = "institute id"></div>
                                                <div class="float_L">&nbsp;&nbsp;<input type = "button" class="cms_topAddBtn" id = "<?php echo 'addupdatebtnugexamid' .$i?>"  onClick = "saveOption(document.getElementById('<?php echo 'ugexamid'.$i?>').value,'ugexam',<?php echo $i?>,document.getElementById('countryselector').value);"/></div>
                                                <div class="float_L"><div style="padding:4px 0 0 10px" id = "<?php echo 'nameugexamid'.$i?>"></div></div>
                                                <div class="clear_L">&nbsp;</div>
                                            </div>
                                            <div><div class="errorMsg" id = "<?php echo 'errorugexam' .$i?>"></div></div>
										</div>
                                    </div>
                                    <div class="clear_L">&nbsp;</div>
                                </div>
                                <?php } ?>
                                <div class="lineSpace_10">&nbsp;</div>
                                </div>
                                <div class="lineSpace_10">&nbsp;</div>
                                <div style = "padding-left:153px;width:250px;"  id = "pgexamlink" onClick = "ShowTextBoxes('pgexam');return false;">
<img src = "/public/images/plusIcons.gif" border = "0" id = "imgpgexam"/>
                                <a href = "#">Add Post Graduate Exams</a>
                                </div>
                                <div id = "pgexam" style = "display:none">
                                <div class="lineSpace_10">&nbsp;</div>
                                <?php 
                                for($i = 1;$i<=3;$i++){
                                ?>
                                <div style="width:100%;padding-bottom:10px">
                                	<div style="width:150px" class="float_L">
                                    	<div style="width:100%">
                                        	<div class="txt_align_r" style="line-height:20px">Enter PG Exam ID <?php echo $i;?> &nbsp;</div>
                                        </div>
                                    </div>
                                    <div style="width:800px" class="float_L">
                                    	<div style="width:100%">
                                            <div style="width:100%">
                                                <div class="float_L"><input type="text" name = "pgexam[]" id = "<?php echo 'pgexamid'.$i?>" style="width:70px;height:18px" validate = "validateInteger" caption = "institute id"></div>
                                                <div class="float_L">&nbsp;&nbsp;<input type = "button" class="cms_topAddBtn" id = "<?php echo 'addupdatebtnpgexamid' .$i?>"  onClick = "saveOption(document.getElementById('<?php echo 'pgexamid'.$i?>').value,'pgexam',<?php echo $i?>,document.getElementById('countryselector').value);"/></div>
                                                <div class="float_L"><div style="padding:4px 0 0 10px" id = "<?php echo 'namepgexamid'.$i?>"></div></div>
                                                <div class="clear_L">&nbsp;</div>
                                            </div>
                                            <div><div class="errorMsg" id = "<?php echo 'errorpgexam' .$i?>"></div></div>
										</div>
                                    </div>
                                    <div class="clear_L">&nbsp;</div>
                                </div>
                                <?php } ?>
                                <div class="lineSpace_10">&nbsp;</div>
                                </div>
                                <div class="lineSpace_10">&nbsp;</div>

                                <div style = "padding-left:153px;width:250px;" id = "doctoralexamlink" onClick = "ShowTextBoxes('doctoralexam');return false;">
<img src = "/public/images/plusIcons.gif" border = "0" id = "imgdoctoralexam"/>
                                <a href = "#">Add Doctoral Exams</a>
                                </div>
                                <div id = "doctoralexam" style="display:none">
                                <div class="lineSpace_10">&nbsp;</div>
                                <?php 
                                for($i = 1;$i<=3;$i++){
                                ?>
                                <div style="width:100%;padding-bottom:10px">
                                	<div style="width:150px" class="float_L">
                                    	<div style="width:100%">
                                        	<div class="txt_align_r" style="line-height:20px">Enter Doctoral Exam ID <?php echo $i;?> &nbsp;</div>
                                        </div>
                                    </div>
                                    <div style="width:800px" class="float_L">
                                    	<div style="width:100%">
                                            <div style="width:100%">
                                                <div class="float_L"><input type="text" name = "doctoral[]" id = "<?php echo 'doctoralexamid'.$i?>" style="width:70px;height:18px" validate = "validateInteger" caption = "institute id"></div>
                                                <div class="float_L">&nbsp;&nbsp;<input type = "button" class="cms_topAddBtn" id = "<?php echo 'addupdatebtndoctoralexamid' .$i?>"  onClick = "saveOption(document.getElementById('<?php echo 'doctoralexamid'.$i?>').value,'doctoralexam',<?php echo $i?>,document.getElementById('countryselector').value);"/></div>
                                                <div class="float_L"><div style="padding:4px 0 0 10px" id = "<?php echo 'namedoctoralexamid'.$i?>"></div></div>
                                                <div class="clear_L">&nbsp;</div>
                                            </div>
                                            <div><div class="errorMsg" id = "<?php echo 'errordoctoralexam' .$i?>"></div></div>
										</div>
                                    </div>
                                    <div class="clear_L">&nbsp;</div>
                                </div>
                                <?php } ?>
                                <div class="lineSpace_10">&nbsp;</div>
                                </div>
                                <div class="lineSpace_10">&nbsp;</div>
                                <div style = "padding-left:153px;width:250px;" id = "englishtestlink" onClick = "ShowTextBoxes('englishtest');return false;">
<img src = "/public/images/plusIcons.gif" border = "0" id = "imgenglishtest"/>
                                <a href = "#">Add English Tests Exams</a>
                                </div>
                                <div id = "englishtest" style = "display:none">
                                <div class="lineSpace_10">&nbsp;</div>
                                <?php 
                                for($i = 1;$i<=3;$i++){
                                ?>
                                <div style="width:100%;padding-bottom:10px">
                                	<div style="width:150px" class="float_L">
                                    	<div style="width:100%">
                                        	<div class="txt_align_r" style="line-height:20px">Enter Test Exam ID <?php echo $i;?> &nbsp;</div>
                                        </div>
                                    </div>
                                    <div style="width:800px" class="float_L">
                                    	<div style="width:100%">
                                            <div style="width:100%">
                                                <div class="float_L"><input type="text" name = "englishtestexamid[]" id = "<?php echo 'englishtestexamid'.$i?>" style="width:70px;height:18px" validate = "validateInteger" caption = "institute id"></div>
                                                <div class="float_L">&nbsp;&nbsp;<input type = "button" class="cms_topAddBtn" id = "<?php echo 'addupdatebtnenglishtestexamid' .$i?>"  onClick = "saveOption(document.getElementById('<?php echo 'englishtestexamid'.$i?>').value,'englishtestexam',<?php echo $i?>,document.getElementById('countryselector').value);"/></div>
                                                <div class="float_L"><div style="padding:4px 0 0 10px" id = "<?php echo 'nameenglishtestexamid'.$i?>"></div></div>
                                                <div class="clear_L">&nbsp;</div>
                                            </div>
                                            <div><div class="errorMsg" id = "<?php echo 'errorenglishtestexam' .$i?>"></div></div>
										</div>
                                    </div>
                                    <div class="clear_L">&nbsp;</div>
                                </div>
                                <?php } ?>
                                <div class="lineSpace_10">&nbsp;</div>
                                </div>
                                <div class="lineSpace_20">&nbsp;</div>                                
                                
                                <div class="grayLine_1">&nbsp;</div>
                                <div class="lineSpace_10">&nbsp;</div>                                
                                            <div><div class="errorMsg" id = "mainerror"></div></div>
                                <!---Start_Button-->
                                <div id = "publishbtn">
								<div align="c"><input type="submit" class="btnSubmitted" value="Publish" style="font-size:13px" /></div>
                                </div>
                            </div>
                        </div>
                    </div>
</form>
                <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
            </div>
        </div>
    </div>
</div>
<script>

function ShowTextBoxes(testname)
{
    document.getElementById(testname).style.display = '';
    document.getElementById('img' + testname).src = '/public/images/minIcons.gif';
    document.getElementById(testname + 'link').setAttribute('onclick','HideTextBoxes("'+ testname + '");return false;');
    return false;
}

function HideTextBoxes(testname)
{
    document.getElementById(testname).style.display = 'none';
    document.getElementById('img' + testname).src = '/public/images/plusIcons.gif';
    document.getElementById(testname + 'link').setAttribute('onclick','ShowTextBoxes("'+ testname + '");return false;');
    return false;
}

function checkifinstituteisadded(start,end)
{
    for(var i = start;i<=end;i++)
    {
        for(var j=1;j<i;j++)
        {
            if(document.getElementById('enterid' + i).value != '')
            {
                if(document.getElementById('enterid'+i).value == document.getElementById('enterid'+j).value)
                {
                    document.getElementById('error'+i).innerHTML = "Institute has already been added at position " + j;
                    break;
                }
            }
        }
    }
}

function getCmsCountryOptions(countryid)
{
    if(countryid == '')
    {
        var inputElems = document.getElementById('TopForm');

        for(var inputElemsCount =0;inputElemsCount<inputElems.length;inputElemsCount++)
        {
            inputElem = inputElems[inputElemsCount];	
            if(inputElem.type == 'text')
            {
                inputElem.value = '';
               // inputElem.disabled = false;
                document.getElementById('addupdatebtn' + inputElem.id).className = "cms_topAddBtn";
            }
            if(inputElem.id.indexOf('name') > -1)
            {
                inputElem.innerHTML = '';
            }
        }
        document.getElementById('countryerror').innerHTML = 'Please select a country';
        return false;
    }
    else
    {
        document.getElementById('countryerror').innerHTML = '';
    }

    var xmlHttp = getXMLHTTPObject();
    xmlHttp.onreadystatechange=function()
    {
        if(xmlHttp.readyState==4)
        { 
            var res = eval("eval("+xmlHttp.responseText+")");
            result = res.results;
                var inputElems = document.getElementById('TopForm');
                for(var inputElemsCount =0;inputElemsCount<inputElems.length;inputElemsCount++)
                {
                    inputElem = inputElems[inputElemsCount];	
                    if(inputElem.type == 'text'){
                        inputElem.value = '';
                    //    inputElem.disabled = false;
                        document.getElementById('addupdatebtn' + inputElem.id).className = "cms_topAddBtn";
                        document.getElementById('name'+inputElem.id).innerHTML = '';
                   }
                }
            document.getElementById('setquestion').style.display = '';
            document.getElementById('imgsetquestion').src = '/public/images/minIcons.gif';
            document.getElementById('setquestionlink').setAttribute('onclick','HideTextBoxes("setquestion");return false;');
            
            document.getElementById('setarticle').style.display = '';
            document.getElementById('imgsetarticle').src = '/public/images/minIcons.gif';
            document.getElementById('setarticlelink').setAttribute('onclick','HideTextBoxes("setarticle");return false;');
            
            document.getElementById('setfaq').style.display = '';
            document.getElementById('imgsetfaq').src = '/public/images/minIcons.gif';
            document.getElementById('setfaqlink').setAttribute('onclick','HideTextBoxes("setfaq");return false;');

            document.getElementById('ugexam').style.display = '';
            document.getElementById('imgugexam').src = '/public/images/minIcons.gif';
            document.getElementById('ugexamlink').setAttribute('onclick','HideTextBoxes("ugexam");return false;');
            
            document.getElementById('pgexam').style.display = '';
            document.getElementById('imgpgexam').src = '/public/images/minIcons.gif';
            document.getElementById('pgexamlink').setAttribute('onclick','HideTextBoxes("pgexam");return false;');
            
            document.getElementById('doctoralexam').style.display = '';
            document.getElementById('imgdoctoralexam').src = '/public/images/minIcons.gif';
            document.getElementById('doctoralexamlink').setAttribute('onclick','HideTextBoxes("doctoralexam");return false;');
            
            document.getElementById('englishtest').style.display = '';
            document.getElementById('imgenglishtest').src = '/public/images/minIcons.gif';
            document.getElementById('englishtestlink').setAttribute('onclick','HideTextBoxes("englishtest");return false;');

            for(var key in result)
            {
                if(key.indexOf('name') > -1)
                    document.getElementById(key).innerHTML = result[key] + ' is already published';
                else
                {
                    document.getElementById(key).value = result[key];
                    //  document.getElementById(key).disabled = true;
                    document.getElementById('addupdatebtn' + key).className = "cms_topUpdateBtn";
                }
            }
            total = res.totalresults;
            i = 1;
        }
    };

    SITE_URL_HTTPS = '/';
    url =SITE_URL_HTTPS+'enterprise/Enterprise/getCmsCountryOptions' + '/' + countryid;
    xmlHttp.open("GET",url,true);
    xmlHttp.send(null);
    return false;
}

function saveOption(itemid,itemtype,priority,countryid)
{
    var obj = document.getElementById('countryselector');
    var countrycsv = '';
    var j = 0;
    for(var i = 0;i < obj.length;i++)
    {
        if(obj[i].selected)
        {
            if(j == 0)
                countrycsv = obj[i].value;
            else
                countrycsv += ',' + obj[i].value ;
            j++;
        }
    }
        
    if(j == 0 || countrycsv == '')
    {
        document.getElementById('countryerror').innerHTML = 'Please select a country';
        return false;
    }
    else
    {
        document.getElementById('countryerror').innerHTML = '';
    }
    var statusval = '';
    if(document.getElementById('addupdatebtn' + itemtype + 'id' + priority).className == "cms_topAddBtn")
    {
        statusval = 'add';
    }
    else
    {
        statusval = 'update';
    }
    if(itemid == '' && statusval == 'add')
    {
        document.getElementById('error' + itemtype + priority).innerHTML = 'Please specify the ' + itemtype + ' id';
        document.getElementById('name' + itemtype + 'id' + priority).innerHTML = '';
    }
    else
    {
        var xmlHttp = getXMLHTTPObject();
        xmlHttp.onreadystatechange=function()
        {
            if(xmlHttp.readyState==4)
            { 
                var result1 = eval("eval("+xmlHttp.responseText+")");
                result = result1.results;
                if(typeof(result.error) != 'undefined')
                {
                    document.getElementById('error' + itemtype + priority).innerHTML = result.error;
                    document.getElementById('name' + itemtype + priority).innerHTML = "";
                }
                else
                {
                    document.getElementById('error' +  itemtype + priority).innerHTML = '';
                    if(itemid != '')
                    document.getElementById('addupdatebtn' + itemtype + 'id' + priority).className = "cms_topUpdateBtn";
                    else
                    document.getElementById('addupdatebtn' + itemtype + 'id' + priority).className = "cms_topAddBtn";
                    var msg = document.getElementById('name' + itemtype + 'id' + priority).innerHTML;
                    document.getElementById('name' + itemtype + 'id' + priority).innerHTML = result.msg;
                    window.setTimeout(function(){ document.getElementById('name' + itemtype + 'id' + priority).innerHTML = msg }, 1000);
                }
            }
        };

        var url = '';
        if(itemid == '')
        itemid = 0;
        url = '/enterprise/Enterprise/saveCountryOption/country/1/0/' + countrycsv + '/0/'  + itemid + '/' + itemtype + '/' + statusval + '/' +  priority;
        xmlHttp.open("GET",url,true);
        xmlHttp.send(null);
    }
    return false;
}

function submitForms()
{
    if(document.getElementById('countryselector').value == '')
    {
        document.getElementById('countryerror').innerHTML = 'Please select a country';
        return false;
    }
    else
    {
        return true;
    }
}

function showPublishResponse(res)
{
   alert(res);
}
</script>
