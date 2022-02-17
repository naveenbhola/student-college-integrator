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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<title>CMS Banner Upload</title>
<div style="width:100%">
	<div style="margin:0 10px">
    	<div style="width:100%">
            <div class="raised_lgraynoBG">
                <b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
<form enctype="multipart/form-data" onSubmit=" AIM.submit(this, {'onStart' : submitForm, 'onComplete' : showPublishResponse})"  action="/enterprise/Enterprise/publishAll" method="post"  name = "TopForm" id = "TopForm">
                    <div class="boxcontent_lgraynoBG">
                    	<div style="width:100%">
                        	<div style="padding:10px">
                            	<div class="orangeColor fontSize_14p bld" style="padding-bottom:5px">Top 20 Institutes In India</div>
                                <div class="grayLine_1">&nbsp;</div>
                                <div class="lineSpace_10">&nbsp;</div>
                                
								<!---Start_UploadBanner-->
                                <div style="width:100%;padding-bottom:10px">
                                	<div style="width:150px" class="float_L">
                                    	<div style="width:100%">
                                        	<div class="txt_align_r bld Fnt14" style="line-height:20px">Select Category &nbsp;</div>
                                        </div>
                                    </div>
										<?php global $categoryParentMap;?>
                                    <div style="width:800px" class="float_L">
                                    	<div style="width:100%">
                                        	<div><select style="width:220px" name = "categoryselector" id = "categoryselector" onChange = "getCmsInstitutes(this.value);"><option value = ''>Select</option>
											<?php foreach($categoryParentMap as $key=>$value){ 
											if($value['id'] == $selectedcategoryId){ ?>
											<option selected = 'true' value = "<?php echo $value['id']?>"><?php echo $key?></option>
											<?php } else { ?>
											<option value = "<?php echo $value['id']?>" ><?php echo $key?></option>
											<?php }} ?>
                                            </select></div>
                                            <div><div class="errorMsg" id = "categoryerror"></div></div>
                                        </div>
                                    </div>
                                    <div class="clear_L">&nbsp;</div>
                                </div>
                                <!---End_UploadBanner-->
                                
                                <!---Start_Upate-->
                                <?php 
                                //for($i = 0;$i<count($institutes);$i++){
                                for($i = 1;$i<=20;$i++){
                                
/*    $institute['instituteid'] = document.getElementById('enterid' + $i).value;
    $institute['priority'] = $i;
    $institute['status'] = 'add';

    var institutejson = json_encode(institute);*/
                                ?>
                                <div style="width:100%;padding-bottom:10px">
                                	<div style="width:150px" class="float_L">
                                    	<div style="width:100%">
                                        	<div class="txt_align_r" style="line-height:20px">Enter Institute ID <?php echo $i;?> &nbsp;</div>
                                        </div>
                                    </div>
                                    <div style="width:800px" class="float_L">
                                    	<div style="width:100%">
                                            <div style="width:100%">
                                                <div class="float_L"><input type="text" name = "<?php echo 'enterid'.$i?>" id = "<?php echo 'enterid'.$i?>" style="width:70px;height:18px" onBlur = "getinstitutename(this.value,<?php echo $i?>,document.getElementById('categoryselector').value);" validate = "validateInteger" caption = "institute id"></div>
                                                <div class="float_L">&nbsp;&nbsp;<input type = "button" class="cms_topAddBtn" id = "<?php echo 'addupdatebtn' .$i?>"  onClick = "saveOption(document.getElementById('<?php echo 'enterid'.$i?>').value,<?php echo $i?>,document.getElementById('categoryselector').value);"/></div>
                                                <div class="float_L"><div style="padding:4px 0 0 10px" id = "<?php echo 'name'.$i?>"></div></div>
                                                <div class="clear_L">&nbsp;</div>
                                            </div>
                                            <div><div class="errorMsg" id = "<?php echo 'error' .$i?>"></div></div>
										</div>
                                    </div>
                                    <div class="clear_L">&nbsp;</div>
                                </div>
                                <?php } ?>
                                <!---End_Update-->
                                
                                <!---Start_Add-->
<!--                                <div style="width:100%;padding-bottom:10px">
                                	<div style="width:150px" class="float_L">
                                    	<div style="width:100%">
                                        	<div class="txt_align_r" style="line-height:20px">Enter Institute ID 2 &nbsp;</div>
                                        </div>
                                    </div>
                                    <div style="width:550px" class="float_L">
                                    	<div style="width:100%">
                                            <div style="width:100%">
                                                <div class="float_L"><input type="text" style="width:70px;height:18px"></div>
                                                <div class="float_L">&nbsp;&nbsp;<input class="cms_topAddBtn" value="" /></div>
                                                <div class="float_L"><div style="padding:4px 0 0 10px">&nbsp;</div></div>
                                                <div class="clear_L">&nbsp;</div>
                                            </div>
                                            <div><div class="errorMsg">Please enter the valid institute ID</div></div>
										</div>
                                    </div>
                                    <div class="clear_L">&nbsp;</div>
                                </div>-->
                                <!---End_Add-->
                                
                                
                                <div class="grayLine_1">&nbsp;</div>
                                <div class="lineSpace_10">&nbsp;</div>                                
                                            <div><div class="errorMsg" id = "mainerror"></div></div>
                                <!---Start_Button-->
								<div align="center"><input type="submit" class="btnSubmitted" value="Publish Top 20 List" style="font-size:13px" onClick = "return publishall(this.form);" /></div>
                                <!---End_Button-->
                                
                                
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

