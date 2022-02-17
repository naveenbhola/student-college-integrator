<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Exam Guide html PDF</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css' />
    <link rel="stylesheet" type="text/css" href="https://<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion('examGuideCss'); ?>" />    
    
</head>

<body>
    <div class="guide-container">
       
        <h1><?php if($groupYear){
            $groupYear = ' '.$groupYear;
           }if($examFullName){ echo $examFullName.' ('.$examName.')'.$groupYear;}else{
            echo $examName.$groupYear;
            }?>
            <a class="dl-nw" target="_blank" href="<?=$examPageUrl?>">Visit on Shiksha</a>

        </h1> 
        <div class="clg-det">
            <?php if(count($groupList)>1){?>
            <div class="clg-bx">           
                <p>Conducted for <span><?php echo count($groupList)?> Courses</span><br/> Showing details for 
            <span><?php echo $groupName;?></span></p>
            </div>
             <?php }?>
             <?php
                if($conductedBy['name'] || $conductedBy){?>
            <div class="clg-bx">
                <p>Conducted by<br/>                 
                    <span>
                        <?php if(is_array($conductedBy)){ echo $conductedBy['name']; }else{ echo htmlentities($conductedBy);}?>
                    </span>
                </p>
            </div>
            <?php } ?>
            <?php if(!empty($applyOnlineData['of_creationDate'])){?>
            <div class="clg-bx">
                <p>Last Date to Apply <span><?=$applyOnlineData['of_creationDate']?></span></p>
            </div>
            <?php } ?>
        </div>
        <div>
             <?php
                    foreach ($examContent['homepage'] as $key => $curObj) { 
                      if(in_array($curObj->getEntityType(), $noSnippetSections)){
                      continue;
                      }
                      $homepageSection['data'] = $curObj->getEntityValue();
                      $homepageSection['sectionName'] = $curObj->getEntityType();
                      $homepageSection['section'] = ($homepageSection['sectionName'] == 'Summary')?'homepage':'';
                      $this->load->view('examPages/newExam/guideWidgets/summary', $homepageSection); 
                    }
                      foreach ($examContent['sectionname'] as $section) { 
                        $data['section'] = $section;
                        if(array_key_exists($section, $wikiFields) && !empty($examContent[$section])){
                          $wikiData['sectionData'] = $examContent[$section];
                          $wikiData['sectionName'] = $wikiFields[$section];
                          $wikiData['section'] = $section;
                          $this->load->view('examPages/newExam/guideWidgets/wikiFieldsView', $wikiData); //common view for all wiki fields  
                        }else {
                          switch($section){
                              case 'importantdates':
                                if(!empty($importantDatesData['dates']) || !empty($examContent['importantdates']['wiki']))
                                {
                                  $this->load->view('examPages/newExam/guideWidgets/importantDates',$data);
                                }
                                //important Dates
                            break;
                              case 'results':
                                if(!empty($examContent['results']['wiki']) || !empty($resultData))
                                {
                                  $this->load->view('examPages/newExam/guideWidgets/result',$data);  
                                }
                            break;
                              case 'applicationform':
                                if(!empty($appFormData['appFormWiki']) || !empty($appFormData['formURL']) || !empty($appFormData['fileUrl'])) {
                                  $this->load->view('examPages/newExam/guideWidgets/applicationForm',$data);
                                }
                            break;
                              case 'samplepapers':
                              if((!empty($samplePaperData) && count($samplePaperData) > 0) || (!empty($guidePaperData) && count($guidePaperData) > 0) || !empty($examContent['samplepapers']['wiki'])) {
                                  $this->load->view('examPages/newExam/guideWidgets/samplePaper',$data);
                                }
                            break;
                          }
                        }
                      }
       ?>
        </div>
    </div>
</body>
</html>
