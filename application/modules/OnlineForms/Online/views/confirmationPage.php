<?php
$headerComponents = array (
		'js' => array (
				'multipleapply',
				'user' ,
                                'ana_common',
                                'ajax-api',
		),
		'jsFooter' => array (
				'common',
				'processForm' 
		),
                'css'	=>	array('common_new','shortlist'),
		'product'               => "online" ,
		'title'                 => "Confirmation Page | Shiksha.com",
		'metaDescription'       => ""
);

$this->load->view ( 'common/header', $headerComponents );
?>

<!--  div Id top-nav is used to make common.js  code workable without GNB Layer. this id is referance for making home page navigation bar sticky   -->
<div id="top-nav" style="visibility:hidden;height:0px"></div>
<div style="padding: 15px 10px 0px 10px; clear: left;">
<?php
    $subcatId = 23;
    //echo Modules::run ( 'coursepages/CoursePage/loadCoursePageTabsHeader', $subcatId, "MyShortlist", TRUE );
    //$dataArray['CUSTOMIZED_TABS_BAR'] = array('Home', 'Institutes','AskExperts', 'Rankings', 'Exams','ApplyOnline','MyShortlist'); // Adding My Shortlist
    //echo Modules::run('coursepages/CoursePage/loadCoursePageTabsHeader', $subcatId, "ApplyOnline", TRUE, $dataArray);
?>
</div>
<div class="clearFix"></div>
<div class="paytm-wrap">
        	<div class="paytm-content-wrap">
            	<?php
                if($showThankYouMsg == 1){
                ?>
                <div class="paytm-content">
                    <p><i class="paytm-sprite thanku-mark-icon"></i><strong>Thank you</strong> for submitting your forms on Shiksha. Your application reference number is <strong><?=$appRefId?></strong><br />
                    You will get a confirmation sms and email from shiksha.com regarding your application.</p>
                    <?php
                    if($showPaytmAmt == 1){
                    ?>
                    <p>We have also credited your <i class="paytm-sprite paytm-large-logo"></i> 
                    	<span class="que-mark">?</span> account with Rs. 100</p>
                        <div class="paytm-tooltip" style="display: none;">
                        	<i class="paytm-sprite tooltip-pointer"></i>
                           	<ul class="tooltip-list">
                            	<li>You can use your <strong>PayTM credit</strong> to recharge your prepaid mobile or to buy tickets on sites like bookmyshow.com, redbus.in etc.</li>
                                <li>You will get a mail from PayTM to claim your money</li>
                            </ul>
                        </div>
                        <p style="font-size: 12px;color: #5E5B5B;">(If you don't have a paytm account, sign-up with your mobile number on <a href="https://paytm.com/" rel="nofollow">paytm.com</a> within 7 days to retain your credit)</p>
                    <?php
                    }
                    ?>
                </div>
                <?php
                }
                ?>
                <div class="unique-code-sec clearfix">
                	<div class="unique-code-title">How to use your unique code <div class="code-area"><span class="unique-code"><?=$couponCode?></span></div> to save and earn more while applying to MBA colleges?</div>
                    <ul class="code-info-list">
                    	<li>
                        	<div class="code-img-col">
                            	<i class="paytm-sprite code-usage-icon"></i>
                            </div>
                            <div class="code-content-col">
                            	<p>Use this code to apply to more colleges on Shiksha and earn Rs. 100 in your <i class="paytm-sprite paytm-sml-logo"></i>wallet everytime </p>
                                <a href="<?php echo SHIKSHA_HOME."/studentFormsDashBoard/StudentDashBoard/index";?>" class="application-form-link">View other Application forms ></a>
                            </div>
                        </li>
                        <li style="margin-right:0;">
                        	<div class="code-img-col">
                            	<i class="paytm-sprite code-share-icon"></i>
                            </div>
                            <div class="code-content-col">
                            	<p>Share this code with your friends to earn Rs. 100 in your <i class="paytm-sprite paytm-sml-logo"></i> wallet everytime your friends apply using your code</p>
                                <p class="already-sent-info">(We have already sent you an SMS and a mail to forward to your friends)</p>
                                <label class="coupen-title" style="display: none;">Share coupon on:</label>
                                <a href="javascript:void(0);" onclick="shareOnFb('<?php echo $shortLink;?>','<?php echo $couponCode;?>');" style="display:none;"><i class="paytm-sprite faceboook-link"></i>facebook</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
<?php
$this->load->view ( 'common/footerNew', array (
			'loadJQUERY' => 'YES' 
	) );
?>
<script>
    $j(document).ready(function(){
        $j("body").on("mouseover",".que-mark",function(){
            var offset = $j(this).offset();
            $j(".paytm-tooltip").css("top", offset.top+45);
            $j(".paytm-tooltip").show();
            });
        });
    
    $j("body").on("mouseout",".que-mark",function(){
        $j(".paytm-tooltip").hide();
        });
        
//    function shareOnFb(shortLink,couponCode) {
//        FB.ui({
//            method: 'feed',
//            link: shortLink,
//            description: 'Apply to MBA colleges on Shiksha with my code and get Rs.100 on payTM',
//            caption: 'Use my code '+couponCode+' and get Rs. 100 on payTM when you apply to MBA colleges on Shiksha.com'
//          }, function(response){});
//    }
</script>

