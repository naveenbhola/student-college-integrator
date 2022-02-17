<div id="intakeYearLayer" data-role= "page" style = "background:#fff !important;width:100% !important;" data-enhance="false">
	<div class="layer-header">
        <a href="javaScript:void(0);" class="back-box warnMsg" data-rel="back"><i class="sprite back-icn"></i></a>
        <p>Intake Years</p>
    </div>
    <section class="content-wrap">
    	<ul class="lyr-list">
    		<?php
    		for ($i=0; $i < count($allIntakeYears); $i++) {
    			$date = date_create_from_format("Y-m-d", $allIntakeYears[$i]);
    			echo '<li>'.date_format($date, "M Y").'</li>';
    		}
    		?>
    	</ul>
    </section>
</div>