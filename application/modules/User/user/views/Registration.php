<?php        

$this->load->helper('form');


$headerComponents = array(

		'css'      =>        array('user'),
		
		'js'         =>            array('Registration','user','tooltip','common','cityList','EduList','unRestrictedCityList','imageUpload'),

		'title'      =>        'User Registration',

		'tabName'          =>        'Register',

		'taburl' =>  SITE_URL_HTTPS.'user/Userregistration/index',  

		'metaKeywords'  =>'Some Meta Keywords',

		'product' => '',
		'search'=>"false",
        'bannerProperties' => array('pageId'=>'', 'pageZone'=>''),
        
		'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),

		'callShiksha'=>1



);

$this->load->view('common/homepage', $headerComponents);



?>
<script>
    var QuickSignUp = 0;
    var isRequestUser = 0;
    var isQuickSignUpUser = 0;
if(isUserLoggedIn){
<?php 
if(isset($validateuser[0]['orusergroup']) && ($validateuser[0]['orusergroup'] != "quicksignupuser" && $validateuser[0]['orusergroup'] != "requestinfouser") && $validateuser[0]['orusergroup'] != "tempuser") {
?>
this.location='/';
<?php } 
if(isset($validateuser[0]['orusergroup']) && ($validateuser[0]['orusergroup'] == "requestinfouser" || $validateuser[0]['orusergroup'] == "tempuser")) { ?>
isRequestUser = 1;
<?php } if(isset($validateuser[0]['orusergroup']) && ($validateuser[0]['orusergroup'] == "quicksignupuser")) { ?>
isQuickSignUpUser = 0;
  <?php }?>
  }
    
</script>

<!--Start_GreenGradient-->

<!-- End-->

<div class="lineSpace_8">&nbsp;</div>



<!--Start_Center-->

<div class="mar_full_10p">

<!--Start_Left_Panel-->

<div id="left_Panel" style="margin:0">

<!--Course_TYPE-->

<!--<div class="raised_blue_L">

<b class="b2"></b>

<div class="boxcontent_blue">

<div class="lineSpace_5">&nbsp;</div>

<div class="normaltxt_11p_blk bld"><span class="mar_left_10p">Why Join</span></div>

<div class="lineSpace_5">&nbsp;</div>

<div class="normaltxt_11p_blk fontSize_16p bld"><span class="mar_left_10p">Shiksha?</span></div>

<div class="lineSpace_11">&nbsp;</div>

<div class="row">

<div class="normaltxt_11p_blk w9 mar_left_5p">

<div class="row">

<div><img src="/public/images/eventArrow.gif" align="left" style="position:relative; top:5px"  /></div>

<div style="margin-left:12px">Get free Information from more then 400 colleges, 3000 courses around the world</div>

</div>
<div class="lineSpace_5">&nbsp;</div>
<div class="row">

<div><img src="/public/images/eventArrow.gif" align="left" style="position:relative; top:5px"  /></div>

<div style="margin-left:12px">Discuss and participate in forums</div>

</div>
<div class="lineSpace_5">&nbsp;</div>
<div class="row">

<div><img src="/public/images/eventArrow.gif" align="left" style="position:relative; top:5px"  /></div>

<div style="margin-left:12px">Ask our counsellors free advice</div>

</div>
<div class="lineSpace_5">&nbsp;</div>
<div class="row">

<div><img src="/public/images/eventArrow.gif" align="left" style="position:relative; top:5px"  /></div>

<div style="margin-left:12px">Get Access to Information on Visas, admission process, accerditation</div>

</div>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                

</div>

</div>

<div class="lineSpace_11">&nbsp;</div>

</div>

<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>

</div>                          

<div class="lineSpace_10">&nbsp;</div>-->

<!--End_Course_TYPE-->

</div>

<!--End_Left_Panel-->




	<?php if($editform == 0)
    $text = "Registration Form";
    else
    $text = "Complete your Registration Information to continue";
    ?>

<!--Start_Mid_Panel-->

<!--<div style="margin-left:164px;">-->
<div>

<div class="row normaltxt_11p_blk" style="margin-bottom:10px">
	<div class="float_R">All fields marked with <span class="redcolor">*</span> are Required&nbsp;</div>
    <div class="OrgangeFont fontSize_13p bld"><?php echo $text?></div>
	<div class="clear_R"></div>
</div>
<!--                         <div class="r2_2 float_R" style="color:#666666;">All fields marked with <span class="redcolor">*</span> are Required</div>
                                 <div class="clear_L"></div>-->


<div class="formField errorPlace"><div class="errorMsg" id= "Register_error"></div></div>

<div style="float:left; width:100%;">

<div class="raised_lgraynoBG">

<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>

<div class="boxcontent_lgraynoBG">

<?php

$this->load->view('user/Registration_form');

?>                                           

</div>

<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>                                           

</div>

</div>

</div>

<!--End_Mid_Panel-->

<br clear="all" />

</div>

<!--End_Center-->



<!--Start_Footer-->

<?php 
$footer = array('pageId'=>'', 'pageZone'=>'');
$this->load->view('common/footer',$footer);?>
<!--End_Footer-->

