<?php if($siteIdentifier != 'mobileSite'){?><!DOCTYPE html> 
  <html> 
  <head> 
<!-- Load CSS files -->
  <link rel="stylesheet" href="//<?php echo CSSURL; ?>/public/mobile5/css/<?php echo getCSSWithVersion("mobileApp",'nationalMobile'); ?>" >

  <link rel="stylesheet" href="//<?php echo CSSURL; ?>/public/mobile5/css/vendor/<?php echo getCSSWithVersion("jquery.mobile-1.4.5",'nationalMobileVendor'); ?>" > 
      
  <!-- Load JS files --> 
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script> 
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script> 
  <script src="//<?php echo JSURL; ?>/public/mobile5/js/vendor/<?php echo getJSWithVersion("jquery.mobile-1.4.5.min","nationalMobileVendor"); ?>"></script> 
         
    <meta charset="utf-8"> 
   <title>User Point System</title> 
    <meta name="viewport" content="width=device-width, initial-scale=1"> 
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'> 
    </head>
<?php }?><?php if($siteIdentifier == 'mobileSite'){
$headerComponents = array(
   'mobilecss' => array('mobileApp'),
   );
$borderStyle="border-bottom:7px solid #efefee";  
$this->load->view('header',$headerComponents); ?>
<div id="wrapper" data-role="page" style="min-height:413px;padding-top:40px;">

<?php
//Check if user came directly on this page. If the referrer is not shiksha, we have to display the Hamburger menu
$displayHamburger = false;
if(!$_SERVER['HTTP_REFERER']){  //If no referer is defined, show Hamburger menu
        $displayHamburger = true;
}
else if( strpos($_SERVER['HTTP_REFERER'],'shiksha') === false){ //If referer is not from Shiksha, show Hamburger menu
        $displayHamburger = true;
}
if($displayHamburger){
        echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
}
        echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel',false,'userPointSystem');
?>

    <header id="page-header"  class="header ui-header-fixed" data-role="header" data-tap-toggle="false" data-position="fixed">
     <?php echo Modules::run('mcommon5/MobileSiteHamburger/getMainHeader',$displayHamburger,'', 'userPointSystem');?>
    </header>

    <div class="mobile-container" data-role="content" data-enhance="false">
      <?php 
        $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
      ?>
      <div class="cont-content" >
      <div class="cont-heading" style="text-align:center;">
            <a href="javascript:void(0);">User Point System</a>
      </div>

  <?php }else{ ?>
        <div class="mobile-container" data-role="page">
        <div class="cont-content" data-role="main" data-enhance="false">
  <?php } ?>

    <section class="wht-not-do" style="padding-top:1em">
     <div class="user-txt user-point-content">
          <p>To encourage participation and reward Shiksha members, Shiksha has a system of points and user levels.
The levels and points denote a user&#39;s contribution to the Cafe.</p>
<p>Users on Shiksha Cafe mainly fall into 4 User levels with 18 sub levels. You start by becoming a Beginner-Level 1 and can reach up to Scholar-Level 18.</p>
<p>In order to move up the levels (i.e. Guide and Expert level), one needs to have both quality and quantity of answers/comments. Garner points for quantity by answering as many questions as you can and the quality will be judged by the upvotes that an answer gets by the users.</p>
       </div>
       
       <a href="#userpointsystem1" data-inline="true" data-rel="dialog" data-transition="slide" class="do-cont user-point-system" class="do-cont user-point-system"  onclick="myInterface.setTitle('User levels and points');">
         <div class="div-left">
	    <div class="wht-txt" >
		<p><span class="numeric-text">1</span></p>
		<p style="margin-left:53px; line-height: 22px;">User levels and points</p>
	    </div>
         </div>
         <div class="div-right"> 
           <i class="mobile-sprite left-arow"></i>
         </div>
	 <div style="clear:both"></div>
       </a>
       
        <a href="#userpointsystem2" data-inline="true" data-rel="dialog" data-transition="slide" class="do-cont user-point-system" class="do-cont user-point-system" onclick="myInterface.setTitle('How you gain points..');">
         <div class="div-left">
	  <div class="wht-txt" >
	    <p><span class="numeric-text" >2</span></p>
	    <p>How you gain points and get promoted?</p>
	   </div>
         </div>
         <div class="div-right"> 
           <i class="mobile-sprite left-arow"></i>
         </div>
	 <div style="clear:both"></div>
       </a>
       
       <a href="#userpointsystem3" data-inline="true" data-rel="dialog" data-transition="slide" class="do-cont user-point-system" class="do-cont user-point-system" style="<?=$borderStyle;?>" onclick="myInterface.setTitle('How you lose points (Penalties)?');">
         <div class="div-left">
	    <div class="wht-txt" >
		<p><span class="numeric-text" >3</span></p>
		<p >How you lose points (Penalties)?</p>
	    </div>
         </div>
         <div class="div-right"> 
           <i class="mobile-sprite left-arow"></i>
         </div>
	 <div style="clear:both"></div>
       </a>
    </section>
  </div>
    <?php if($siteIdentifier == 'mobileSite'){
                $this->load->view('footerLinks'); 
                $this->load->view('footer'); 
    ?></div><?php } ?>
