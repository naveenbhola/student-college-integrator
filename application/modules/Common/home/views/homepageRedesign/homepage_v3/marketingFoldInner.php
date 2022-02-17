<div class="_cntr">
    <div class="marketingCont">
        <div class="fltlft addvertisement">
        <?php 
        if($data[0]['type'] == 'videoOnly'){
            $this->load->view('home/homepageRedesign/homepage_v3/marketingFoldVideo',$data);
        }
        elseif($data[0]['type'] == 'imageWithText'){
            $this->load->view('home/homepageRedesign/homepage_v3/marketingFoldImageWithCaption',$data); 
        }
        elseif($data[0]['type'] == 'imageOnly'){
            $this->load->view('home/homepageRedesign/homepage_v3/marketingFoldSingleImage',$data);
        }?>
        </div>
        <?php echo Modules::run('shiksha/getTestimonials','homepage'); ?>
        <div class="clr"></div>
    </div>
</div>