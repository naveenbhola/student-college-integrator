 <div id="registrationStarts" class="prsnl-dtls">
                               <section class="prsnl-cont clearfix">
                                   <article class="prsnl-box">
                                       <div class="dtls">
                                          <div class="ph">
                                             <h3>Additional Details</h3>	
                                             <p>Please provide the following information to view detailed results.</p>
                                         
                                             <div class="academic-dtls">
                                                  <?php echo Modules::run('registration/Forms/LDB',NULL,'iimPredictor', array('preSelectedCategoryId'=>'3', 'preSelectedDesiredCourse'=>'2', 'gradYear'=>$gradYear, 'exams'=>array('CAT'), 'CAT_score'=>$catScore)); ?>
                                             </div>
                                             </div>
                                         </div>
                                   </article>
                               </section>
                              
                              </div>
                   <!--personal div ends--> 
                </div>
                 <!-- right panel start -->
<!--                  <aside>
                    <div class="aside col-lg-3 pL0">
                        
                        <div class="left_nav">
                           <div class="predictor-container">
                              
                            </div>
                        </div>
                    </div>
                </aside> -->
                <!-- left panel ends -->
                <!-- right panel ends here -->
                <p class="clr"></p>
            </div>
        </section>
    </div>