</div>


<div class="mobile-container" data-role="page" id="userpointsystem1" class="of-hide" data-enhance="false">
<div class="cont-content" data-role="main">
    <?php if($siteIdentifier == 'mobileSite'){?>
        <div class="cont-heading" style="margin-bottom:4px;">
            <a href="javascript:void(0);" data-rel="back"><i class="mobile-sprite ic_bck"></i>User levels and points</a>
        </div>
    <?php } ?>
  
    <section class=" cont-content wht-not-do" style="padding-top:1em;">
       <div class="user-txt user-point-content" style="text-align:center">
          <strong class="user-level-title">BEGINNER</strong>
       </div>
       
       <div class="user-level-table"> 
       	<table>
        	<tr>
            	<th style="width:30%">Levels</th>
                <th style="width:70%">Points and additional checks<span style="color:#f68d8d"></span></th>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">1</span></td>
                <td>0-24 points</td>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">2</span></td>
                <td>25-49 points</td>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">3</span></td>
                <td>50-99 points</td>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">4</span></td>
                <td>100-199 points</td>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">5</span></td>
                <td>200-399 points</td>
            </tr>
        </table>
       </div>
       
       <div class="user-txt user-point-content" style="text-align:center">
          <strong class="user-level-title">CONTRIBUTOR</strong>
       </div>
       
       <div class="user-level-table">
       	<table>
        	<tr>
            	<th style="width:30%">Levels</th>
                <th style="width:70%">Points and additional checks<span style="color:#f68d8d"></span></th>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">6</span></td>
                <td>400-699 points</td>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">7</span></td>
                <td>700-1149 points</td>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">8</span></td>
                <td>1150-1749 points</td>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">9</span></td>
                <td>1750-2499 points</td>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">10</span></td>
                <td>2500-3499 points</td>
            </tr>
        </table>
       </div>
       
       
       <div class="user-txt user-point-content" style="text-align:center">
          <strong class="user-level-title">GUIDE</strong>
       </div>
       
       <div class="user-level-table">
       	<table>
        	<tr>
            	<th style="width:30%">Levels</th>
                <th style="width:70%">Points and additional checks<span style="color:#f68d8d">*</span></th>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">11</span></td>
                <td>3500-4999 points<br><span style="font-size:12px;"><span style="color:#f68d8d">*</span>100 upvotes across all answers</span></td>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">12</span></td>
                <td>5000-7499 points<br><span style="font-size:12px;"><span style="color:#f68d8d">*</span>100 upvotes across all answers</span></td>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">13</span></td>
                <td>7500-11499 points<br><span style="font-size:12px;"><span style="color:#f68d8d">*</span>100 upvotes across all answers</span></td>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">14</span></td>
                <td>11500-17499 points<br><span style="font-size:12px;"><span style="color:#f68d8d">*</span>100 upvotes across all answers</span></td>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">15</span></td>
                <td>17500-27499 points<br><span style="font-size:12px;"><span style="color:#f68d8d">*</span>100 upvotes across all answers</span></td>
            </tr>
        </table>
       </div>
       
       <div class="user-txt user-point-content" style="text-align:center">
          <strong class="user-level-title">SCHOLAR</strong>
       </div>
       
       <div class="user-level-table">
       	<table>
        	<tr>
            	<th style="width:30%">Levels</th>
                <th style="width:70%">Points and additional checks<span style="color:#f68d8d">*</span></th>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">16</span></td>
                <td>27500-42499 points<br><span style="font-size:12px;"><span style="color:#f68d8d">*</span>250 upvotes across all answers</span></td>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">17</span></td>
                <td>42500-67499 points<br><span style="font-size:12px;"><span style="color:#f68d8d">*</span>500 upvotes across all answers</span></td>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">18</span></td>
                <td>67500 and above<br><span style="font-size:12px;"><span style="color:#f68d8d">*</span>1000 upvotes across all answers</span></td>
            </tr>
        </table>
       </div>
     </section>
