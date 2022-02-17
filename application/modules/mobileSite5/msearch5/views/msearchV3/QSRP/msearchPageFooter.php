<p class='clr'></p>
<?php 
    $this->load->view('/mcommon5/footerLinks'); 
?>
</div>
</div><!-- for closing content div -->
<div data-role="popup" data-transition="none" id="coachMarkPop" data-enhance="false"><!-- dialog--> 
</div>
</div><!-- for closing wrapper div -->

<!-- Filter -->
<?php if(count($tupleData['filters']) >= 1 && in_array($currentTab, array('answered', 'unanswered'))) {
    $this->load->view('msearch5/msearchV3/QSRP/filterLayer');
} ?>

<?php $this->load->view('/mcommon5/footer'); ?>

<script type="text/javascript">
    var retainSearchKeyword = '<?php echo addslashes($keyword); ?>';
    var trackingKeyIds = eval('(' + '<?php echo json_encode($trackingKeyIds);?>' + ')');
    var redirectToUrl = "<?php echo $updateTabInURL ?>";

    <?php if($isZRP) { ?>
        initializeQSRP('zrp');
    <?php }
    else { ?>
        initializeQSRP();
    <?php } ?>
</script>