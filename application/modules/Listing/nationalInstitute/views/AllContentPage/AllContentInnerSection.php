<div class="allContentLoader" id="allContentLoader">
    
</div>
<div class="bg-color" id="innerPageContent">
    <?php $this->load->view("AllContentPage/widgets/topSectionSticky"); ?>
    <div class="container-fluid ana-main-wrapper">
        <!---->
        <div class="new-container new-breadcomb ana-container">
            <?php 
                $this->load->view("AllContentPage/widgets/breadcrumb");
            ?>

            <div class="new-row" id="topSection">
                <div class="group-card gap clrTpl no-pad">
                    <?php 
                        $this->load->view("AllContentPage/widgets/topSection");

                        $this->load->view("AllContentPage/widgets/filters");
                    ?>
                </div>
            </div>
            <div class="new-row main-content">
                <?php 
                    $this->load->view("AllContentPage/AllContentLeftSection");
                    $this->load->view("AllContentPage/AllContentRightSection");
                ?>
            </div>
            <?php 
                if($pageType !='admission' && $pageType != 'scholarships'){
                    $this->load->view("AllContentPage/widgets/pagination");
                }
                if($pageType == 'scholarships'){
                    echo $anaWidget['html'];
                }
                $this->load->view("AllContentPage/widgets/disclaimer");
            ?>
            
        </div>          
            
    </div>

    <a href="javascript:void(0);" class="scrollToTop"></a>

</div>