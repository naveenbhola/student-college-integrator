<!-- page content -->
<div class="right_col" role="main">
    <!-- top tiles -->
    <?php
        $index = 0;
    if($actionName == 'engagement'){

        $tileNames = array(
            'Page Views',
            'Pages / Session',
            'Avg Session(mm:ss)',
            'Bounce (%)',
            'Exit (%)'
        );

        $tileIdentifiers = array(
            'pageview',
            'pgpersess',
            'avgsessdur',
            'bounce',
            'exit'
        );
    } else if ($actionName == 'traffic') {

        $tileNames = array(
            'Users',
            'Unique Sessions',
            'Page Views',
            '(%) New Sessions',
        );

        $tileIdentifiers = array(
            'users',
            'sessions',
            'pageview',
        );
    } else if ($actionName == 'response') {

        $tileNames = array(
            'Total Responses',
            'Responses/Respondent',
            'Paid Responses',
            'Paid Courses',
            'First Time Users',
        );
    } else if ($actionName == 'registration') {

        $tileNames = array(
            'Total Registration',
            'MMP Registration',
            'Response Registration',
            'Signup Registration',
            'Hamburger Registration',
        );
    }
    $tileData = array();
    foreach ($topTiles as $tileId => $tileValue) {
        $oneTile       = new stdClass();
        $oneTile->id   = $tileId;
        $oneTile->name = $tileNames[ $index ];
        if(isset($tileIdentifiers[$index])){
            $oneTile->dataText = $tileIdentifiers[$index];
        }
        /*if($tileId == 'avgsessdur')
            $tileValue = number_format($tileValue / 60, 2, '.', '');*/
        $oneTile->value = $tileValue;
        $tileData[]     = $oneTile;
        $index++;
    }
    $index = 0;

    $this->load->view('trackingMIS/nationalListings/filters');
    $this->load->view('trackingMIS/nationalListings/topTiles', array('tileData' => $tileData));

//    if (isset($resultsForGraph)) {
    $this->load->view('trackingMIS/nationalListings/showTrend');
//    }
    ?>
    <br/>
    <div class="row">
        <?php
        if (isset($splitInformation)) {
            foreach ($splitInformation as $index => $value) { // For list of valid identifiers, see Listings controller
                $data = array(
                    'splitData' => $value,
                    'splitType' => $index
                );
                if (count($data['splitData']) > 0 && property_exists($data['splitData'][0]->Pivot)) {
                    $this->load->view('trackingMIS/nationalListings/showSplit', $data);
                } else {
                    $this->load->view('trackingMIS/nationalListings/showSplit', array('splitData' => $value, 'splitType' => $index));
                }
            }
        } ?>
    </div>
    <div class="row">
        <?php
            $this->load->view('trackingMIS/nationalListings/sourceBarGraph');
        ?>
    </div>
    <div class="row">
        <?php
        if (isset($resultsToShow)) {

            if ($actionName == 'response') {
                $this->load->view('trackingMIS/nationalListings/showTable');
            } else if ($actionName == 'engagement') { // make this dynamic
                $this->load->view('trackingMIS/nationalListings/showTableEngagement');
            } else if ($actionName == 'registration') {
                $this->load->view('trackingMIS/nationalListings/showTableRegistration');
            } else if ($actionName == 'traffic') {
                $this->load->view('trackingMIS/nationalListings/showTableTraffic');
            }
        }
        ?>
    </div>
</div>