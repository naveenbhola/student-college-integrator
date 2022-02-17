                       <section class="prf-box-grey" >

                          <div class="prft-titl">
                               <div class="caption">
                                  <p>EDUCATIONAL BACKGROUND</p>
                               </div>
                               <?php if($publicProfile != true){?>
                               <div class="tools">
                                  <a href="javascript:void(0);" onclick="editUserProfile('educationalBackgroundSection','EDUCATIONAL BACKGROUND');" class="change">Edit</a>
                               </div>
                               <?php } ?>
                          </div>

                       <!--profile-tab content-->
                        <div class="frm-bdy">
                           <form action="#" class="prf-form" style="display:none">

                                <ul class="p-ul">
                                    <li class="p-l">
                                      <span class="p-s">
                                         <label>Course Level</label>

                                        <!--  <div class="custom-drp">
                                           <p class="custm-srch">Select<i class="icons ic_dropdownsumo"></i></p>
                                           <div class="custm-drpdwn-dflt">
                                             <ul class="drpdwn-ul">
                                               <li><a href="#">Selct option</a></li>
                                               <li><a href="#">Selct option</a></li>
                                               <li><a href="#">Selct option</a></li>
                                             </ul>
                                           </div>
                                         </div> -->

                                          <select class="dfl-drp-dwn" name="workExperience" id="totalWorkEx_<?php echo $regFormId; ?>" >
                                            <option value="1">ABC/option>
                                                  <?php 

                                                    foreach($WorkExRange as $value=>$description){ ?>
                                                          <option <?php  if ($value == $personalInfo['Experience'] ) { echo "selected='true'";} ?> value="<?php echo $value; ?>" > <?php echo $description; ?> </option>
                                                   <?php } ?>
                                          </select>

                                      </span>

                                       <span class="p-s">
                                         <label>School/College Name</label>
                                        <input type="text" class="prf-inpt"/>
                                      </span>
                                      
                                    </li>

                                    <li class="p-l">
                                      <span class="p-s">
                                         <label>Course Completion Year</label>
                                        <div class="custom-drp">
                                          <p class="custm-srch">Select<i class="icons ic_dropdownsumo"></i></p>
                                           <div class="custm-drpdwn-dflt">
                                             <ul class="drpdwn-ul">
                                               <li><a href="#">Selct option</a></li>
                                               <li><a href="#">Selct option</a></li>
                                               <li><a href="#">Selct option</a></li>
                                             </ul>
                                           </div>
                                         </div>
                                      </span>

                                       <span class="p-s">
                                         <label>Board</label>
                                         <div class="custom-drp">
                                           <p class="custm-srch">Select<i class="icons ic_dropdownsumo"></i></p>
                                           <div class="custm-drpdwn-dflt">
                                             <ul class="drpdwn-ul">
                                               <li><a href="#">Selct option</a></li>
                                               <li><a href="#">Selct option</a></li>
                                               <li><a href="#">Selct option</a></li>
                                             </ul>
                                           </div>
                                         </div>
                                      </span>
                                    </li>


                                    <li class="p-l">
                                      <span class="p-s">
                                         <label>Subjects</label>
                                         <input type="text" class="prf-inpt"/>
                                      </span>

                                       <span class="p-s">
                                         <label>Marks</label>
                                         <div class="custom-drp">
                                           <p class="custm-srch1">Select</p>
                                           <div class="custm-drpdwn-dflt">
                                             <ul class="drpdwn-ul-1">
                                               <li><a href="#">Selct option</a></li>
                                               <li><a href="#">Selct option</a></li>
                                               <li><a href="#">Selct option</a></li>
                                             </ul>
                                           </div>
                                         </div>

                                          <div class="custom-drp1">
                                           <p class="custm-srch1">Select</p>
                                           <div class="custm-drpdwn-dflt">
                                             <ul class="drpdwn-ul-1">
                                               <li><a href="#">Selct option</a></li>
                                               <li><a href="#">Selct option</a></li>
                                               <li><a href="#">Selct option</a></li>
                                             </ul>
                                           </div>
                                         </div>
                                      </span>
                                    </li>


                                 </ul>

                              <div class="prf-btns">
                                <div class="lft-sid">
                                   <a href=""><i class="icons ic_addwrk"></i>Add school/College Details</a>
                                 </div>
                                <section class="rght-sid">
                                 <a href="#" class="btn-grey">Cancel</a>
                                 <a href="#" class="btn_orngT1">Save</a>
                                </section> 
                              </div>
                               <p class="clr"></p>
                           </form>
                           
                          <div class="prf-edu">
                               <span class="edu-bck">
                                 <i class="icons1 ic_edu"></i><p>Class X</p>
                               </span>
                               <div class="edu-dtls btm">
                                  <p>Studied @<span>Delhi Public School, Indirapuram</span></p>
                                  <b>Class of 2008, CBSE</b>
                                  <p>Subjects taken:<span>Physics, Chemistry, Maths, English</span></p>
                                  <p>Marks obtained:<span>CGPA  9</span></p>
                               </div>
                          </div> 
                           
                          <div class="prf-edu">
                               <span class="edu-bck">
                                 <i class="icons1 ic_edu"></i><p>Class XII</p>
                               </span>
                               <div class="edu-dtls">
                                  <p>Studied @<span>Delhi Public School, Indirapuram</span></p>
                                  <b>Class of 2008, CBSE</b>
                                  <p>Subjects taken:<span>Physics, Chemistry, Maths, English</span></p>
                                  <p>Marks obtained:<span>CGPA  9</span></p>
                               </div>
                          </div>  
                            
                           
                           <div class="prf-btns">
                                <div class="lft-sid">
                                   <a href=""><i class="icons ic_addwrk"></i>Add your graduation details</a>
                                 </div>
                               
                           </div>
                            <p class="clr"></p>
                        </div>
                       </section>