<script type="text/javascript">
    var targetBaseUrl = '<?php echo SHIKSHA_HOME."/trackingMIS/Listings" ?>';
    var bootstrapDropdownHandler = new BootstrapDropdownHandler(targetBaseUrl);
    bootstrapDropdownHandler.generateInput();

    $('#submit').on('click', function () {
        var targetURL = '/<?php echo $actionName; ?>?dim=<?php echo $metricName; ?>&pivot=<?php echo $pivotName; ?>';
        bootstrapDropdownHandler.submitInput(targetURL);
    });
</script>