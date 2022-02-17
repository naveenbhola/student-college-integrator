<?php
   $headerComponents = array(
      'css'			=> array('headerCms','raised_all','mainStyle','footer'),
      'js'			=> array('user','tooltip','common','newcommon','listing','prototype','scriptaculous','utils','imageUpload'),
      'title'			=> "Add Company & Logo",
      'product' 		=> '',
                          	'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:"")
   );

   $this->load->view('enterprise/headerCMS', $headerComponents);
   $this->load->view('enterprise/cmsTabs',$cmsUserInfo);

   $paginationHTML = doPagination($totalCount, $paginationURL,$rstart,$rcount,4);


?>

<!--Included for using the ShowOverlay function lying inside header.js-->
<script language = "javascript" src = "/public/js/header.js"></script>

<!--Included for using the validateStr function lying inside common.js-->
<script language = "javascript" src = "/public/js/common.js"></script>

<!-- Included for uploading logo pictures on the overlay-->
<script language="javascript" src="/public/js/<?php echo getJSWithVersion('myShiksha'); ?>"></script>

<!-- Included for uploading logo pictures on the overlay with AIM(Ajax Iframe Method)-->
<script language="javascript" src="/public/js/imageUpload.js"></script>



<script type="text/JavaScript">
var imageDone = new Array(0,0,0,0)






var mid='';
var mname='';
var murl='';
var nameFirstChar= /[a-zA-Z0-9]/gi;
function is_blank(str){
    if(str == '' || str == null || str == 'Enter the company name')
    return false;
    else
        return true;
}


// Facilitates the opening of the overlay
function openAddOverlay()
{
        var x=document.getElementById('addOverLay').innerHTML;
        showOverlay(790,500,'Add Companies Here',x);
}

function openModOverlay(modid, modname, modurl)
{
        mid=modid;
        mname= modname;
        murl=modurl;
        document.getElementById('logo4').src = modurl;
        var y=document.getElementById('modOverLay').innerHTML;
        showOverlay(790,500,'Add Companies Here',y);
        document.getElementById("name4id").value = modname;

}

var logo1='';
var logo2='';
var logo3='';
var logo4='';

// Checks if the company name text field in the add overlay is inputted with allowed characters/count
function checkError(eid)
{
    var name= document.getElementById(eid).value;
    var checkvalue=validateStr(name,"Company Name",50,3);
    var alphaCheck= name.charAt(0).match(nameFirstChar);
    var objErr = document.getElementById('replyText_error1');
    var objErr2 = document.getElementById('replyText_error2');
    var objErr5 = document.getElementById('replyText_error5');



    if( eid == 'name1id')
    {
        if(checkvalue != true)
        {
        document.getElementById("replyText_error1").innerHTML= checkvalue;
        document.getElementById("replyText_error1").style.display = '';
        document.getElementById(eid).value="Enter the company name";
        }
        else if ( alphaCheck == null )
        {
        document.getElementById("replyText_error1").innerHTML= "First character of the name must be alphanumeric";
        document.getElementById("replyText_error1").style.display = '';
        document.getElementById(eid).value="Enter the company name";
        }
        else
        {
            objErr.innerHTML= '';
            objErr.parentNode.style.display = 'none';
        }

    }

    if( eid == 'name2id')
    {
        if(checkvalue != true)
        {
        document.getElementById("replyText_error2").innerHTML= checkvalue;
        document.getElementById("replyText_error2").style.display = '';
        document.getElementById(eid).value="Enter the company name";
        }
        else if(alphaCheck == null)
        {
        document.getElementById("replyText_error2").innerHTML= "First character of the name must be alphanumeric";
        document.getElementById("replyText_error2").style.display = '';
        document.getElementById(eid).value="Enter the company name";
        }
        else
        {
            objErr2.innerHTML= '';
            objErr2.parentNode.style.display = 'none';
        }
    }

    if( eid == 'name3id')
    {
        if(checkvalue != true)
        {
            document.getElementById("replyText_error3").innerHTML= checkvalue;
            document.getElementById("replyText_error3").style.display = '';
            document.getElementById(eid).value="Enter the company name";
        }
        else if( alphaCheck == null)
        {
        document.getElementById("replyText_error3").innerHTML= "First character of the name must be alphanumeric";
        document.getElementById("replyText_error3").style.display = '';
        document.getElementById(eid).value="Enter the company name";
        }
        else
        {
           document.getElementById("replyText_error3").innerHTML= '';
           document.getElementById("replyText_error3").style.display = 'none' ;
        }

   }

    if( eid == 'name4id')
    {
        if(checkvalue != true)
        {
        document.getElementById("replyText_error5").innerHTML= checkvalue;
        document.getElementById("replyText_error5").style.display = '';
        document.getElementById(eid).value="Enter the company name";
        }
        else if( alphaCheck == null)
        {
        document.getElementById("replyText_error5").innerHTML= "First character of the name must be alphanumeric";
        document.getElementById("replyText_error5").style.display = '';
        document.getElementById(eid).value="Enter the company name";
        }
        else
        {
            objErr5.innerHTML= '';
            objErr5.parentNode.style.display = 'none';
        }

   }


}


