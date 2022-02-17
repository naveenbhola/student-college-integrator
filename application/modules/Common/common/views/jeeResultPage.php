<?php
        $headerComponents = array(
                'css'               => array('main'),
                'jsFooter'          => array('common'),
                'title'             => "JEE Result",
                'metaDescription'   => "JEE Result",
                'displayname'       => (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
                'bannerProperties'  => array('pageId'=>'', 'pageZone'=>''),
                'product'           => 'resultPage',
                'noIndexNoFollow'   => 1,
                'callShiksha'       => 1
        );
        $this->load->view('common/header', $headerComponents);
?>
<style>
 .holds-the-iframe {
  background:url(/public/mobile5/images/ShikshaMobileLoader.gif) center center no-repeat;
 }
</style>
    <div class="jee-page">
        <div class="new-container page-start">
            <div class="new-row">
                <div class="col-md-12">
                    <p class="text18">
                        Check your JEE Main 2017 result below. After checking the result, predict your college and branch <a href="http://www.shiksha.com/b-tech/resources/jee-mains-college-predictor" target="_blank">here</a>.
                    </p>
                </div>
                <div class="col-md-9">
                    <div class="group-card gap pad-off">
                        <div class="holds-the-iframe"><iframe id="result_frame" src="" class="jee-result-iframe"></iframe></div>
                    </div>
                </div>
                <div class="col-md-3 no-padRgt">
                    <div class="group-card gap bg-img">
                        <h2 class="almuni-h3">JEE Main 2017 college predictor</h2>
                        <p class="text14">
                            Enter you rank. Find your college and branch
                        </p>
                        <a href="<?=SHIKSHA_HOME?>/b-tech/resources/jee-mains-college-predictor" target="_blank" class="btn-primary top-b">Predict college</a>
                    </div>
                    <div class="group-card gap views-ol">
                        <h2 class="rgt-title">Got a great rank? Explore Top Ranked Colleges</h2>
                        <ul class="rank-ul">
                            <li>
                                <a href="<?=SHIKSHA_HOME?>/b-tech/ranking/top-engineering-colleges-in-india/44-2-0-0-0" target="_blank">All Engineering College Rankings</a>
                            </li>
                            <li>
                                <a href="<?=SHIKSHA_HOME?>/b-tech/ranking/top-engineering-colleges-in-india/44-2-0-0-0" target="_blank">Top Engineering Colleges by Location</a>
                            </li>
                            <li>
                                <a href="<?=SHIKSHA_HOME?>/b-tech/ranking/top-engineering-colleges-in-india/44-2-0-0-0" target="_blank">Top Engineering Colleges by Specialization</a>
                            </li>
                            <li>
                                <a href="<?=SHIKSHA_HOME?>/college/indian-institute-of-technology-delhi-hauz-khas-3050" target="_blank">IIT Delhi</a>
                            </li>
                            <li>
                                <a href="<?=SHIKSHA_HOME?>/college/indian-institute-of-technology-bombay-iit-powai-mumbai-28130" target="_blank">IIT Bombay</a>
                            </li>
                            <li>
                                <a href="<?=SHIKSHA_HOME?>/university/national-institute-of-technology-tiruchirappalli-2996" target="_blank">NIT Trichy</a>
                            </li>
                        </ul>
                    </div>
                    <div class="group-card gap views-ol">
                        <h2 class="rgt-title">Not so great rank? Find the best college for you</h2>
                        <ul class="rank-ul">
                            <li>
                                <a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-india" target="_blank">Engineering Colleges in India</a>
                            </li>
                            <li>
                                <a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-andhra-pradesh" target="_blank">Engineering Colleges in Andhra Pradesh</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-arunachal-pradesh" target="_blank">Engineering Colleges in Arunachal Pradesh</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-assam" target="_blank">Engineering Colleges in Assam</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-bihar" target="_blank">Engineering Colleges in Bihar</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-chhattisgarh" target="_blank">Engineering Colleges in Chhattisgarh</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-haryana" target="_blank">Engineering Colleges in Haryana</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-karnataka" target="_blank">Engineering Colleges in Karnataka</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-kerala" target="_blank">Engineering Colleges in Kerala</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-goa" target="_blank">Engineering Colleges in Goa</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-gujarat" target="_blank">Engineering Colleges in Gujarat</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-himachal-pradesh" target="_blank">Engineering Colleges in Himachal Pradesh</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-jammu-and-kashmir" target="_blank">Engineering Colleges in Jammu and Kashmir</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-jharkhand" target="_blank">Engineering Colleges in Jharkhand</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-madhya-pradesh" target="_blank">Engineering Colleges in Madhya Pradesh</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-maharashtra" target="_blank">Engineering Colleges in Maharashtra</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-manipur" target="_blank">Engineering Colleges in Manipur</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-meghalaya" target="_blank">Engineering Colleges in Meghalaya</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-mizoram" target="_blank">Engineering Colleges in Mizoram</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-nagaland" target="_blank">Engineering Colleges in Nagaland</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-orissa" target="_blank">Engineering Colleges in Orissa</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-punjab" target="_blank">Engineering Colleges in Punjab</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-rajasthan" target="_blank">Engineering Colleges in Rajasthan</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-sikkim" target="_blank">Engineering Colleges in Sikkim</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-tamil-nadu" target="_blank">Engineering Colleges in Tamil Nadu</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-tripura" target="_blank">Engineering Colleges in Tripura</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-uttar-pradesh" target="_blank">Engineering Colleges in Uttar Pradesh</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-west-bengal" target="_blank">Engineering Colleges in West Bengal</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-uttarakhand" target="_blank">Engineering Colleges in Uttarakhand</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-dadra-and-nagar-haveli" target="_blank">Engineering Colleges in Dadra and Nagar Haveli</a>
                            </li>
                            <li><a href="<?=SHIKSHA_HOME?>/b-tech/colleges/b-tech-colleges-telangana" target="_blank">Engineering Colleges in Telangana</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
    
<?php
        $this->load->view('common/footer');
?>

<script>
  jQuery(document).ready(function($) {
        $j('#result_frame').prop('src', '<?=$jeeResultPageURL?>');
  });
</script>

