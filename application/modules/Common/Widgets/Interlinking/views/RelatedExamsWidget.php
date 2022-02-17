<?php 

if(!empty($recommendedExams['data'])){ ?>
  <div class="slider-sections" id="exmsldrSec">
    <div class="ana-container">
      <div class="row p-rel">
      <h2 class="head-1"><?=$recommendedExams['widgetHeading']?></h2>

        <div class="slide-show pos-rel exm-shwLst ExamsSlider">
             <a class="icn-prev lftArwDsbl"><i></i></a>

         <div class="r-caraousal">
           <ul class="ana-slider-col exm-sldr">
             <?php foreach($recommendedExams['data'] as $key=>$data){ 
		$year = ($data['year']!='')?' '.$data['year']:'';
		$displayTitle = $data['exam_name'].$year;
		?>
               <li >
                <div class="data-add">
                 <div class="clg-infrm-data">
                   <a href="<?=$data['exam_url']?>" class="title"><?php echo htmlentities(substr($displayTitle,0,14))?><?php if(strlen(htmlentities($displayTitle))>14){echo '...'; } ?></a>
                   <p class="exam_name"><?php echo htmlentities(substr($data['full_name'],0,56))?><?php if(strlen(htmlentities($data['full_name']))>56){echo '...'; } ?></p>
                   <a class="cousre-data-inf" href="<?=$data['exam_url']?>" ga-page="<?php echo $GA_PageType.'_Similar_Exams';?>" ga-attr="Similar_Exams" ga-optlabel="<?php echo $GA_Device.'_Similar_Exams'; ?>">View Exam Details</a>
                 </div>
               </div>
               <?php } ?>
             </li>

           </ul>
         </div>
           <a class="icn-next"><i></i></a>
       </div>
     </div>
   </div>
 </div>
 <?php } ?>
