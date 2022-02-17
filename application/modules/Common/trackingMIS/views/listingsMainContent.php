<!-- page content -->
<div class="right_col" role="main">          
    <!-- top tiles -->
    <?php
//        $this->load->view('trackingMIS/nationalListings/topTiles');
//        $this->load->view('trackingMIS/nationalListings/networkActivities');
    ?>
    <br/>
    <div class="row">
       <?php
       foreach ($splitInformation as $index => $value) { // For list of valid identifiers, see Listings controller
           $data = array(
               'splitData' => $value,
               'splitType' => $index
           );
           $this->load->view('trackingMIS/nationalListings/showSplit', $data);
       }
        ?>
    </div>
</div>