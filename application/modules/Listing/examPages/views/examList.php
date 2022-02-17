<?php
    $titleText = "$seoText Exams in India - Get Dates, Syllabus, Question Papers & Answer keys";
    $metaDescription = "Get $seoText exams eligibility, exam syllabus, exam pattern, question papers, application dates, admit card dates, answer keys and result announcements for upcoming $seoText entrance exams in India in ".date('Y');

if(isMobileRequest()) {
    $headerComponents = array (
        'js' => array (),
        'bannerProperties' => array(
                'pageId'=>'EXAM',
                'pageZone'=>'TOP',
                'examPageShikshaCriteria' => (array_key_exists($shikshaCriteria, $duplicateShikshaCriteria)) ? $duplicateShikshaCriteria[$shikshaCriteria] : $shikshaCriteria
            ),
        'm_meta_title'                =>  $titleText,
        'm_meta_description' => $metaDescription,
        'canonicalURL'      => $canonicalurl,
        'prevURL'=>$prevURL,
        'nextURL'=>$nextURL
    );
    $this->load->view('mcommon5/header',$headerComponents);
    echo jsb9recordServerTime('SHIKSHA_MOB_NATIONAL_EXAM_PAGES',1);

}else{
    $headerComponents = array(
    'jsFooter' => array (
        'common',
        'processForm'
    ),
    'searchEnable' => false,
    'bannerProperties' => array(
        'pageId'=>'ALL_EXAM',
        'pageZone'=>'TOP',
        'examPageShikshaCriteria' => array()
    ),
    'title' => $titleText,
    'metaDescription' => $metaDescription,
    'canonicalURL' => $canonicalURL,
    'noIndexMetaTag'=> $isNoIndex,
    'previousURL'=>$prevURL,
    'nextURL'=>$nextURL
    );
    $this->load->view ('common/header', $headerComponents );
    echo jsb9recordServerTime('NATIONAL_EXAM_PAGES', 1);
}
?>

<link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
    <?php
    if(isMobileRequest()){ ?>
        <div id="wrapper" data-role="page" style="min-height:413px;padding-top:40px;">
            <?php
     echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
     echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel',false);
     ?>
     <header id="page-header"  class="header ui-header-fixed" data-role="header" data-tap-toggle="false" data-position="fixed">
     <?php echo Modules::run('mcommon5/MobileSiteHamburger/getMainHeader',$displayHamburger=true, '');?>
    </header>
    <?php } ?>

<div class="exam-main-wrap">
<div class="exam_Sticky_head">
      <div class="exams__container" >
        <h1><?php echo "Top ".$courseName." Entrance Exams In India"; ?></h1>
            <p>Get <?=$courseName?> exam dates, syllabus, exam pattern, question papers, application dates, admit card dates, answer keys and result announcements for upcoming <?=$courseName?> entrance exams in India in <?=date('Y')?></p>
      </div>
    </div>
    <div class="exams__container">
        <?php 
            if(count($popularExams)>0){
                $this->load->view('listExams', array('examType' => 'Popular', 'exams'=>$popularExams));
            }
            if(count($otherExams)>0){
                $this->load->view('listExams', array('examType' => 'Other', 'exams'=>$otherExams)); 
            }
        ?>
    </div>
    
    <?php $this->load->view('pagination', array('totalItems'=>$totalItems,'currentPage'=>$currentPageNo,'currentPageUrl'=>$currentPageUrl,'queryParam'=>$queryParam,'pageSize'=>$pageSize));?>
<?php 
if(isMobileRequest()){
    $this->load->view("mobile_examPages5/newExam/widgets/successLayer");
}
?>
<div class="main__layer" id='exmPopUpLayer'></div>
<?php if(isMobileRequest()) {
    $this->load->view('/mcommon5/footerLinks');
} ?>
</div>
<?php if(isMobileRequest()){ ?>
    </div>
<?php }?>
<?php
if(isMobileRequest()) {?>
<div id="popupBasicBack" data-enhance='false'></div>
<?php $this->load->view('/mcommon5/footer');
}else{
$this->load->view('common/footerNew', $loadjQuery);
}
// $this->load->view('common/newMMPForm');
?>
<script type="text/javascript">
var fromWhere = 'allExamPage';
 <?php if(isMobileRequest()){ ?>
        $(document).ready(function(){
<?php }else { ?>
        $j(document).ready(function(){
<?php }?>
     examCTAObj.bindExamPageElements();
  });
</script>
