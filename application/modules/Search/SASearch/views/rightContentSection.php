<section class="resultsListing" id="resultsListing">
    <?php
        if($pageData['totalResultCount'] > 0){
    ?>
                <div class="holdList">
                    <?php $this->load->view('SASearch/courseTuple');?>
                </div>
    <?php       $this->load->view('SASearch/pagination');
            } else{
                $this->load->view('SASearch/ZRPPage');
            }
    ?>
</section>