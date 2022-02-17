<?php
    $this->load->view("allcontent/widgets/topSection");
?>
<div class="new-container">
        <?php
            switch ($pageType) {
                case 'articles':
                     $this->load->view("allcontent/articleDetailsSection");
                     break;

                case 'questions':
                     $this->load->view("allcontent/anaDetailsSection");
                     break;

                case 'reviews':
                     $this->load->view("allcontent/reviewDetailsSection");
                     break;

                 default:
                     # code...
                     break;
            }
        ?>

<?php
    $this->load->view("allcontent/widgets/pagination");
    echo modules::run('mobile_listing5/AllContentPageMobile/getRelatedLinks',$listing_id,$listing_type,$pageType,$courseIdsMapping);
?>
<?php if($pageType == 'reviews'){?>
    <div class="disclaimer">
    <p><strong>Disclaimer:</strong>All reviews are submitted by current students & alumni of respective colleges and duly verified by Shiksha.</p>
    </div>
<?php } ?>
</div>