// Removes the predefined text from the company name add text field
function removeText(eid)
{  if(document.getElementById(eid).value == 'Enter the company name')
    document.getElementById(eid).value= '';}


// Fucntion to validate fields on the overlay
function addValidateFields(element)
{
     var objErr4 = document.getElementById('replyText_error4');
     var name = new Array();
     var logo = new Array();
     name[0]= document.getElementById("name1id").value;
     logo[0]= logo1;
     name[1]= document.getElementById("name2id").value;
     logo[1]= logo2;
     name[2]= document.getElementById("name3id").value;
     logo[2]= logo3;
    var i=0;
    var flag1=0;
    var flag2=0;

    while(i<3)
    {
        //checking if all fields empty
        if( is_blank(name[i]) == false && is_blank(logo[i]) == false)
        ++flag2;
        //checking main validation condition
        if( (is_blank(name[i]) == false && is_blank(logo[i]) == false) ||( is_blank(name[i]) == true && is_blank(logo[i]) == true)){ }
        else
        flag1=1;
        ++i;
   }
    if(flag2==3)
    {
        alert('No value submitted');
        hideOverlay();
    }

   if( flag2 <3 && flag1 != 1)
    {
	   element.disabled = true;
        var k;
        var submitL=0;
        var totLogo=0;
        for( k=0;k<3;k++)
        {
            if( is_blank(name[k]) == true && imageDone[k] == 1 )
            totLogo++;
        }
        for( k=0;k<3;k++)
        {

            if( is_blank(name[k]) == true && imageDone[k] == 1 )
                {


                    submitL++;
                    name[k]=name[k].replace(/&/gi,'xxxx');
                    var url ="/enterprise/Enterprise/set_companylogo/";
                    var data = 'name='+name[k]+'&logo='+logo[k];
                    if(k+1 == totLogo)
                    new Ajax.Request (url,{method:'post', parameters: data, onSuccess:function () {
				doneLogo(submitL);}});
                    else
                    new Ajax.Request (url,{method:'post', parameters: data, onSuccess:function () {
				nothing();}});


                }
        }





    }


    if(flag1 == 1){

            document.getElementById("replyText_error4").innerHTML= 'Please ensure that company name is provided for the company logo uploaded and company logo is uploaded for the company name provided';
            document.getElementById("replyText_error4").style.display = '';
    }else
        {
             objErr4.innerHTML= '';
             objErr4.parentNode.style.display = 'none';
        }
 }


function modValidateFields(){

    var name, logo,url,data;
    name= document.getElementById("name4id").value;
    logo= logo4;
    var flag=0;
    if ( is_blank(logo)== false && name== mname)
    flag=0; // No changes made to the logo listing, DB will not be troubled !!

    if( is_blank(logo)== false && is_blank(name)== true && name !=mname && (validateStr(name,"Company Name",50,3)== true))
    flag=1; // Valid Changes made to the company name in the listing & no change to logo picture

    if( is_blank(logo)== true && name == mname)
    flag=2; // Valid changes made to the logo picture in the listing and no change to the name

    if( is_blank(logo)== true && is_blank(name )== true && name != mname && (validateStr(name,"Company Name",50,3)== true))
    flag=3; // changes made to logo picture as well as the company name

    switch(flag)
     {  case 0:     alert('No changes made');
                    hideOverlay();
                    break;

        case 1:     logo=murl;
                    url ="/enterprise/Enterprise/mod_companylogo/";
                    data = 'name='+name+'&logo='+logo+'&id='+mid;
                    new Ajax.Request (url,{method:'post', parameters: data, onSuccess:function () {nothing();}});
                    alert('Modified Succesfully');
                    hideOverlay();
                    break;

        case 2:     url ="/enterprise/Enterprise/mod_companylogo/";
                    data = 'name='+name+'&logo='+logo+'&id='+mid;
                    new Ajax.Request (url,{method:'post', parameters: data, onSuccess:function () {nothing();}});
                    alert('Modified Succesfully');
                    hideOverlay();
                    break;

        case 3:     url ="/enterprise/Enterprise/mod_companylogo/";
                    data = 'name='+name+'&logo='+logo+'&id='+mid;
                    new Ajax.Request (url,{method:'post', parameters: data, onSuccess:function () {nothing();}});
                    alert('Modified Succesfully');
                    hideOverlay();
                    break;

        default:    alert("No changes made");
                    hideOverlay();
                    break;
      }location = '/enterprise/Enterprise/add_companylogo/';

}

