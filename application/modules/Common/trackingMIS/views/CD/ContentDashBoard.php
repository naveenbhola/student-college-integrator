 <?php 
        $this->load->config('cdTrackingMISConfig');     
        $headings = $this->config->item("headings");
?>
<div class="row">
    <?php if(isset($topTiles)) 
    {
        $data['overviewHeading'] = $headings['mainHeading'];
        $this->load->view('trackingMIS/CD/topTiles',$data);
    }
     ?>
</div>
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
<div class="row">
        <?php 
                if( ! empty($respondentsResult))
                {
                    $this->load->view('trackingMIS/CD/dataTable');
                }
        ?>
    </div>