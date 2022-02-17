<?php $this->load->view('header'); ?>
<link rel="stylesheet" href="//<?php echo CSSURL; ?>/public/mobile5/css/<?php echo getCSSWithVersion("main",'nationalMobile'); ?>" >
<style>
    .h-solid{padding:0px 0px 10px 0px;color:#666;text-transform:uppercase;font-size:18px; font-weight:300;}
</style>


<div id="wrapper" data-role="page" style="min-height:413px;padding-top:40px;">

   <?php
	 echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
	 echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
   ?>

    <header id="page-header"  class="header ui-header-fixed" data-role="header" data-tap-toggle="false" data-position="fixed">
     <?php echo Modules::run('mcommon5/MobileSiteHamburger/getMainHeader',$displayHamburger=true);?>
    </header>	  
	  
    <div data-role="content" data-enhance="false">
        <?php 
        $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
        ?>

        <div class="new-container contUs-wrapper">
            <div class="new-row">
              <div class="col-lg-12 s-div"> 
                </div>
                <h1 class="head-L1 col-lg-12">Contact Us</h1>
                <!-- <p class="para-L2 col-lg-12">Students & Parents Helpline</p> -->
            <!-- <div class="col-lg-12 tac">
                <div class="stu-colDiv">
                    <div class="stu-col">
                        <a href="tel:01140469621"><p class="mob-no">011 - 4046 - 9621</p></a>
                        <p class="para-L2">Timing: Mon-Fri, 9:30 AM - 6:30 PM (IST)</p>   
                    </div>
                </div>
            
            </div> -->
            <div class="new-container contUs-wrapper">
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
                        <a href="#questionPostingLayerOneDiv" data-inline="true" data-rel="dialog" data-transition="fade" class="" id="questionText_contact" ga-attr="ASK_QUESTION"><textarea placeholder="Type your Question..." name=questionText></textarea></a>
                        <a class="btn__prime button button--orange" id="ask_button_contact" href="#questionPostingLayerOneDiv" data-inline="true" data-rel="dialog" data-transition="fade" ga-attr="ASK_QUESTION">Ask Question</a>
                        <input type="hidden" name="tracking_keyid" id="tracking_keyid_ques" value="">
                         <p class="call__us">Or call us on: <span class="num__txt">011 - 4046 - 9621</span> <span class="timings"> Mon-Fri, 9:30AM - 6:30PM (IST)</span>  </p>
                      </div>

                 </div>

            </div>
            <!--ends-->
  </div>
                
                <p class="para-L2 col-lg-12 mt20">Get your College / Institute / Business listed on Shiksha</p>
		<p class="para-L2 col-lg-12 time-para">Send email to us: <a href="mailto:sales@shiksha.com">sales@shiksha.com</a></p>
                <p class="para-L2 col-lg-12 time-para">Alternatively, contact a sales branch near you | Timing: Mon-Fri, 9:30 AM - 6:30 PM (IST)</p>
                <div class="col-lg-12">
                <div class="new-row">        
                <div class="col-lg-4">
                    <div class="scard">
                        <h1 class="head-L2 cont-head">Ahmedabad</h1>
                        <p class="para-L2 add-clr">203, Shitiratna Panchvati Circle , CG Road Ahmedabad - 380 006</p>
                        <div>
                        <i class="icons phone-icn"></i>
                        <div class="phn-div">
                            <a href="tel:079 - 26440210" class="link-gray-medium">079 - 26440210</a> / <a href="tel:079 - 40233628" class="link-gray-medium">079 - 40233628</a> / <a href="tel:9925229091" class="link-gray-medium">9925229091</a>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="col-lg-4">
                    <div class="scard">
                        <h1 class="head-L2 cont-head">Bengaluru</h1>
                        <p class="para-L2 add-clr">N-901 & 902, North Block, Manipal Cantre, Dickenson Road, Bengaluru - 560042</p>
                        <i class="icons phone-icn"></i>
                        <a href="tel:080 - 40439000" class="link-gray-medium">080 - 40439000</a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="scard">
                        <h1 class="head-L2 cont-head">Chandigarh</h1>
                        <p class="para-L2 add-clr">Info Edge India Ltd., 1st floor, SCO 224-225, Sec 40 - D, Chandigarh - 160036</p>
                        <i class="icons phone-icn"></i>
                        <a href="tel:0172 - 5074040" class="link-gray-medium">0172 - 5074040</a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="scard">
                        <h1 class="head-L2 cont-head">Chennai</h1>
                        <p class="para-L2 add-clr">Samson Towers, 1st floor, 403 L, pantheon Road, Egmore, Chennai - 600008</p>
                        <i class="icons phone-icn"></i>
                        <a href="tel:044-42977777" class="link-gray-medium">044-42977777</a> / 
                        <a href="tel:044-42068533" class="link-gray-medium"> Fax: 044-42068533</a> 
                    </div>
                    
                </div>
                <div class="col-lg-4">
                    <div class="scard">
                        <h1 class="head-L2 cont-head">Delhi NCR(Head Office)</h1>
                        <p class="para-L2 add-clr">Info Edge India Ltd, A-88, Sector -2, Noida - 201301</p>
                        <i class="icons phone-icn"></i>
                        <a href="tel:0120 - 4629400" class="link-gray-medium">0120 - 4629400</a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="scard">
                        <h1 class="head-L2 cont-head">Hyderabad</h1>
                        <p class="para-L2 add-clr">6-3-1192/1/1, Office No:113 To 115, 3rd Block, 1st Floor, White House, Kundan bagh, Beside Life Style, Begumpet, Hyderabad - 500026</p>
                        <i class="icons phone-icn"></i>
                        <a href="tel:040 - 44751049" class="link-gray-medium">040 - 44751049</a> / <a href="tel:9849695326" class="link-gray-medium">9849695326</a>
                    </div>
                </div><div class="col-lg-4">
                    <div class="scard">
                        <h1 class="head-L2 cont-head">Kolkata</h1>
                        <p class="para-L2 add-clr">Info Edge (India) Ltd., 224 A J C Bose Road, KRISHNA BUILDING- 8th Floor, Module # 801 & 802 (Near Beckbagan Crossing), Kolkata - 700017</p>
                        <i class="icons phone-icn"></i>
                        <a href="tel:033-40021775" class="link-gray-medium">033-40021775</a> / <a href="tel:033-40021764" class="link-gray-medium">64</a> / <a href="tel:033-40021766" class="link-gray-medium">66</a> / <a href="tel:033-40021750" class="link-gray-medium">50</a>
                    </div>
                    
                </div>
                <div class="col-lg-4">
                    <div class="scard">
                        <h1 class="head-L2 cont-head">Jaipur</h1>
                        <p class="para-L2 add-clr">Info Edge (India) Ltd., 605, Crystal Mall, S.J.S. Highway, Bani Park, Jaipur - 302016</p>
                        <i class="icons phone-icn"></i>
                        <a href="tel:0141 - 4048908" class="link-gray-medium">0141 - 4048908</a> / <a href="tel:9925229091" class="link-gray-medium">9925229091</a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="scard">
                        <h1 class="head-L2 cont-head">Mumbai</h1>
                        <p class="para-L2 add-clr">127-128, 1st Floor, Chintamani Plaza, Andheri Kurla Road, Andheri (E), Mumbai - 400099</p>
                        <i class="icons phone-icn"></i>
                        <a href="tel:022 - 42447835" class="link-gray-medium">022 - 42447835</a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="scard">
                        <h1 class="head-L2 cont-head">Pune</h1>
                        <p class="para-L2 add-clr">Info Edge (India) Ltd., Anand Square, 2nd floor Off No - 201 & 202, New Airport Road, Opp. Symbiosis Intl. School, Viman Nagar, Pune - 411014</p>
                        <i class="icons phone-icn"></i>
                        <a href="tel:020 - 67495700" class="link-gray-medium">020 - 67495700</a> 
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="scard">
                        <h1 class="head-L2 cont-head">Indore</h1>
                        <p class="para-L2 add-clr">201, Royal Ratan Building , 7 M.G. Road, Indore - 452001</p>
                        <i class="icons phone-icn"></i>
                        <a href="tel:0731-4010305" class="link-gray-medium">0731-4010305</a> / <a href="tel:9893627008" class="link-gray-medium">9893627008</a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="scard">
                        <h1 class="head-L2 cont-head">Outside India</h1>
                        <i class="icons phone-icn"></i>
                        <a href="tel:1800 717 1094" class="link-gray-medium">1800 717 1094 (Toll free)</a>
                    </div>
                </div>
            </div>
                </div>
            </div>
        </div> 
    </div>
	  


<?php $this->load->view('footerLinks'); ?>
</div>
</div>
<div id="popupBasicBack" data-enhance='false'></div>
<?php $this->load->view('footer'); ?>
<script type="text/javascript">
var GA_currentPage = 'CONTACT US PAGE';
var ga_commonCTA_name = '_CONTACT_US_PAGE_MOB';
var ga_user_level = '<?=$GA_userLevel?>';
    $(document).ready(function(){
      initializeContactUsPage(1377);
  });
</script>