function showLogo1(response){

   document.getElementById('upload1').style.display = "none";
    if(response== 'Image size must be 120*40 px' || response== 'Please select a photo to upload' ||response== 'Images of type jpeg,gif,png are allowed')
    { imageDone[0]=0; alert(response); }
    else
    {logo1= response;
     imageDone[0]=1;
     document.getElementById('logo1').src=response;}
}

function showLogo2(response){
    document.getElementById('upload2').style.display = "none";
    if(response== 'Image size must be 120*40 px' || response== 'Please select a photo to upload' ||response== 'Images of type jpeg,gif,png are allowed')
    { imageDone[1]=0 ; alert(response); }
    else
    {logo2= response;
     imageDone[1]=1;
     document.getElementById('logo2').src=response;}
}

function showLogo3(response){
    document.getElementById('upload3').style.display = "none";
    if(response== 'Image size must be 120*40 px' || response== 'Please select a photo to upload' ||response== 'Images of type jpeg,gif,png are allowed')
    {imageDone[2]=0; alert(response);}
    else
    {logo3= response;
     imageDone[2]=1;
     document.getElementById('logo3').src=response;}
}

function showLogo4(response){
    document.getElementById('upload4').style.display = "none";
    if(response== 'Image size must be 120*40 px' || response== 'Please select a photo to upload' ||response== 'Images of type jpeg,gif,png are allowed')
    {imageDone[3]=0; alert(response);}
    else
    {logo4= response;
     imageDone[3]=1;
     document.getElementById('logo4').src=response;}
}
function uploader(eid){document.getElementById(eid).style.display = "block";}

function askDelete(delid, delname){


      var url ="/enterprise/Enterprise/check_deletelogo/";
      var data = 'id='+delid+'&name='+delname;
      new Ajax.Request (url,{method:'post', parameters: data, onSuccess:function (request) {checkDelete(request.responseText);}});


}

function checkDelete(response)
{

     var ajaxresponse= eval(response);
     if( ajaxresponse[0] == 0 )
        {


                var answer1 = confirm("Are you sure you want to delete "+ajaxresponse[2]);
                if (answer1){

                                var url1 ="/enterprise/Enterprise/del_companylogo/";
                                var data1 = 'id='+ajaxresponse[1];
                                new Ajax.Request (url1,{method:'post', parameters: data1});
                                location = "/enterprise/Enterprise/add_companylogo/";

                            }

        }
      if( ajaxresponse[0] != 0 )
        {

                var ik=0;
                var iname='=>  ';
                while (ajaxresponse[ik] != null)
                {

                        iname= iname+(ik+1)+'.'+ajaxresponse[ik]+' ';
                        ik++;
                }
                alert("Unable to delete !! This company is attached with the following listings"+iname);
        }

}
function doneLogo(submitL)
{
    if ( submitL > 0)
        {   document.getElementById("replyText_error4").style.display = "none";  alert('Form Submitted Succesfully');}
        else
        alert('No value submitted');

        hideOverlay();
        location = '/enterprise/Enterprise/add_companylogo/';
}



function nothing(){

}

</script>



<div class="mlr10">

<div class="float_L"><div class="fcOrg Fnt18 bld lineSpace_25">Companies Logos</div></div>

<div class="float_R"><div><input type="button" class="entr_Allbtn ebtn_1" value="&nbsp;" onclick ="openAddOverlay()"/></div></div>

