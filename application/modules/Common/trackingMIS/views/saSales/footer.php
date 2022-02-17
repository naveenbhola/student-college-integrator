   		</div>
    </div>
</div>

<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/<?php echo getJSWithVersion("bootstrap.min","nationalMIS"); ?>"></script>
<!-- bootstrap progress js -->
<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/progressbar/bootstrap-progressbar.min.js"></script>
<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/nicescroll/jquery.nicescroll.min.js"></script>
<!-- icheck -->
<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/icheck/icheck.min.js"></script>

<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/<?php echo getJSWithVersion("customMIS","nationalMIS"); ?>"></script>
<!-- google maps-->
<script async defer src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyDP9nPoEGCUgecgCMwIpnJ7k_GildwRVG8&region=in"></script>

<!-- daterangepicker -->
<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/<?php echo getJSWithVersion("moment.min","nationalMIS"); ?>"></script>
<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/datepicker/daterangepicker.js"></script>
<script type="text/javascript" src="//<?php echo JSURL; ?>/public/js/trackingMIS/<?php echo getJSWithVersion("saSalesMIS","nationalMIS"); ?>"></script>
<script type="text/javascript">
$(document).ready(function() {

	saSales.showDateRangePicker();
	Common.dateCompareMode('nouse');

	$('#submit,.applyBtn').on('click', function () {
        saSales.submitInput();
    });

    intitalizeStudentCountTile();
});

</script>