function publishall(objForm)
{
    var flag = 0;
    if(document.getElementById('categoryselector').value == '')
    {
        document.getElementById('categoryerror').innerHTML = 'Please select a category';
        flag = 1;
    }
    for(var i = 1;i<=20;++i)
    {
        if(document.getElementById('enterid' + i).value == '')
        {
            document.getElementById('error' + i).innerHTML = "Please specify institute id";
        }
    }
    checkifinstituteisadded(1,20);    

    for(var i = 1;i<=20;++i)
    {
        if(trim(document.getElementById('error' + i).innerHTML) != "")
        {
            flag = 1;
            break;
        }
    }    
    if(flag == 1)
    {
        document.getElementById('mainerror').innerHTML = "Please correct the errors marked in red";
        return false;
    }
    else
    {

        document.getElementById('mainerror').innerHTML = "";
        return true;
    }
}

function getCmsInstitutes(categoryid)
{
    if(categoryid == '')
    {
        document.getElementById('categoryerror').innerHTML = 'Please select a category';
        return false;
    }
    else
    {
        document.getElementById('categoryerror').innerHTML = '';
    }

    var xmlHttp = getXMLHTTPObject();
	xmlHttp.onreadystatechange=function()
	{
		if(xmlHttp.readyState==4)
		{ 	
            var res = eval("eval("+xmlHttp.responseText+")");
            result = res.results;
            total = res.totalresults;
            i = 1;
            if(total > 0)
            {
                while(i <= 20)
                {
                    if(typeof(result[i]) != 'undefined')
                    {
                        document.getElementById('enterid' + i).value = result[i].instituteid;
                        document.getElementById('name' + i).innerHTML = result[i].institute_name;
                        document.getElementById('addupdatebtn' + i).className = 'cms_topUpdateBtn';
                        document.getElementById('error' + i).innerHTML = "";
                    }
                    else
                    {
                        document.getElementById('addupdatebtn' + i).className = 'cms_topAddBtn';
                        document.getElementById('error' + i).innerHTML = "";
                        document.getElementById('enterid' + i).value = '';
                        document.getElementById('name' + i).innerHTML = '';
                    }
                    i++;
                }
                i--;
            }
            else
            {
                while(i <= 20)
                {
                    document.getElementById('enterid' + i).value = '';
                    document.getElementById('name' + i).innerHTML = '';
                    document.getElementById('addupdatebtn' + i).className = 'cms_topAddBtn';
                    document.getElementById('error' + i).innerHTML = "";
                    i++;
                }
            }
		}
	};

	SITE_URL_HTTPS = '/';
	url =SITE_URL_HTTPS+'enterprise/Enterprise/getCmsTopInstitutes' + '/' + categoryid;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
	return false;
}

function saveOption(instituteid,priority,categoryid)
{
    if(categoryid == '')
    {
        document.getElementById('categoryerror').innerHTML = 'Please select a category';
        return false;
    }
    var statusval = '';
    if(document.getElementById('addupdatebtn' + priority).className == "cms_topAddBtn")
    {
        statusval = 'add';
    }
    else
    {
        statusval = 'update';
    }

    if(instituteid == '')
    {
        document.getElementById('error' + priority).innerHTML = 'Please specify institute id';
        document.getElementById('name' + priority).innerHTML = '';
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
                    document.getElementById('error' + priority).innerHTML = result.error;
                    document.getElementById('name' + priority).innerHTML = "";
                }
                else
                {
                    document.getElementById('error' + priority).innerHTML = '';
                    document.getElementById('addupdatebtn' + priority).className = "cms_topUpdateBtn";
//                    document.getElementById('name' + priority).innerHTML = result.msg;
                    alert(result.msg);
                }
            }
        };

        var url = '';
        url = '/enterprise/Enterprise/saveOption/' + instituteid + '/' + categoryid + '/' + statusval + '/' + priority;

        xmlHttp.open("GET",url,true);
        xmlHttp.send(null);
    }
    return false;
}

function getinstitutename(instituteid,id,categoryid)
{
    if(categoryid == '')
    {
        document.getElementById('categoryerror').innerHTML = 'Please select a category';
        return false;
    }

	if(instituteid == '')
    {
        document.getElementById('error' + id).innerHTML = 'Please specify institute id';
        document.getElementById('name' + id).innerHTML = '';
    }
    else
    {
    var xmlHttp = getXMLHTTPObject();
	xmlHttp.onreadystatechange=function()
	{
		if(xmlHttp.readyState==4)
		{ 
//            var result = xmlHttp.responseText;
			var result = eval("eval("+xmlHttp.responseText+")");
            if(typeof(result.error) != 'undefined')
            {
                document.getElementById('error' + id).innerHTML = result.error;
            }
            else
            {
                    document.getElementById('error' + id).innerHTML = '';
                    document.getElementById('name' + id).innerHTML = result.institute_name;
            }
		}
	};

	SITE_URL_HTTPS = '/';
	url =SITE_URL_HTTPS+'enterprise/Enterprise/getMetaInfoForInstitutes' + '/' + instituteid + '/' + categoryid;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
    }
	return false;
}
function submitForm()
{
	document.getElementById('TopForm').submit();
	return true;
}
function showPublishResponse(res)
{
alert(res);
}
</script>
