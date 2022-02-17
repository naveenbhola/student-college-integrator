<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<script>
var SITE_URL = '<?php echo base_url() ."/";?>';
</script>
<script language="javascript" src="/public/js/blog.js"></script>
        <script language="javascript" src="/public/js/common.js"></script>
        <script language="javascript" src="/public/js/md5.js"></script>
        <script language="javascript" src="/public/js/user.js"></script>
        <script language="javascript" src="/public/js/prototype.js"></script>
        <script language="javascript" src="/public/js/payment.js"></script>
        <script language="javascript" src="/public/js/utils.js"></script>



<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Payment</title>
<link href="/public/css/header.css" type="text/css" rel="stylesheet" />
<link href="/public/css/mainStyle.css" type="text/css" rel="stylesheet" />
<link href="/public/css/raised_all.css" type="text/css" rel="stylesheet" />
<link href="/public/css/footer.css" type="text/css" rel="stylesheet" />
<style>
.raised_listing {background: transparent; } 
.raised_listing  .b1, .raised_listing  .b2, .raised_listing  .b3, .raised_listing  .b4, .raised_listing  .b1b, .raised_listing  .b2b, .raised_listing  .b3b, .raised_listing  .b4b {display:block; overflow:hidden; font-size:1px;} 
.raised_listing  .b1, .raised_listing  .b2, .raised_listing  .b3, .raised_listing  .b1b, .raised_listing  .b2b, .raised_listing  .b3b {height:1px;} 
.raised_listing  .b2 {background:#FBE8B0; border-left:1px solid #FBE8B0; border-right:1px solid #FBE8B0;} 
.raised_listing  .b3 {background:#FFFFFF; border-left:1px solid #FBE8B0; border-right:1px solid #FBE8B0;} 
.raised_listing  .b4 {background:#FFFFFF; border-left:1px solid #FBE8B0; border-right:1px solid #FBE8B0;} 
.raised_listing  .b4b {background:#FFFFFF; border-left:1px solid #FBE8B0; border-right:1px solid #FBE8B0;} 
.raised_listing  .b3b {background:#FFFFFF; border-left:1px solid #FBE8B0; border-right:1px solid #FBE8B0;} 
.raised_listing  .b2b {background:#FFFFFF; border-left:1px solid #FBE8B0; border-right:1px solid #FBE8B0;} 
.raised_listing  .b1b {margin:0 5px; background:#FBE8B0;} 
.raised_listing  .b1 {margin:0 5px; background:#ffffff;} 
.raised_listing  .b2, .raised_listing  .b2b {margin:0 3px; border-width:0 2px;} 
.raised_listing  .b3, .raised_listing  .b3b {margin:0 2px;} 
.raised_listing  .b4, .raised_listing  .b4b {height:2px; margin:0 1px;} 
.raised_listing  .boxcontent_listing {display:block; background-color:#FFFFFF; border-left:1px solid #FBE8B0; border-right:1px solid #FBE8B0;} 

</style>
</head>
<style>
#header {
float:none;
font-size:85%;
line-height:normal;
width:100%;
}
#header ul {
list-style-image:none;
list-style-position:outside;
list-style-type:none;
margin:0pt;
padding:0px 0px 0pt;
}
#header li {
background:transparent url(right.gif) no-repeat scroll right top;
float:left;
margin:0pt;
padding:0pt 5px 0pt 0pt;
}
#header a {
background:transparent url(left.gif) no-repeat scroll left top;
color:#99CCFF;
display:block;
float:left;
font-weight:bold;
padding:5px 7px 4px 20px;
text-decoration:none;
}
#header a {
float:none;
}
#header a:hover {
color:#FFFFFF;
}
#header #current {
background-image:url(right_on.gif);
}
#header #current a {
background-image:url(left_on.gif);
color:#333333;
padding-bottom:5px;
}
</style>

<body>
<!--StartTopHeaderWithNavigation-->
<div id="headerGradienthome">
<?php
$this->load->view('payment/paymentHeader.php');
?>
    
</div>
<div class="lineSpace_9">&nbsp;</div>
<div class="mar_full_10p">
	<div id="headernoGradient" style="height:57px">
		<!--<div style="width:476px; height:62px; background-color:#FFFFFF; float:right; border:1px solid #E6E6E6">&nbsp;</div>-->
		<div class="float_L"><a style="text-decoration: none;" href="/"><img border="0" src="/public/images/nshik_ShikshaLogo1.gif" alt="" /></a></div>		
	</div>
</div>
<div class="lineSpace_9">&nbsp;</div>
<!--StartTopHeaderWithNavigation-->
<div class="lineSpace_28">&nbsp;</div>
<!--End_GreenGradient-->


<div class="mar_full_10p">
	<div class="row">
		<div class="float_R" style="width:49%">
			<div class="raised_listing">
				<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
				<div class="boxcontent_listing">
					<div class="bglisting bld"><span style="margin-left:10px; position:relative; top:4px">Paid Listing</span></div>
					<div class="mar_full_10p normaltxt_11p_blk fontSize_12p">
						<div class="lineSpace_20">&nbsp;</div>
						<div><img src="/public/images/check_icon.gif" align="absmiddle"/>Duration 1 months</div>
						<div class="lineSpace_20">&nbsp;</div>
						<div><img src="/public/images/check_icon.gif" align="absmiddle"/>Shown in sponsored results for specific keyword</div>
						<div class="lineSpace_20">&nbsp;</div>
						<div><img src="/public/images/check_icon.gif" align="absmiddle"/>Logo of the Institute can be displayed along with search results</div>
						<div class="lineSpace_20">&nbsp;</div>
						<div><img src="/public/images/check_icon.gif" align="absmiddle"/>Can upload upto 3 picture, 3 Video &amp; 3 files</div>
						<div class="lineSpace_20">&nbsp;</div>
						<div><img src="/public/images/check_icon.gif" align="absmiddle"/>Featured Presence on upto 3 category home pages</div>
						<div class="lineSpace_20">&nbsp;</div>
					</div>
					<div class="row">
						<div class="buttr3">
							<button class="btn-submit19" value="" type="button" style="width:130px;" onclick="window.location='<?php echo site_url().'/payment/payment'; ?>'">
								<div class="btn-submit19"><p class="btn-submit20 btnTxtBlog" style="padding:15px 7px 15px 7px">Post Paid Listing</p></div>
							</button>
						</div>
						<div class="clear_L"></div>
					</div>
				</div>
				<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
			</div>
		</div>
		<div class="float_L" style="width:49%">
			<div class="raised_listing">
				<b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b>
				<div class="boxcontent_listing">
					<div class="bglisting bld"><span style="margin-left:10px; position:relative; top:4px">Free Listing</span></div>
					<div class="mar_full_10p normaltxt_11p_blk fontSize_12p">
						<div class="lineSpace_20">&nbsp;</div>
						<div><img src="/public/images/check_icon.gif" align="absmiddle"/>Duration 6 months</div>
						<div class="lineSpace_20">&nbsp;</div>
						<div><img src="/public/images/check_icon.gif" align="absmiddle"/>Not Shown in sponsored results</div>
						<div class="lineSpace_20">&nbsp;</div>
						<div><img src="/public/images/check_icon.gif" align="absmiddle"/>Logo of the Institute not displayed along with search results</div>
						<div class="lineSpace_20">&nbsp;</div>
						<div><img src="/public/images/check_icon.gif" align="absmiddle"/>Can upload Only 1 picture</div>
						<div class="lineSpace_20">&nbsp;</div>
						<div class="lineSpace_20">&nbsp;</div>
						<div class="lineSpace_14">&nbsp;</div>
					</div>
					<div class="row">
						<div class="buttr3">
							<button class="btn-submit19" value="" type="button" style="width:130px;" onclick="window.location='<?php echo site_url().'/payment/paymentFree'; ?>'">
								<div class="btn-submit19"><p class="btn-submit20 btnTxtBlog" style="padding:15px 7px 15px 7px">Post Free Listing</p></div>
							</button>
						</div>
						<div class="clear_L"></div>
					</div>
				</div>
				<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
			</div>
		</div>
		<div class="clear_R"></div>
	</div>
</div>

<div class="lineSpace_20">&nbsp;</div>
<div class="lineSpace_20">&nbsp;</div>
<div class="lineSpace_20">&nbsp;</div>
<div class="lineSpace_20">&nbsp;</div>
<div class="lineSpace_20">&nbsp;</div>
<div class="lineSpace_20">&nbsp;</div>
<div class="lineSpace_20">&nbsp;</div>
<div class="lineSpace_20">&nbsp;</div>
<div class="lineSpace_20">&nbsp;</div>
<?php
$this->load->view('common/disablePageLayer.php');
    $this->load->view('common/overlay');
    $this->load->view('common/footer');
    ?>

</body>
</html>



