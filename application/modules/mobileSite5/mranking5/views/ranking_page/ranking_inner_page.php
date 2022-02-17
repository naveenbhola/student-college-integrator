<div class="ranking__block">
    <div class="ranking__Result pad__all__10" id="hide__div">
        <h1 class="f16__bold clr__1"><?php echo $meta_details['h1']; ?></h1>
        <p class="f12__normal clr__6" >
        <span id='disclaimerReadMore'>
        <?php if(strlen($rankingPageDisclaimer)>36)
        {
         echo substr($rankingPageDisclaimer,0,36).'... <a href="javascript: void(0);" class="link" id="ReadFullText"> Read More</a></span>';  
        ?>
        <span class='hid' id='disclaimerFullText'> <?php echo $rankingPageDisclaimer;?> </span>
        <?php
        }
        else
        echo $rankingPageDisclaimer;    
        ?>
        </p>
    </div>
    <div class="data__block">
        <?php $this->load->view('mranking5/ranking_page/ranking_page_filter_section');  ?>

        <?php 
            if($tupleType=='institute'){
                $this->load->view("mobile_listing5/institute/widgets/courseCompareLayer");
        ?>
            <div class="select-Class">
                <select name="customCourseSelect" class="hid" id="customCourseSelect">
                </select>
            </div>
            <div>
                <input type="hidden" id="customCourseSelect_input">
                </input>
            </div>
        <?php
            }
        ?>

        <!--touples start-->
        <div id="rankingPageTable" class="rank__mb__col clear__float rank-block">
            <?php $this->load->view('mranking5/ranking_page/ranking_page_table',$data);  ?>
        </div>
        <!--related links-->
        <?php $this->load->view('mranking5/ranking_page/ranking_page_interlinking') ?>
        <div class="search_fill_layer"></div>
    </div>
</div>
