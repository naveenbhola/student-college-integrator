<?php
  $institute = $courseData['institute'];
  $courses = $courseData['courses'];
  $university = $courseData['university'];
  $dataCity = $cityState;
  $dbCourses = $dbValues['course'];
  $dbInstitute = $dbValues['institute'];
  $dbUniversity = $dbValues['university'];
  $virtualCityMapping = $cityState["virtualCityMapping"];
  $childVirtual = $cityState["childVirtualMapping"];
  // _p($childVirtual);
  // _p($virtualCityMapping);
  if (empty($courses)){ ?> 
      <div class="nopaid-data">No Paid Courses for this institute</div>
      <div class="btn-flexbox">
          <button type="button" name="button" class="pwa-btns pwa-secondary"  onclick="showPage(2)">Back</button>
          </div>
  <?php return;
}


 ?>

<div>
  <input type="hidden" 
  name="virtualCityMapping" 
  value = '<?php echo json_encode($virtualCityMapping);?>' ">
  <input type="hidden" 
  name="childVirtual" 
  value = '<?php echo json_encode($childVirtual);?>' ">
</div>

<div class="clpwrapper" >
    <div class="clp-response">
      <h2 class="note-h2">Note:</h2>
      <div class="summarypoints">
        <ol>
          <li>Response Delivery Criteria (RDC) of only currently paid CLP's can be controlled from here.</li>
          <li>For CLP's which would become paid in future - Thier response delivery criteria will have to explicitly set after they become paid.</li>
          <li>When a CLP becomes <strong>free to paid</strong> it's RDC will <strong>always be set to default</strong> (Student location - All India, Send CVR -Switched On, Send IVR - Switched On)</li>
          <li>When a CLP’s (say, CLP1 having parent university/institute as UILP1) <strong>associated Client Id is changed</strong> (CL1 to CL2) then RDC of all sibling CLPs (corresponding to CLP1 and belonging to same parent university/institute UILP1) will be <strong>set to default</strong> (Student location - All India, Send CVR - Switch On, Send IVR - Switched On)</li>
          <li>Response once excluded based on the criteria selected below cannot be delivered again to client.</li>
          <li>IVR = UILP viewed Response</li>
          <li>CVR = CLP viewed Response</li>
          <li>Please click on submit to ensure that RDC is saved. This RDC will be applicable for all future response across all Response Delivery Modes(Porting/Response viewer/SMS/Email).</li>
          <li>To close student location layer drop-down, please click on any student location drop-down on this page.</li>
        </ol>
      </div>
      <div class="clptable-block">
        <table>

          <?php if (!empty($institute)) {?>
          <tr>
            <th>ILP Id</th>
            <th>ILP Name</th>
            <th>IVR by Student Location</th>
          </tr>
          <div style="display: none" name = "institute" keys='keys' value = <?php echo $institute['listing_type_id']?> ></div>
           <tr>
            <td><?php echo $institute['listing_type_id']?></td>
            <td><strong><?php echo $institute['listing_title']?></strong></td>
            <td>
              <div class="clp-flex">
                
                <input type="checkbox" tickbox="tickbox" name="institute_clpresponse_<?php echo $institute['listing_type_id'] ?>" id="clpresponse1" class="filter-chck" <?php 
                  // _p($dbInstitute[$institute['listing_type_id']]['actionValue']!=0);
                  if (empty($dbInstitute[$institute['listing_type_id'].'IVR']['actionValue']) ||
                      $dbInstitute[$institute['listing_type_id'].'IVR']['actionValue'] == "show"){
                      echo "checked";
                  } 

                ?> 
                onClick ="toggleDropDown(this)"
                noncta = "noncta<?php echo $institute['listing_type_id']?>_"
                />
                
                <label for="clpresponse1" class="checkbox-label blue-label">Send IVR</label>
                <div class="clpresponse-dropbox" >
                  <div class="clpresponse-custmdrpdwn clpresponse1" noncta = "noncta<?php echo $institute['listing_type_id']?>" onclick="toggleActiveClass(this)"  >
                  <p>All India </p>
                  <i class="carret-arw"></i>
                    <div class="clpresponse-layer">
                      <?php  
                      $dataCity["id"] = "institute_ivr_".$institute['listing_type_id'];
                      $dataCity["location"] = $dbInstitute[$institute['listing_type_id'].'IVR']["locationId"];
                      $this->load->view('courseResponseAccess/cityDropDown',$dataCity); 
                      ?>
                    </div>
                  </div>
                </div>
              </div>
            </td>
          </tr>
          <?php } else {?>
          <tr>
            <th>ULP Id</th>
            <th>ULP Name</th>
            <th>UVR by Student Location</th>
          </tr>
          <div style="display: none" name = "university" keys='keys' value = <?php echo $university['listing_type_id']?> ></div>
           <tr>
            <td><?php echo $university['listing_type_id']?></td>
            <td><strong><?php echo $university['listing_title']?></strong></td>
            <td>
              <div class="clp-flex">
                <input type="checkbox" tickbox="tickbox" name="university_clpresponse_<?php echo $university
                ['listing_type_id']?>" id="clpresponse1" class="filter-chck"  
                <?php 
                  // _p($dbInstitute[$institute['listing_type_id']]['actionValue']!=0);
                  if (empty($dbUniversity[$university['listing_type_id'].'IVR']['actionValue']) ||
                      $university[$university['listing_type_id'].'IVR']['actionValue'] == "show"){
                      echo "checked";
                  } 

                ?>   
                noncta = "noncta<?php echo $university['listing_type_id']?>_"
                onClick ="toggleDropDown(this)"/>
                <label for="clpresponse1" class="checkbox-label blue-label">Send IVR</label>
                <div class="clpresponse-dropbox">
                  <div class="clpresponse-custmdrpdwn clpresponse1" noncta = "noncta<?php echo $university['listing_type_id']?>" onclick="toggleActiveClass(this)" >
                    <p>All India </p>
                    <i class="carret-arw"></i>
                    <div class="clpresponse-layer">
                      <?php  
                      $dataCity["id"] = "university_ivr_".$university['listing_type_id'];
                      $dataCity["location"] = $dbUniversity[$university['listing_type_id'].'IVR']["locationId"];
                      $this->load->view('courseResponseAccess/cityDropDown',$dataCity); 
                      ?>
                    </div>

                  </div>
                </div>
              </div>
            </td>
          </tr>
          <?php } ?>

         
        </table>
      </div>
      <div class="clptable-block">
        <table>
          <tr>
            <th>CLP Id</th>
            <th>CLP Name</th>
            <th>CTA Response by Student Location</th>
            <th>CVR by Student Location</th>
          </tr>
          
          <?php foreach ($courses as $course) {?>

            <div style="display: none"  keys = "keys" name ="course" value =<?php echo $course['listing_type_id'] ?> ></div>
            <tr>
              <td><?php echo $course['listing_type_id']?></td>
              <td><strong><?php echo $course['listing_title']?></strong></td>
              <td>
                <div class="clp-flex">
                  <div class="clpresponse-dropbox">
                    <div class="clpresponse-custmdrpdwn filter-chck-drpdwn" onclick="toggleActiveClass(this)" >
                      
                      <p>All India </p>
                      <i class="carret-arw"></i>
                      <div class="clpresponse-layer">
                         <?php  
                        $dataCity["id"] = "course_nvr_".$course['listing_type_id'];
                        $dataCity["location"] = $dbCourses[$course['listing_type_id'].'NVR']["locationId"];
                      
                        $this->load->view('courseResponseAccess/cityDropDown',$dataCity); 
                        ?>
                      </div>
                    </div>
                  </div>
                </div>
              </td>
              <td>
                <div class="clp-flex">
                  <input type="checkbox" tickbox="tickbox" name= "course_clpresponse_<?php echo $course['listing_type_id']?>" id="<?php echo $course['listing_type_id']?>clpresponse11" class="filter-chck"  
                  <?php 
                  //_p($dbInstitute[$institute['listing_type_id']]['actionValue']);
                  if (empty($dbCourses[$course['listing_type_id'].'CVR']['actionValue']) ||
                      $dbCourses[$course['listing_type_id'].'CVR']['actionValue'] == "show"){
                      echo "checked";
                  } 

                  ?> 
                  noncta = "noncta<?php echo $course['listing_type_id']?>_"
                  onClick ="toggleDropDown(this)"/>
                  <label for="<?php echo $course['listing_type_id']?>clpresponse11" class="checkbox-label blue-label">Send CVR</label>
                  <div class="clpresponse-dropbox">
                    <div class="clpresponse-custmdrpdwn <?php echo $course['listing_type_id']?>clpresponse11" onclick="toggleActiveClass(this)"  noncta = "noncta<?php echo $course['listing_type_id']?>" >
                      <p>All India </p>
                      <i class="carret-arw"></i>
                      <div class="clpresponse-layer">
                         <?php 
                          // CVR response 
                           $dataCity["id"] = "course_cvr_".$course['listing_type_id'];
                             
                          $dataCity["location"] = $dbCourses[$course['listing_type_id'].'CVR']["locationId"];
                          $this->load->view('courseResponseAccess/cityDropDown',$dataCity); 
                           ?>
                      </div>
                    </div>
                    <!-- <p class="copyTxt"><i class="pwaicono-arrow-left"></i>Copy these lcoations below</p> -->
                  </div>
                </div>
              </td>
            </tr>
          <?php }?>
        </table>
      </div>

      <div class="submit-col">
        <p>Please click on <strong>submit</strong> to ensure all criteria are saved in data base and reflected across all Response Delivery Modes (Porting/Response viewer/SMS/Email)</p>
        <div class="btn-flexbox">
          <button type="button" name="button" class="pwa-btns pwa-secondary"  onclick="showPage(2)">Back</button>
          <button type="button" name="button" id="savebutton" class="pwa-btns pwa-primary" onclick="submitRDCCriteria()">Submit</button>
        </div>
      </div>

    </div>
</div>


<!-- //$j(".active").removeClass("active"); -->
