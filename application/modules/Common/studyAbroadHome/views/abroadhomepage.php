<?php
$this->load->view('homepageHeader');
echo jsb9recordServerTime('SA_HOME_PAGE',1);
$this->load->view('searchV2HomepageWidget');
$this->load->view('coverageStats');
?>
<div class="home-wrapper clearfix">
    <?php
        //$this->load->view('searchForm');
        //$this->load->view('quickLinks');
        $this->load->view('guideWidget');
        $this->load->view('mostViewedCourses');
        //$this->load->view('countryMap');
        // $this->load->view('articleWidget');      -- This is being replaced by the new navigation widget for content organisation pages
        if(!empty($applyContent)){ $this->load->view('applyContentWidget'); }
        //echo modules::run('abroadContentOrg/AbroadContentOrgPages/getHomePageContentOrgWidget');
    ?>
</div>
<?php
$this->load->view('homepageFooter');
?>