<div class="clear_B">&nbsp;</div>

</div>


<!-- For the overlay inner html-->

<div style ="display : none" id ="addOverLay">

<div class="w790">

  <div class="">



        <div class="pf10">


            <!-- Begin first row in overlay-->

                <div class="wdh100 mb10">

                <div class="float_L mr10" ><img id="logo1" src="" /></div>

                <!-- ajax call to this form is yet to be defined-->
                <form id="form1" name="form1" enctype="multipart/form-data" action="/enterprise/Enterprise/uploadFile"   onSubmit="AIM.submit(this,{'onStart': startCallback, 'onComplete': showLogo1});"  autocomplete="off" method="post">

                <div class="float_L mr10" style="margin-top:1px"><input type="text" class="otBx" value="Enter the company name" id="name1id"   onclick="removeText('name1id')"  onblur= "checkError('name1id') " /></div>

                <div class="float_L mr10" id="file1" ><input name="myImage[]" id="myImage1" type="file" size="25" /></div>

                <div class="float_L">

                   <div><input type="submit" value="Upload" onClick="javascript : uploader('upload1')"/></div>

                    <div id="upload1" style="display: none"><img  src="/public/images/ajax-loader.gif" align="absmiddle" /> <span class="updTxt">Uploading</span></div>

                      </div>

                </form>
                <div class="clear_B">&nbsp;</div>

            </div>


<!-- end first overlay row-->

<!-- Error display div -->

            <div class="wdh100 mb10">
                <div class="float_L mr10">
                    <div class=""><div class="errorMsg" id="replyText_error1"></div></div>
                </div>
                <div class="clear_B">&nbsp;</div>

            </div>

    <!-- Error display div ends-->








     <!-- Begin Second row for adding company -->


            <div class="wdh100 mb10">

                <div class="float_L mr10"><img id="logo2" src="" /></div>


                <!-- ajax call to this form is yet to be defined-->
                <form id="form2" name="form2" enctype="multipart/form-data" action="/enterprise/Enterprise/uploadFile"   onSubmit="AIM.submit(this,{'onStart': startCallback, 'onComplete': showLogo2});" autocomplete="off" method="post">

                <div class="float_L mr10" style="margin-top:1px"><input type="text" class="otBx" value="Enter the company name" id="name2id"   onclick="removeText('name2id')"  onblur= "checkError('name2id') " /></div>

                <div class="float_L mr10" id="file2"><input name="myImage[]" type="file" size="25" id="myImage2" /></div>

                <div class="float_L">

                 <div><input type="submit" value="Upload" onClick="javascript : uploader('upload2')" /></div>

                 <div id="upload2" style="display : none"><img src="/public/images/ajax-loader.gif" align="absmiddle" /> <span class="updTxt">Uploading</span></div>

                 </div>

                </form>

                <div class="clear_B">&nbsp;</div>

            </div>



     <!-- End Second row for adding company-->




     <!-- Error display div -->

            <div class="wdh100 mb10">
                <div class="float_L mr10">
                    <div class=""><div class="errorMsg" id="replyText_error2"></div></div>
                </div>
                <div class="clear_B">&nbsp;</div>

            </div>

    <!-- Error display div ends-->




    <!-- Begin Third row for adding company -->


        <div class="wdh100 mb10">

                <div class="float_L mr10"><img id="logo3" src="" /></div>

                 <!-- ajax call to this form is yet to be defined-->
                 <form id="form3" name="form3" enctype="multipart/form-data" action="/enterprise/Enterprise/uploadFile"   onSubmit="AIM.submit(this,{'onStart': startCallback, 'onComplete': showLogo3});" autocomplete="off" method="post">


                <div class="float_L mr10" style="margin-top:1px"><input type="text" class="otBx" value="Enter the company name" id="name3id"  onclick="removeText('name3id')" onblur="checkError('name3id')"/></div>

                <div class="float_L mr10" id="file3"><input name="myImage[]" type="file" size="25" id="myImage3"/></div>

                <div class="float_L"><div><input type="submit" value="Upload" onClick="javascript : uploader('upload3')" /></div>

                <div id="upload3" style="display : none"><img src="/public/images/ajax-loader.gif" align="absmiddle" /> <span class="updTxt">Uploading</span></div>

                </div>

                 </form>

                <div class="clear_B">&nbsp;</div>

            </div>
    <!-- End third row for adding company-->



    <!-- Error display div -->

            <div class="wdh100 mb10">
                <div class="float_L mr10">
                    <div class=""><div class="errorMsg" id="replyText_error3"></div></div>
                </div>
                <div class="clear_B">&nbsp;</div>

            </div>

    <!-- Error display div ends-->


     <!-- Error display div -->

            <div class="wdh100 mb10">
                <div class="float_L mr10">
                    <div class=""><div class="errorMsg" id="replyText_error4"></div></div>
                </div>
                <div class="clear_B">&nbsp;</div>

            </div>

    <!-- Error display div ends-->




            <div class="bbm">&nbsp;</div>

            <div align="center" class="mtb10">

                <input type="button" value="Preview & Submit" class="searchWidgetBtn_n" onClick="javascript: addValidateFields(this)"/> <!--<input type="button" value="&nbsp;" class="entr_Allbtn ebtn_5" onClick="javascript:hideOverlay();" />-->

            </div>





        </div>

    </div>

