<?php
$footerComponents = array(
    'hideFooter' => true
);
$this->load->view('commonModule/footerV2', $footerComponents);
?>

<script>
var prefillData = '<?php echo json_encode($prefillData);?>';
</script>