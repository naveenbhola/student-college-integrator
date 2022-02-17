<div id="countryLayer" data-role= "page" style = "background:#fff !important;width:100% !important;" data-enhance="false">
	<div class="layer-header">
        <a href="javaScript:void(0);" class="back-box warnMsg" data-rel="back"><i class="sprite back-icn"></i></a>
        <p>Applicable countries</p>
    </div>
    <section class="content-wrap">
    	<ul class="lyr-list">
    		<?php
    		for ($i=0; $i < count($applicableCountries); $i++) { 
    			echo '<li>'.$applicableCountries[$i].'</li>';
    		}
    		?>
    	</ul>
    </section>
</div>