</div>
</div>



<div class="mobile-container" data-role="page" id="userpointsystem2" class="of-hide">
<div class="cont-content" data-role="main">
<?php if($siteIdentifier == 'mobileSite'){?>
        <div class="cont-heading" style="margin-bottom:4px;">
            <a href="javascript:void(0);" data-rel="back"><i class="mobile-sprite ic_bck" style="top:20px; !important"></i>How you gain points and get promoted?</a>
        </div>
    <?php } ?>
    <section class="cont-content wht-not-do">
     	<div class="user-txt user-point-content" style="padding-bottom:1em;">
          <p>Shiksha offers points for every content created (asking a question, answering, commenting or commenting on discussions).</p>
		  <p>A new user starts earning points by getting &quot;One time joining bonus&quot; of 10 points. You can earn your joining bonus by performing your first action - which can be asking a question, commenting, answering, voting, sharing, reporting abuse, following, inviting friends or any profile update.</p>
</div>
	<div class="earn-points-sec">
        <strong class="earn-point-title">Actions to earn points</strong>
        <a href="#" class="mobile-sprite down-arrow"></a>
    </div>
    <div class="user-txt user-point-content" style="text-align:center">
          <strong class="user-level-title">Question</strong>
    </div>
    <div class="user-level-table"> 
       	<table>
        	<tr>
            	<th style="width:30%">Points awarded</th>
                <th style="width:70%">Actions</th>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">5</span></td>
                <td>Ask a question<span style="color:#f68d8d">*</span>
                <br>
         		<span style="font-size:12px;"><span style="color:#f68d8d">*</span>Points will be awarded for only first 5 questions asked.</span>
				</td>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">5</span></td>
                <td>Question gets 25 follows</td>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">15</span></td>
                <td>Question gets 100 follows</td>
            </tr>
        </table>
       </div>
       
       
    <div class="user-txt user-point-content" style="text-align:center">
          <strong class="user-level-title">Answer</strong>
    </div>
    <div class="user-level-table"> 
       	<table>
        	<tr>
            	<th style="width:30%">Points awarded</th>
                <th style="width:70%">Actions</th>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">10</span></td>
                <td>Answer a question</td>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">10</span></td>
                <td>Answer get 10 up-votes </td>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">10</span></td>
                <td>Answer get 25 up-votes </td>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">30</span></td>
                <td>Answer get 100 up-votes </td>
            </tr>
        </table>
       </div>
       
       <div class="user-txt user-point-content" style="text-align:center">
          <strong class="user-level-title">Discussion</strong>
    </div>
    <div class="user-level-table"> 
       	<table>
        	<tr>
            	<th style="width:30%">Points awarded</th>
                <th style="width:70%">Actions</th>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">5</span></td>
                <td>Start a discussion <span style="font-size:14px;">(Open to level11 and higher)</span></td>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">5</span></td>
                <td>Discussion gets 25 follows</td>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">15</span></td>
                <td>Discussion gets 100 follows</td>
            </tr>
        </table>
       </div>
       
       <div class="user-txt user-point-content" style="text-align:center">
          <strong class="user-level-title">Discussion comment</strong>
    </div>
    <div class="user-level-table"> 
       	<table>
        	<tr>
            	<th style="width:30%">Points awarded</th>
                <th style="width:70%">Actions</th>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">0</span></td>
                <td>Comment on a discussion</td>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">10</span></td>
                <td>Comment gets 10 up-votes</td>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">10</span></td>
                <td>Comment gets 25 up-votes</td>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">30</span></td>
                <td>Comment gets 100 up-votes</td>
            </tr>
        </table>
       </div>
       
       <div class="user-txt user-point-content" style="text-align:center">
          <strong class="user-level-title">Profile Update</strong>
    </div>
    <div class="user-level-table"> 
       	<table>
        	<tr>
            	<th style="width:30%">Points awarded</th>
                <th style="width:70%">Actions</th>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">5</span></td>
                <td>Upload a photo</td>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">5</span></td>
                <td>Residence city</td>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">5</span></td>
                <td>Field of study</td>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">5</span></td>
                <td>Country of interest</td>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">5</span></td>
                <td>Phone number</td>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">5</span></td>
                <td>Education background</td>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">5</span></td>
                <td>Work Experience</td>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">5</span></td>
                <td>Add "About me"</td>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">5</span></td>
                <td>Date of Birth</td>
            </tr>
        </table>
       </div>
       
       
       <div class="user-txt user-point-content" style="text-align:center">
          <strong class="user-level-title">Joining Bonus</strong>
    </div>
    <div class="user-level-table"> 
       	<table>
        	<tr>
            	<th style="width:30%">Points awarded</th>
                <th style="width:70%">Actions</th>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">10</span></td>
                <td>First time action</td>
            </tr>
        </table>
    </div>
    <div class="user-txt user-point-content" style="padding-bottom:1em;">
          <p>Sample scoring for an Answer </p>
	  <p>If you answer a question you earn 10 points. If your answer gets:</p>
	  <ul class="bullet-list">
            <li> 10 upvotes you earn 10 additional points</li>
	    <li> 25 upvotes you earn 10 more additional points,</li>
            <li> 100 up votes you earn 30 additional points.</li>
          </ul>
	  <p>In effect, your answer can earn you a maximum of 60 points.</p>
