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
        echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel',false,'communityGuideline');
?>

    <header id="page-header"  class="header ui-header-fixed" data-role="header" data-tap-toggle="false" data-position="fixed">
     <?php echo Modules::run('mcommon5/MobileSiteHamburger/getMainHeader',$displayHamburger,'', 'communityGuideline');?>
    </header>

    <div class="mobile-container" data-role="content" data-enhance="false">
      <?php 
        $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
      ?>
      <div class="cont-content" >
      <div class="cont-heading" style="text-align:center;">
            <a href="javascript:void(0);">Community Guidelines</a>
      </div>

  <?php }else{ ?>
        <div class="mobile-container" data-role="page">
        <div class="cont-content" data-role="main" data-enhance="false">
  <?php } ?>
      
        <section id="wht-not-do" class="wht-not-do" style="padding-top:1em">
         <div class="user-txt">
              <p>Shiksha is a place where students get their career and education questions answered. As a valuable member of this community you are required to follow the guidelines mentioned below while you post a question or answer a question or reply to an answer or post a comment and help us keep this community clean.</p>
           </div>
           <div class="warning-text">
            <div style="text-align: center"><i class="mbl warning-icon"></i></div>
            <p>Warning: Violating the Community Guidelines may result in the termination of your Shiksha Cafe account without warning.In extreme cases, violations may result in the termination of access to all other Shiksha services.</p>
           </div>
           <a href="#whattodo" data-inline="true" data-rel="dialog" data-transition="slide" class="do-cont"  onclick = "myInterface.setTitle('What to do?');">
             <div class="div-left">
               <div class="wht-txt">
                  <p><i class="mbl ic_right"></i></p>
                  <p>What to do?</p>
               </div>
             </div>
             <div class="div-right"> 

               <i class="mobile-sprite left-arow"></i>
             </div>
             <div style="clear:both"></div>
           </a>
           
            <a href="#whatnottodo" data-inline="true" data-rel="dialog" data-transition="slide" class="do-cont" style="<?=$borderStyle;?>" onclick = "myInterface.setTitle('What not to do?');" >
              <div class="div-left">
               <div class="wht-txt">
                  <p><i class="mbl ic_cross1"></i></p>
                  <p>What not to do?</p>

                </div>
              </div>
              <div class="div-right">
                <i class="mobile-sprite left-arow" ></i>
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
  
        
  <div class="mobile-container" data-role="page" id="whattodo" class="of-hide" data-enhance="false">
  <div class="cont-content" data-role="main">
    <?php if($siteIdentifier == 'mobileSite'){?>
        <div class="cont-heading" >
            <a href="javascript:void(0);" data-rel="back"><i class="mobile-sprite ic_bck"></i>What to do?</a>
        </div>
    <?php } ?>
    <section class="wht-not-do">
       <a href="javascript:void(0)" class="red-cross"><i class="mobile-sprite ic_rght"></i></a>
       <p class="not-do-txt">What to do?</p>
       
       <div class="not-do-cont tupple-space">
         <h3>Share Knowledge</h3>
         <p>Someone could benefit from your knowledge, opinions, or personal experiences.</p>
       </div>
       
        <div class="not-do-cont tupple-space">
         <h3>Practice Courtesy</h3>
         <p>Shiksha Cafe is a diverse community of people with diverse opinions. Showing respect to others makes the community better for all members.</p>
       </div>
       
        <div class="not-do-cont tupple-space">
         <h3>Cite Sources</h3>
         <p>To make your answers and discussions more meaningful and credible, always try to cite online resources with your answers.</p>
       </div>
        
        <div class="not-do-cont tupple-space">
         <h3>Ask Clearly</h3>
         <p>Keep your questions short but descriptive enough for the expert to understand your question. In case of discussions, give a meaningful title to the discussion post.</p>
       </div>
       
        <div class="not-do-cont tupple-space">
         <h3>Avoid Chat Lingo</h3>
         <p>Keep the language of your question/comments/discussions clear and try avoiding chat lingo and short forms. This will help the experts and community members in understanding your question clearly.</p>
       </div>
       
       <div class="not-do-cont tupple-space">
         <h3>Categorize Correctly</h3>
         <p>Categorize your questions/discussions/announcements correctly by adding the relevant tags. This will help the Shiksha Cafe experts in finding your question easily.</p>
       </div>
       
        <div class="not-do-cont tupple-space">
         <h3>Post your answers properly</h3>
         <p>To answer a question, click on Answer this question button. Use this only to post an answer to the question and not to respond to another answer. Use the &quot;Comment&quot;button to reply or respond a particular answer. Comments like &quot;Thank you&quot; or &quot;Great!&quot; should be posted as a comment to an answer and not as a new answer.</p>
       </div>
       
    </section>
  </div>
