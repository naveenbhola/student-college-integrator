<?php
$headerComponents = array(
        'js'               =>array(),
        'css'              =>array('iimPredictor'),
        'jsFooter'         =>array('shikshaHomePage','common'),
        'title'            => 'IIM Call Predictor 2017 -18 | Check Eligibility & CAT Cut-offs',
        'metaDescription'  => "Shiksha's IIM Call predictor helps you predict your chances of getting a call from IIMs, based on your CAT score and academic profile. Also check your eligibility and minimum percentile for IIM call.",
        'metaKeywords'     => 'Shiksha, education, colleges, universities, institutes, career, career options, career prospects, engineering, mba, medical, mbbs, study abroad, foreign education, college, university, institute, courses, coaching, technical education, higher education, forum, community, education career experts, ask experts, admissions, results, events, scholarships',
        'product'          => 'home',
        'bannerProperties' => array('pageId'=>'HOME', 'pageZone'=>'TOP'),
        'canonicalURL'     => SHIKSHA_HOME.'/mba/resources/iim-call-predictor',
        'showGutterBanner' => '1',	//To show Gutter banner only on the Shiksha Homepage
        'showSniperBanner' => '1', 	//To show promotional banner for study abroad
        'showBottomMargin' => false,
        'lazyLoadJsFiles'  => array('userRegistration')
        );
if($partnerFlag === true) {
    $headerComponents['partnerPage'] = $partner;
}
?>
<?php $this->load->view('common/header', $headerComponents); ?>

<section class="err-msg">
            <div class="container pLR0">
                 <div class="error-col">
                     <aside class="error-img">
                       <img src="/public/images/ICP_dataNotAvailable2.png" />
                     </aside>
                     <section class="error-infrmtn">
                       <h2 class="sry-txt">We are sorry !!!</h2>
                       <h3 class="avail-txt">IIM call predictor is currently  available only on mobile.</h3>
                       
                       <p class="opn-txt">Kindly open this link on your mobile to use this tool:</p>
                       <div class="opn-link">www.shiksha.com/mba/resources/iim-call-predictor</a>
                       <p class="mean-txt">Meanwhile,we are working hard to get this predictor to you on the desktop website as soon as possible</p>
                     </section>
                 </div>
               <p class="clr"></p>
            </div>
        </section>

<?php $this->load->view('common/footer'); ?>