</div>
	</section>
</div>
</div>
        
<div class="mobile-container" data-role="page" id="userpointsystem3" class="of-hide">
<div class="cont-content" data-role="main">
    <?php if($siteIdentifier == 'mobileSite'){?>
        <div class="cont-heading" style="margin-bottom:4px;">
            <a href="javascript:void(0);" data-rel="back"><i class="mobile-sprite ic_bck" style="top:20px; !important"></i>How you lose points (Penalties)?</a>
        </div>
    <?php } ?>
    <section class="cont-content wht-not-do">
     	<div class="user-txt user-point-content" style="padding-bottom:1em;">
          <p>While content creation earns you points, misuse of the community leads to penalty. You could get penalized for:<br>
            1. Inactivity<br>
            2. Misuse of &quot;Report Abuse&quot;<br>
            3. Your content if it&#39;s Reported Abuse by other users and accepted by the moderator
          </p>
</div>
	<div class="earn-points-sec">
        <strong class="earn-point-title">Actions that will be penalized</strong>
        <a href="#" class="mobile-sprite down-arrow"></a>
    </div>
    <div class="user-txt user-point-content" style="text-align:center">
          <strong class="user-level-title" style="font-size:14px;">Report Abuse on your content</strong>
    </div>
    <div class="user-level-table"> 
       	<table>
        	<tr>
            	<th style="width:40%">Penalty & Conditions<span style="color:#f68d8d">*</span></th>
                <th style="width:60%">Actions</th>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">0</span></td>
                <td class="font-14">Report abuse[s] rejected by moderator</td>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">0</span></td>
                <td class="font-14">Report abuse accepted without penalty (content deleted)</td>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">0</span><br>
                <span style="font-size:12px;"><span style="color:#f68d8d">*</span>For the first deletion 
