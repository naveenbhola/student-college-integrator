<h2><?php echo htmlentities($heading);?></h2>
<?php if($count >0){?>
<div class="row">
	 <!--rleft-->
	   <div class="col-sm-4 col-start">
	   	  <div class="stu-list">
	   	  	<ul>
          <?php 
          $count=0;
          foreach($studentDetail as $key=>$value){ ?>
	   	  		<li class="snapDtl" hideClass="<?php echo $tabName;?>" linkfor="<?php echo $tabName.$count;?>">
	   	  			<a href="javaScript:void(0);">
	   	  				<div>
	   	  					<img src="<?php echo ($value['imageUrl']==''?'/public/images/photoNotAvailable.gif':getImageUrlBySize($value['imageUrl'],'s'));?>" class="img-block" >
	   	  					 <div class="side-col">
	   	  					 	<p class="abroad-stu-name f14 ctgray"><?php echo htmlentities($value['firstName']." ".$value['lastName']);?></p>
	   	  					 	<?php if($value['courseName']!=''){?>
                    <p class="abroad-exams f12 ctgray"><?php echo htmlentities($value['courseName']); ?></p>
                    <?php } ?>
	   	  					 	<p class="abroad-exams f12 ctgray">
                        <?php 
                       $examStr = "";
                       foreach($value['exams'] as $examName=>$score){
                        $examStr.= $examName.":".$score.", ";
                        }
                        echo substr($examStr,0,strlen($examStr)-2);
                        ?>
                    </p>
	   	  					 </div>
	   	  				</div>
	   	  			</a>
	   	  		</li>
          <?php $count++;} ?>  
	   	  	</ul>
	   	  </div>
	   </div>

	   <!---->
	   <div class="col-sm-8 " style="padding-bottom: 10px;">
      <?php 
      $count=0;
      foreach($studentDetail as $key=>$value){?>
	   	  <div class="x-panel max-len <?php echo $tabName;?> <?php echo $tabName.$count;?>" style="<?php if($count>0){ echo "display: none;";}?>" >
	   	   

	   	  	<div class="container">
	   	  		<div class="img-new" >
	   	  			<img height="78" width="117" src="<?php echo ($value['imageUrl']==''?'/public/images/photoNotAvailable.gif':getImageUrlBySize($value['imageUrl'],'m'));?>" />
	   	  		</div>
	   	  		<div class="img-desc">
	   	  			<p class="f18 ctgray"><?php echo htmlentities($value['firstName']." ".$value['lastName']);?></p>
	   	  			<p class="f12 ctgray"><?php echo htmlentities($value['cityName']); ?> <?php echo htmlentities($value['stateName']);?></p>
	   	  			<p class="f12 ctgray">Mobile: <?php echo htmlentities($value['ISDCode']."-".$value['mobile']);?></p>
	   	  			<p class="f12 ctgray">email: <?php echo htmlentities($value['email']);?></p>
	   	  		</div>
	   	  	</div>
      <!--details-->
	   	  	<div class="container top-container">
              <?php if($value['courseName']!=''){?>
	   	  		   <div class="control-group">
                  <label class="contrl-label f14 ctgray">Course intrested in:</label>
                   <div class="contrl">
                      
                      <p class="f14 ctgray"><?php echo htmlentities($value['courseName']); ?></p>
                     
                   </div>
               </div>
              <?php } ?>
              <?php if($value['termSeason']!=''){?>
               <div class="control-group">
                  <label class="contrl-label f14 ctgray">Intake:</label>
                   <div class="contrl">
                       <p class="f14 ctgray"><?php echo $value['termSeason']." ".$value['termYear'];?></p>
                   </div>
               </div>
               <?php } ?> 
               <?php if(count($value['exams'])>0){?>
               <div class="control-group">
                  <label class="contrl-label f14 ctgray">Exams:</label>
                   <div class="contrl">
                       <p class="f14 ctgray"><?php 
                       $examStr = "";
                       foreach($value['exams'] as $examName=>$score){
                        $examStr.= $examName.":".$score.", ";
                        }
                        echo substr($examStr,0,strlen($examStr)-2);
                        ?></p>
                   </div>
               </div>
               <?php } ?>
               <?php if($value['currentClass']!=''){?>
               <div class="control-group">
                  <label class="contrl-label f14 ctgray">Current Class:</label>
                   <div class="contrl">
                       <p class="f14 ctgray"><?php echo htmlentities($value['currentClass']); ?></p>
                   </div>
               </div>
               <?php } ?>
               <?php if($value['currentSchool']!=''){?>
               <div class="control-group">
                  <label class="contrl-label f14 ctgray">Current School:</label>
                   <div class="contrl">
                       <p class="f14 ctgray"><?php echo htmlentities($value['currentSchool']); ?></p>
                   </div>
               </div>
               <?php } ?>
               <?php 
               //_p($value['education']);
               if(count($value['education']['10'])> 0){

                if($value['education']['10']['board']=='CBSE'){
                  global $CBSE_Grade_Mapping;
                  $marks = array_flip($CBSE_Grade_Mapping);
                  $realMarks = $marks[$value['education']['10']['marks']];
                }elseif($value['education']['10']['board']=='IGCSE'){
                  global $IGCSE_Grade_Mapping;
                  $marks = array_flip($IGCSE_Grade_Mapping);
                  $realMarks = $marks[$value['education']['10']['marks']];
                }else{
                  $realMarks = $value['education']['10']['marks'];
                }


                ?>
               <div class="control-group">
                  <label class="contrl-label f14 ctgray">Class 10th board:</label>
                   <div class="contrl">
                       <p class="f14 ctgray"><?php echo htmlentities($value['education']['10']['board']); ?></p>
                   </div>
               </div>
               <?php if($realMarks !=''){?>
               <div class="control-group">
                  <label class="contrl-label f14 ctgray">Class 10th grades:</label>
                   <div class="contrl">
                       <p class="f14 ctgray"><?php echo htmlentities($realMarks); ?></p>
                   </div>
               </div>
               <?php }} ?>


               <?php if($value['graduation']!=''){?>
               <div class="control-group">
                  <label class="contrl-label f14 ctgray">Graduation:</label>
                   <div class="contrl">
                       <p class="f14 ctgray"><?php echo htmlentities($value['graduation']); ?></p>
                   </div>
               </div>
               <?php } ?>
               <?php if($value['workXP'] !=''){?>
               <div class="control-group">
                  <label class="contrl-label f14 ctgray">Work exp:</label>
                   <div class="contrl">
                       <p class="f14 ctgray"><?php echo htmlentities($value['workXP']);?></p>
                   </div>
               </div>
               <?php } ?>
               <?php if($value['currentCounsellor']!=''){?>
               <div class="control-group">
                  <label class="contrl-label f14 ctgray">Current Counselor assigned:</label>
                   <div class="contrl">
                       <p class="f14 ctgray"><?php echo htmlentities($value['currentCounsellor']); ?></p>
                   </div>
               </div>
               <?php } ?>
               <?php if($value['userCurrentStage']!=''){?>
                <div class="control-group">
                  <label class="contrl-label f14 ctgray">Current Stage of Student:</label>
                   <div class="contrl">
                       <p class="f14 ctgray"><?php echo htmlentities($value['userCurrentStage']); ?></p>
                   </div>
               </div>
               <?php } ?>


               <?php if(count($applied[$value['userId']])>0){?>
               <?php if($applied[$value['userId']]['visaStatus']!=''){?>
                <div class="control-group">
                  <label class="contrl-label f14 ctgray">Visa Status of Student:</label>
                   <div class="contrl">
                      <?php $visaStatusText = '';
                      if($applied[$value['userId']]['visaStatus']=="yes"){
                          $visaStatusText = 'Accepted';
                      }elseif($applied[$value['userId']]['visaStatus']=="no"){
                          $visaStatusText = 'Rejected';
                      }else{
                          $visaStatusText = 'In Progress';
                      }
                      ?>
                       <p class="f14 ctgray"><?php echo htmlentities($visaStatusText); ?></p>
                   </div>
               </div>
               <?php } ?>
               <?php if($applied[$value['userId']]['visaReason']!=''){?>
                <div class="control-group">
                  <label class="contrl-label f14 ctgray">Reason for Visa accept/reject:</label>
                   <div class="contrl">
                       <p class="f14 ctgray"><?php echo htmlentities($applied[$value['userId']]['visaReason']); ?></p>
                   </div>
               </div>
               <?php } ?>
               <?php } ?>

               <?php if(count($applied[$value['userId']])>0){?>
               <?php if($applied[$value['userId']]['enrollmentStatus']!=''){?>
                <div class="control-group">
                  <label class="contrl-label f14 ctgray">Admitted Status of Student:</label>
                   <div class="contrl">
                      <?php $enrollmentStatusText='';
                      if($applied[$value['userId']]['enrollmentStatus']=='yes'){
                          $enrollmentStatusText = 'Admitted';
                      }
                      ?>
                       <p class="f14 ctgray"><?php echo htmlentities($enrollmentStatusText); ?></p>
                   </div>
               </div>
               <?php } ?>
               <?php if($applied[$value['userId']]['enrollmentReason']!=''){?>
                <div class="control-group">
                  <label class="contrl-label f14 ctgray">Reason for admission of Student:</label>
                   <div class="contrl">
                       <p class="f14 ctgray"><?php echo htmlentities($applied[$value['userId']]['enrollmentReason']); ?></p>
                   </div>
               </div>
               <?php } ?>
               <?php } ?>



               <?php if($value['shortlistSentOn']!=''){?>
               <div class="control-group">
                  <label class="contrl-label f14 ctgray">Shortlist sent date:</label>
                   <div class="contrl">
                       <p class="f14 ctgray"><?php echo $value['shortlistSentOn'];?></p>
                   </div>
               </div>
               <?php } ?>
			   <?php if($scholarship[$key]['scholarshipReceived']!=''){?>
               <div class="control-group">
                  <label class="contrl-label f14 ctgray">Scholarship Received:</label>
                   <div class="contrl">
                       <p class="f14 ctgray"><?php echo $scholarship[$key]['scholarshipReceived'];?></p>
                   </div>
               </div>
			   <?php } ?>
			   <?php if($scholarship[$key]['scholarshipDetails']!=''){?>
			   <div class="control-group">
                  <label class="contrl-label f14 ctgray">Scholarship Details:</label>
                   <div class="contrl">
                       <p class="f14 ctgray"><?php echo $scholarship[$key]['scholarshipDetails'];?></p>
                   </div>
               </div>
               <?php } if( $candidatesDocumentsDetails[$key] != ''){?>
                   <div class="control-group">
                       <label class="contrl-label f14 ctgray">Documents:</label>
                       <div class="contrl">
                       <?php
                       foreach ($candidatesDocumentsDetails[$key] as $documentsDetail)
                       {
                           ?>
                               <p class="f14 ctgray displayBlock"><a target="_blank" href="/studyAbroadCommon/FileDownloader/downloadDocument/candidateDocumentForm?docId=<?php echo $documentsDetail['docId'];?>"> <?php echo $documentsDetail['documentName'];?> </a></p>
                           <?php
                       }
                       ?>
                       </div>
                   </div>
               <?php } ?>
	   	    </div>
	   	 <!----> 	
	   	  </div>
      <?php $count++;} ?>  
	   </div>
	 <!---->
</div>
<?php }else{?>
<div class="row">
  <div class="col-sm-12">
    <div class="x-panel" style="height: 300px;margin-bottom:10px;">
      <div class="container">
        <h2 style="position: absolute;top:100px;left:35%;">No records found</h2>
      </div>
    </div>
  </div>
</div>
<?php }?>