<?php
    $headerComponents = array('css'             => array('homePageSA'),
                              'canonicalURL'    => SHIKSHA_STUDYABROAD_HOME,
                              'title'           => 'Study Abroad – Colleges, Courses, Exams, Free Counseling',
                              'metaDescription' =>'Want to study abroad ? Get free expert advice and information on colleges, courses, exams, admission, student visa, and application process to study overseas.',      
                              'metaKeywords'    => 'study abroad, study overseas, overseas education, higher education in abroad, study abroad programs, study abroad colleges, study abroad courses, International studies',
                              'deferCSS'        => true,
                              'firstFoldCssPath'    => 'css/homePageCss_FF'
                              );
    $this->load->view('commonModule/headerV2',$headerComponents);
    echo jsb9recordServerTime('SA_MOB_HOMEPAGE',1);
?>
<div data-enhance="false">
<?php     
    /*Search Box html start */
    $this->load->view('widgets/searchBarV2');
    /*Search Box html end */

    /* data guides widget */
    $this->load->view('widgets/guides');
    /* data guides widget : END */

    /* trending courses & recently viewed courses widget */
    $this->load->view('widgets/trendingCourses');
    /* trending courses & recently viewed courses widget : END */
    
    /* registration link widget */
    $this->load->view('widgets/registrationLink');
    /* registration link widget : END */
    

    /* data apply content widget */
    $this->load->view('widgets/applyContent');
    /* data apply content widget : END */
    
    /* quick links widget */
    $this->load->view('widgets/quickLinks');
    /* quick links widget : END */

    /* data count widget */
    $this->load->view('widgets/dataCount');
    /* data count widget : END */
?>
</div>
<?php 
    $this->load->view('homePageFooter');
?>