</div>

<!--what not to do-->
<div class="mobile-container" data-role="page" id="whatnottodo" data-enhance="false" class="of-hide">
  <div class="cont-content">
    <?php if($siteIdentifier == 'mobileSite'){?>
      <div class="cont-heading" >
            <a href="javascript:void(0);" data-rel="back"><i class="mobile-sprite ic_bck"></i>What not to do?</a>
      </div>
    <?php } ?>
    <section class="wht-not-do">
       <a href="javascript:void(0)" class="red-cross"><i class="mobile-sprite ic_cross"></i></a>
       <p class="not-do-txt">What not to do?</p>
       
       <div class="not-do-cont tupple-space">
         <h3>Promote Business</h3>
         <p>Shiksha Cafe is a community for sharing knowledge. It is prohibited to use this platform for promoting business interest, self-promotion, advertisement and spamming members. You are not allowed to share your email id, websites, contact numbers, address, or any other personal information which is directly or indirectly related by your personal means.</p>
       </div>
       
        <div class="not-do-cont tupple-space">
         <h3>Hateful Behavior</h3>
         <p>It is strictly prohibited to post information that is false, misleading, unlawful, obscene, vulgar and objectionable or with the intent to harass, threaten abuse, accuse or harm the feelings of anyone.</p>
       </div>
       
        <div class="not-do-cont tupple-space">
         <h3>Duplicate Content</h3>
         <p>Before posting any new question/discussion/announcement, please use the search box to see if the same has already been posted before. This will save your time for you and community members.</p>
       </div>
        
        <div class="not-do-cont tupple-space">
         <h3>Copyrighted Material</h3>
         <p>Posting of copyrighted material is strictly prohibited. You can cite the same as a source and use the web link to direct the community members to the original source.</p>
       </div>
        
        <div class="not-do-cont tupple-space">
         <h3>Misuse Cafe</h3>
         <p>Don't create multiple accounts to earn points or otherwise violate the above guidelines or the Shiksha Terms of Service. Don't post things that are incomprehensible or in inappropriate language.</p>
       </div>
       
        <div class="not-do-cont tupple-space">
         <h3>Misuse the Report Abuse option</h3>
         <p>The Report Abuse option should only be used if a user:</p>
           <div class="sub-cont1 tupple-space">
            <ul class="bullet-list">
             <li>Is trying to use this platform for promoting business interest, self-promotion, advertisement and spamming members.</li>
             <li>Posting of copyrighted material.</li>
             <li>Post information that is false, misleading, unlawful, obscene, vulgar and objectionable or with the intent to harass, threaten abuse, accuse or harm the feelings of anyone.</li>
           </ul>
           </div>
          <span class="full-cont">
If we find you misusing the report abuse option for your personal gain, you can be penalized. So think twice before using report abuse button.
</span> 
       </div>
       
    </section>
  </div>
</div>

<?php if($siteIdentifier != 'mobileSite'){?>
</body>
  </html>

  <script>
    myInterface.setTitle('Community Guidelines');
    </script>
<?php } ?>

