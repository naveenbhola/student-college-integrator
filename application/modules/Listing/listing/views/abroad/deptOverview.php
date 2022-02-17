<?php
$this->load->view('listing/abroad/deptHeader');
echo jsb9recordServerTime('SA_DEPARTMENT_PAGE',1);
$this->load->view('listing/abroad/widget/breadCrumbs');
?>
<div class="content-wrap clearfix">
    <?php
        $this->load->view('listing/abroad/deptPageContent');
        $this->load->view('listing/abroad/rightColumn');
    ?>
    <img id = 'beacon_img' src="/public/images/blankImg.gif" width=1 height=1 style="visibility: hidden;">
</div>
<?php $this->load->view('listing/abroad/deptFooter'); ?>