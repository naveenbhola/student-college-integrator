<!-- page content -->
<div class="right_col" role="main">
    <!-- top tiles -->
    <?php
    $getTileMetaData = function($aspect, $tileNames){
        $oneTile = new stdClass();
        $oneTile->name = $tileNames[$aspect];
        $oneTile->dataText = $aspect;
        return $oneTile;
    };

    if($actionName == 'engagement'){
        $tileMetaData = array(
            $getTileMetaData('pageview', $tileNames),
            $getTileMetaData('pgpersess', $tileNames),
            $getTileMetaData('avgsessdur', $tileNames),
            $getTileMetaData('bounce', $tileNames),
            $getTileMetaData('exit', $tileNames),
        );

        $tileNames = $tileMetaData;
        unset($tileMetaData); //

    } else if ($actionName == 'traffic') {

        $tileNames = array(
            $getTileMetaData('user', $tileNames),
            $getTileMetaData('session', $tileNames),
            $getTileMetaData('pageview', $tileNames),
            $getTileMetaData('newsession', $tileNames),
        );
    }
    $this->load->view('trackingMIS/nationalListings/filters');
    $this->load->view('trackingMIS/nationalListings/topTiles', array('tileData' => $tileNames));
    $this->load->view('trackingMIS/lineChart');

    ?>
    <br/>
    <div class="row">
        <?php
        foreach ($splits as $index => $value) { // For list of valid identifiers, see Listings Config located at application/config/listingsTrackingMISConfig.php
            $this->load->view('trackingMIS/nationalListings/'.$value['viewFileName'], $value);
        } ?>
    </div>
    <div class="row">
        <?php
        if ($actionName == 'response') {
            $this->load->view('trackingMIS/nationalListings/showTable');
        } else if ($actionName == 'engagement') { // make this dynamic
            $this->load->view('trackingMIS/nationalListings/showTableEngagement');
        } else if ($actionName == 'registration') {
            $this->load->view('trackingMIS/nationalListings/showTableRegistration');
        } else if ($actionName == 'traffic') {
            $this->load->view('trackingMIS/nationalListings/showTableTraffic');
            }
        ?>
    </div>
</div>