</div>
</div>


<!--End of Overlay Inner HTML -->


<!-- Begin overlay for modifying the company name/logo --->




<div style ="display : none" id ="modOverLay">

<div class="w790">

  <div class="">



        <div class="pf10">


            <!-- Begin first row in overlay-->

                <div class="wdh100 mb10">

                <div class="float_L mr10" ><img id="logo4" src="" /></div>

                <!-- ajax call to this form is yet to be defined-->
                <form id="form1" name="form1" enctype="multipart/form-data" action="/enterprise/Enterprise/uploadFile"   onSubmit="AIM.submit(this,{'onStart': startCallback, 'onComplete': showLogo4});"  autocomplete="on" method="post">

                <div class="float_L mr10" style="margin-top:1px"><input type="text" class="otBx"  id="name4id"  value="" onclick="removeText('name4id')"  onblur= "checkError('name4id') " /></div>

                <div class="float_L mr10" id="file1" ><input name="myImage[]" id="myImage4" type="file" size="25" /></div>

                <div class="float_L">

                   <div><input type="submit" value="Upload" onClick="javascript : uploader('upload4')"/></div>

                    <div id="upload4" style="display : none"><img src="/public/images/ajax-loader.gif" align="absmiddle" /> <span class="updTxt">Uploading</span></div>

                      </div>

                </form>
                <div class="clear_B">&nbsp;</div>

            </div>


<!-- end first overlay row-->

<!-- Error display div -->

            <div class="wdh100 mb10">
                <div class="float_L mr10">
                    <div class=""><div class="errorMsg" id="replyText_error5"></div></div>
                </div>
                <div class="clear_B">&nbsp;</div>

            </div>

    <!-- Error display div ends-->


 <div class="bbm">&nbsp;</div>

            <div align="center" class="mtb10">

                <input type="button" value="Preview & Submit" class="searchWidgetBtn_n" onClick="javascript: modValidateFields()"/> <!-- <input type="button" value="&nbsp;" class="entr_Allbtn ebtn_5" onClick="javascript:hideOverlay();" />-->

            </div>





        </div>

    </div>

</div>
</div>


<!-- Modifying overlay inner html ends here -->

