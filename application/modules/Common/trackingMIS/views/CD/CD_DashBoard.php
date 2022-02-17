
<?php 
        $this->load->config('cdTrackingMISConfig');     
        $headings = $this->config->item("headings");
?>
<div class="row">
    <?php 
    if(isset($topTiles))
    {
        $data['overviewHeading'] = $headings['mainHeading'];
        $this->load->view('trackingMIS/CD/topTiles',$data);
    }
    ?>
    </div>
    <div class="row">
        <?php
        $data['headings'] = $headings['lineChart'];
        foreach ($topLineChartResults as $key => $value) {
            $data['splitType'] = $key;
            $data['mainHeading'] = $key;
                $data['splitData'] = $value['dataForRegular'];
                $data['splitDataForCompare'] = $value['dataForCompare'];

                $this->load->view('trackingMIS/CD/registrationGraph',$data);
        }
        /*if(!empty($RegistrationData))
        {
            _p($RegistrationData);
            die;
            $this->load->view('trackingMIS/CD/registrationGraph');
        }*/
        ?>
    </div>
    <div class="row">
       <?php
            $data['headings'] = $headings['lineChart'];
            foreach ($linearChartResult as $key => $value) {
                $data['splitType'] = $key;
                $data['splitData'] = $value['dataForRegular'];
                $data['splitDataForCompare'] = $value['dataForCompare'];
                $data['resultCount'] = $value['resultCount'];
               $this->load->view('trackingMIS/CD/answersCount',$data);
            }
        ?> 
    </div>
    <div class="row piechartdiv" id="piechartdiv">
       <?php
            $data['headings'] = $headings['pieChart'];
            foreach ($pieChartResult as $key => $value) {
               $i = 0; 
               $j = 0;
                foreach ($value as $get => $fetch) {
                    $data['splitType'] = $key;
                    $data['keyId'] = $i++;
                 $data['splitData'] = json_encode($fetch['pieChart']);
                $data['splitIndex'] = $fetch['pieChartIndex'];
                $data['totalCount'] = $fetch['pieChartResultCount'];   
                $this->load->view('trackingMIS/CD/deviceUsage',$data);
                }
               
               
            }

        ?> 
    </div>
    <div class="row" id="dataTablediv">
        <?php 
                if( ! empty($respondentsResult))
                {
                    $this->load->view('trackingMIS/CD/dataTable');
                }
        ?>
    </div>
    <div class="row">
        <?php if( ! empty($geosplit))
        {
            $this->load->view('trackingMIS/CD/geosplit');
        }
        ?>
    </div>
   