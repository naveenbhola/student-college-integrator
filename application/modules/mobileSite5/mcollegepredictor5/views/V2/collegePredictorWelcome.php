 <div class="clg-predctr" id="cp-banner">
     <div class="clg-bg <?php if(strtoupper($examName) == 'NIFT') echo 'niftBanner'; ?>" id="clg-bg">
         <?php $this->load->view('CP/V2/breadcrumb');?>
         <div class="clg-txBx">
                  <?php if ($examName == "jee-mains") { ?>
                    <p><span><?php echo strtoupper($examNameDisplay)." College Predictor ".$examYear ?></span></p>
                    <h2 class="sb-hdng">Enter your <?php echo strtoupper($examNameDisplay)." ".date('Y')?> rank to <br> predict your colleges</h2>
                    <?php } else {?>
                <p><span><?php echo strtoupper($examNameDisplay)." ".$examYear ?></span> College Predictor</p>
                <p class="sb-hdng">Use your <?php echo strtoupper($examNameDisplay)." ".$examYear ?> <?php echo (!empty($invertLogic) && $invertLogic==1)?"score":"rank"; ?> to see<br> where you can get admission</p>
                <?php }  if ($examName == "jee-mains") { ?>
              <h3>  
		<ul>
		  <li>Updated with JoSAA <?php echo $examYear;?> closing ranks.</li>
                  <li>See round-wise cut offs for 3500 courses in 800 colleges</li>
                  <li>Get information of JEE Main Colleges Cut off, Fees, Admission and Placement Reviews.</li>
                  <li>Get colleges you may crack basis your category and home state rank also.</li>
                </ul>
              </h3>
                  <?php } else { ?>
                    <ul>
                      <li>See the courses, colleges & rounds you may crack</li>
                      <li>The result is based on admissions data of <?php echo strtoupper($examNameDisplay)." ".(empty($examYear)?"":$examYear-1) ?></li>
                    </ul>
                 
               <?php } ?>
         </div>
     </div>    
 </div>
