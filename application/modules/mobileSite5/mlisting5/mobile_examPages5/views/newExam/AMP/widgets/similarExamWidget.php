 <section>
    <div class="data-card">
        <h2 class="color-3 f16 heading-gap font-w6">Exams similar to <?=$examName;?></h2>
        <div class="card-cmn color-w f14 color-3">
            <ul class="cls-ul ins-acc-ul ex-ul">
                <?php $this->load->view('mobile_examPages5/newExam/AMP/widgets/similarExamData',array('isLimit' => true));?>
            </ul>
            <?php if($similarExams['isViewLink']) { ?>
                <div class="btn-sec">
                    <a class="btn btn-secondary color-w color-b f14 font-w6 m-15top ga-analytic" data-vars-event-name="VIEW_ALL_SIMILAR_EXAMS" on="tap:viewAllExams" role="button" tabindex="0">View All Similar Exams</a>
                </div>
                <amp-lightbox class="" id="viewAllExams" layout="nodisplay" scrollable>
                    <div class="lightbox">
                        <div class="color-w full-layer">
                            <div class="f14 color-f bg-clr-b pad__110 font-w6">Exams Similar to <?=$examName;?><a class="cls-lightbox color-3 font-w6 t-cntr" on="tap:viewAllExams.close" role="button" tabindex="0">×</a>
                            </div>
                            <div class="col-prime-arrow pad10">
                                <ul class="cls-ul ins-acc-ul ex-ul">
                                    <?php $this->load->view('mobile_examPages5/newExam/AMP/widgets/similarExamData',array('isLimit' => false));?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </amp-lightbox> 
               <?php } ?>
           </div>
       </div>
</section>
          