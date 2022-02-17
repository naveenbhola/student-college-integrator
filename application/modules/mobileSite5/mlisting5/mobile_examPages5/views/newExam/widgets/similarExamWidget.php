 <section>
            <div class="data-card">
                <h2 class="color-3 f16 heading-gap font-w6">Exams similar to <?=$examName;?></h2>
                <div class="lcard color-w f14 color-3">
                   <ul class="cls-ul ins-acc-ul ex-ul">
                        <?php $this->load->view('mobile_examPages5/newExam/widgets/similarExamData');?>
                    </ul>
                    <?php if($similarExams['isViewLink']) { ?>
                        <div class="btn-sec">
                            <a class="btn btn-secondary color-w color-b f14 font-w6 m-15top m-5btm" id="viewAllExams" href="javascript:void(0);" ga-attr="VIEW_ALL_SIMILAR_EXAMS">View All Similar Exams</a>
                        </div>
                    <?php } ?>

                </div>
               </div>
          </section>