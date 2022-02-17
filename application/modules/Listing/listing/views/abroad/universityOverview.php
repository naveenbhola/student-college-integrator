<?php
$this->load->view('listing/abroad/universityHeader');
echo jsb9recordServerTime('SA_UNIVERSITY_PAGE',1);
$this->load->view('listing/abroad/widget/breadCrumbs');
?>
<div class="content-wrap clearfix universityLRpane">
    <?php
        $this->load->view('listing/abroad/universityPageContent');
        $this->load->view('listing/abroad/rightColumn');
        if(count($alsoViewedUniversityData)>0){
            $this->load->view('listing/abroad/widget/peopleAlsoViewedUniversity');
        }
    ?>
 <img id = 'beacon_img' src="<?php echo IMGURL_SECURE; ?>/public/images/blankImg.gif" width=1 height=1 style="visibility: hidden;">  
</div>
<?php $this->load->view('listing/abroad/universityFooter'); ?>
