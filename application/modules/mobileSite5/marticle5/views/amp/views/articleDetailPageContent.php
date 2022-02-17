<div class="art-cont">
    <?php $this->load->view('mcommon5/AMP/dfpBannerView',array('bannerPosition' => 'leaderboard'));?>
    <section>
        <div class="art-crd color-w pos-rl">
            <input type="checkbox" class="read-more-state hide" id="post-11">
            <div class="art-crd-hgt read-more-wrap">
                <?php 
                $blogLayout = $blogObj->getBlogLayout(); 
                switch ($blogLayout) {
                    case 'qna':
                        $this->load->view('marticle5/amp/views/blogDetailsQnA');
                        break;
                    default:
                        $this->load->view('marticle5/amp/views/blogDetailDefault');
                        break;
                }
                ?>
            </div>
            <label for="post-11" class="read-more-trigger color-b t-cntr f16 font-w6 block">
            <span class='ga-analytic' data-vars-event-name="ReadMore">Read Full Article</span>
            </label>
        </div>
    </section>

   
    <?php
        $this->load->view("mcommon5/socialShareThis",array('pageType'=>'ampPage','className'=>'shadow'));
        $this->load->view('mcommon5/AMP/dfpBannerView',array("bannerPosition" => "LAA"));

        $this->load->view('marticle5/amp/views/articleRegistrationWidget');

        if($streamCheck == 'fullTimeMba'){
            $this->load->view('marticle5/amp/views/mbaToolWidget');
        }

        $this->load->view('marticle5/amp/views/recentArticles');
        
        $this->load->view('marticle5/amp/views/showRelatedArticle');

        $this->load->view('article/articleSchemaMarkup');
        
        echo Modules::run("Interlinking/InterlinkingFactory/getEntityRHSWidget", $blogEntitiesMapping,'articleDetailPage',4,3,true,$blogId,'blog');
    
        echo Modules::run("Interlinking/InstituteWidget/getRelatedInstituteWidget", $blogEntitiesMapping,'articleDetailPage',$blogId,'blog',true);
    
        echo Modules::run("Interlinking/ExamWidget/getRelatedExamWidget", $blogEntitiesMapping,'articleDetailPage',true);
        $this->load->view('mcommon5/chpInterLinking');

        echo Modules::run("Interlinking/ExamWidget/getUpcomingExamDatesWidget", $blogEntitiesMapping,'articleDetailPage',true);

        if($blogObj->getType() == 'boards' || $blogObj->getType() == 'coursesAfter12th'){
             echo html_entity_decode($ampHTML);
        }
        
    ?>

  
</div>


