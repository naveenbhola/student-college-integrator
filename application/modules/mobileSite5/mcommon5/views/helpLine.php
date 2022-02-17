<?php $this->load->view('header'); ?>
<div id="wrapper" data-role="page" style="min-height:413px;padding-top:40px;">

   <?php
	 echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
	 echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel',false,'helpline');
   ?>

    <header id="page-header"  class="header ui-header-fixed" data-role="header" data-tap-toggle="false" data-position="fixed">
     <?php echo Modules::run('mcommon5/MobileSiteHamburger/getMainHeader',$displayHamburger=true, '', 'helpline');?>
    </header>	  
	
    
    <div data-role="content" data-enhance="false">
        <?php 
            $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
        ?>  
	  
        <!-- <div class="head-group">
            <h1>Student helpline</h1>
        </div> -->
        <div class="new-container stuhelp-wrapper">
            <div class="new-row">
                <div class="col-lg-12 s-div"> 
                </div>
                <h1 class="head-L1 col-lg-12">Student Helpline</h1>
                    <div class="new-container stuhelp-wrapper">
                    <!--ask & answer widget-->
                        <div class="col-lg-12 tac new__col">
                            <div class="stu-colDiv">
                                <p class="f22__clr3">Get our experts to answer your questions within<strong>24 Hrs.</strong></p>
                                 <div class="img__story">
                                    <div class="answer"><p>Answers within 24 Hrs.</p></div>
                                    <div class="reliable"><p>Reliable Answers</p></div>
                                    <div class="experts"><p>1000+ Experts</p></div>
                                 </div>
                                 <div class="input__block">
                                    <a href="#questionPostingLayerOneDiv" data-inline="true" data-rel="dialog" data-transition="fade" class="" id="questionText_contact" ga-attr="ASK_QUESTION"><textarea placeholder="Type your question..." name=questionText></textarea></a>
                                    <a class="btn__prime" id="ask_button_contact" href="#questionPostingLayerOneDiv" data-inline="true" data-rel="dialog" data-transition="fade" ga-attr="ASK_QUESTION">Ask Question</a>
                                    <input type="hidden" name="tracking_keyid" id="tracking_keyid_ques" value="">
                                     <p class="call__us">Or call us on: <span class="num__txt">011 - 4046 - 9621</span> <span class="timings"> Mon-Fri, 9:30AM - 6:30PM (IST)</span>  </p>
                                  </div>

                             </div>
                        </div>
                    <!--ends-->
                      </div>
              </div>
          </div>
    </div>

    <!-- <section class="content-wrap2 ">
       <section class="help-box">
        <p>Call us between <br />09:30 AM to 06:30 PM,<br />Monday through Friday</p>
        <p>Call::<span>011 40469621</span></p>
	        <figure class="help-pic"><img class= "lazy" src="/public/mobile5/images/help-pic.jpg" alt="" /></figure>
		<div class="clearfix"><a href="tel:011 40469621" class="call-btn"><i class="icon-mobile"></i><span>Call Now</span></a></div>
	</section>    
     </section> -->
<?php $this->load->view('/mcommon5/footerLinks') ;?>
</div>
<div id="popupBasicBack" data-enhance='false'></div>
<?php $this->load->view('/mcommon5/footer') ;?>
<script type="text/javascript">
var GA_currentPage = 'STUDENT HELPLINE PAGE';
var ga_commonCTA_name = '_STUDENT_HELPLINE_PAGE_MOB';
var ga_user_level = '<?=$GA_userLevel?>';
    $(document).ready(function(){
      initializeContactUsPage(1381);
  });
</script>