(just a warning)</span></td>
                <td colspan="5" style="background:#fff; border-bottom:none;" class="font-14">Report abuse accepted with penalty (content deleted)</td>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">10</span><br>
				<span style="font-size:12px;"><span style="color:#f68d8d">*</span>From second time onwards (user at beginner level)</span>
                </td>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">20</span><br>
                <span style="font-size:12px;"><span style="color:#f68d8d">*</span>From second time onwards (user at beginner level)</span>
                </td>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">100</span><br>
                <span style="font-size:12px;"><span style="color:#f68d8d">*</span>From second time onwards (user at contributor level)</span>
                </td>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">500</span><br>
				<span style="font-size:12px;"><span style="color:#f68d8d">*</span>From second time onwards (User at guide level)</span>
				</td>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">500</span><br>
				<span style="font-size:12px;"><span style="color:#f68d8d">*</span>From second time onwards (User at scholar level)</span>
				</td>
            </tr>
        </table>
       </div>
       
       
    <div class="user-txt user-point-content" style="text-align:center">
          <strong class="user-level-title" style="font-size:14px;">When you Report Abuse on any content</strong>
    </div>
    <div class="user-level-table"> 
       	<table>
        	<tr>
            	<th style="width:40%">Penalty & Conditions</th>
                <th style="width:60%">Actions</th>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">0</span></td>
                <td class="font-14">Report abuse rejected without penalty</td>
            </tr>
            <tr>
            	<td><span class="numeric-text" style="top:0; float:none; left:0.2em;">10</span></td>
                <td class="font-14">Report abuse rejected with penalty</td>
            </tr>
        </table>
       </div>
       
       <div class="user-txt user-point-content" style="text-align:center">
          <strong class="user-level-title" style="font-size:14px;">Inactivity (Valid only for users from Level 11 and above)</strong>
    </div>
    <div class="user-level-table"> 
       	<table>
        	<tr>
            	<th style="width:40%">Penalty &  Conditions</th>
                <th style="width:60%">Actions</th>
            </tr>
            <tr>
            	<td style="font-size:12px;">Level Drop</td>
                <td class="font-14">No action for 60 days</span></td>
            </tr>
        </table>
       </div>
       <div class="report-abuse-content">
       	<strong>1. Report Abuse</strong>
		<p>When your content is marked as Report Abuse:</p>
		<p>a. It will go to Shiksha moderators to be reviewed. Depending on the nature and intent of the report, the moderator has the right to accept or reject.</p>
		-If it is accepted, the content gets deleted and the 	moderator sends a warning. First time warning carries no penalty
 
		<p>b. If same behaviour is repeated, then penalty will be levied as per your User level.</p>
        - Beginner level &#150; 10 points will be deducted <br>
        - Contributor level &#150; 20 points will be deducted <br>
        - Guide level &#150; 100 points will be deducted <br>
        - Scholar level &#150; 500 points will be deducted<br>
<br>
        <strong>2. When you Report Abuse a content:</strong>
        <p>Depending on the moderator&#39;s discretion, &quot;report abuse&quot; marked by you can be rejected without deducting any points from your account or it can also be rejected with a penalty of 10 points.</p>
        <br>
        <strong>3. Inactivity</strong>
         <p>If you are inactive for more than 60 days (Valid only for users from Level 11 and above) you will be demoted by one level and your points will be set back to the highest point of the previous level. You are considered to be active only if you have created content (question, answer, comment, replies) in a 60 day period and not for other actions such as upvotes or shares. For Eg- If you are inactive at Level 11 for 60 days then on 61st day, you will be demoted to Level 10 and your points will be decreased to 3499, i.e. the highest points for Level 10.</p>

       </div>
       </section>
  </div>
  </div>

<?php if($siteIdentifier != 'mobileSite'){?>
  </body> 
  </html>

  <script>
    myInterface.setTitle('User Point System');
    </script>
<?php } ?>