<div class="lineSpace_5">&nbsp;</div>

                <!--Start_Border-->

                <div class="mlr10">

                <div class="raised_greenGradient_ww">

                <b class="b2"></b><b class="b3"></b><b class="b4"></b>

                <div class="boxcontent_greenGradient_ww">

                <div class="pf10">



                                <!--Begin_Sorting-->

                                <div id="alphaSort">
                                <strong class="Fnt14">Sort by:</strong>

                                <?php if($sortClass == "All")
                                echo'<a href="/enterprise/Enterprise/add_companylogo/All" class="all">All</a>';
                                else  echo'<a href="/enterprise/Enterprise/add_companylogo/All" class="">All</a>';

                                $indArray= array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
                                for( $i=0;$i<27;$i++)
                                {

                                    echo '<a id= "'.$indArray[$i].'"href="/enterprise/Enterprise/add_companylogo/'.$indArray[$i].'">';
                                    echo $indArray[$i].'&nbsp; &nbsp;  </a>';

                                 }?>

                                </div>
                                <?php
                                if ($sortClass != "All") {
                                ?>
                                <script type="text/JavaScript">
                                    document.getElementById('<?php echo $sortClass?>').className = "alpha";
                                </script>
                                <?php } ?>
                                <!--Sorting Over-->




                            <!--Begin_Showing_Pagination-->




                            <?php
                            $ind= 0;
                            foreach($companyLogoListing as $n1 => $m1)
                            {
                                    $ind++;

                            }
                                ?>

                            <div class="wdh100 mtb10">

                            <div class="float_L"><div class="lineSpace_25">Showing <?php echo $ind; ?> out of <?php echo $totalCount;?> companies</div></div>

                            <div class="float_R"><div class="pagingID"><?php echo $paginationHTML; ?></div></div>

                            <div class="clear_B">&nbsp;</div>

                            </div>

                            <!--End_Showing_Pagination-->


                            

                            <!--Begin_Results-->
                            <?php



                           $index= $rstart;
                            foreach($companyLogoListing as $n => $m)
                            {
                                    $index++;
                                    echo '<table cellpadding="0" cellspacing="0" border="0" width="100%">';
                                    echo'<tr>';
                                    echo'<td class="bld Fnt14" width="35" valign="middle">';
                                    echo $index.'</td>';

                                    $rev_arr= array_reverse($m,true);

                                    foreach($rev_arr as $key => $value)
                                    {

                                        if($key != 'logo_url' && $key != 'id')
                                        {
                                            echo'<td width="400" class="Fnt14" valign="middle">';
                                            echo $value.'</td>';
                                            $modname= $value;
                                        }

                                        if($key == 'logo_url')
                                        {
                                            echo'<td width="150"><img src="'.addingDomainNameToUrl(array('url' => $value , 'domainName' =>MEDIA_SERVER)).'" border="0" /></td>';
                                            $modurl= $value;

                                        }
                                        if($key == 'id')
                                        $modid = $value;


                                    }
                                    $location = '/enterprise/Enterprise/del_companylogo/'.$modid;
                                ?>


<td>
    <input type='button' class='entr_Allbtn ebtn_2' value='&nbsp;' onclick ="openModOverlay(<?php echo $modid ?> ,'<?php echo $modname ?>','<?php echo addingDomainNameToUrl(array('url' => $modurl , 'domainName' =>MEDIA_SERVER)); ?>')" />&nbsp;&nbsp;&nbsp;
    <input type="button" class="entr_Allbtn ebtn_3" value="&nbsp;"  onclick= "askDelete('<?php echo $modid?>','<?php echo $modname?>')"/>

</td>
                          <?php echo '</tr><tr><td colspan="4" class="dLine">&nbsp;</td></tr></table>';
                            } ?>

                           <!--End_Results-->



                            <div class="bbm">&nbsp;</div>


                            <!--Start_Showing_Pagination-->

                            <div class="wdh100 mt10">

                                <div class="float_L"><div class="lineSpace_25">Showing <?php echo $ind;?> out of <?php echo $totalCount; ?> companies</div></div>

                                <div class="float_R"><div class="pagingID"><?php echo $paginationHTML; ?></div></div>

                             <!--   <div class="float_R"><div><img src="public/images/paging.gif" /></div></div>-->

                                <div class="clear_B">&nbsp;</div>

                            </div>

                            <!--End_Showing_Pagination-->



                        </div>

                    </div>

                                                                <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>

                                                </div>

        </div>

        <!--End_Border-->

        <div class="h50">&nbsp;</div>

        <div>

            <div class="txt_align_c lineSpace_20">

                <span style="color:#0000FF">

                                <a href="#">About Us</a> - <a href="#">Privacy Policy</a> - <a href="#">Feedback</a> - <a href="#">FAQ</a> - <a href="#">Contact Us</a> - <a href="#">Site Map</a> - <a href="#">Browse: Institute and Career</a> - <a href="#">Terms and Conditions</a><br>Our Partners: <a href="#">Jobs</a> - <a href="#">Jobs for freshers</a> - <a href="#">Real Estate</a> - <a href="#">Jobs in Middle East</a> - <a href="#">Real Estate Agent</a> - <a href="#">Matrimonials</a> - <a href="#">Insurance Comparison</a> - <a href="#">School Online</a>

                                                                </span>

                <br>Trade Marks belongs to the respective owners.<br>

                                                                <span class="Fnt11">Copyright &copy; 2009 Info Edge India Ltd. All right reserved.</span>

            </div>

        </div>

    </div>
</body>
</html>
