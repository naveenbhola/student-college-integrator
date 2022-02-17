<div class="newSearch">
    <div class="newSrpWrapper">
        <div class="ulpWrapper">
            <?php //$this->load->view('SASearch/breadcrumbs');?>
            <div class="getSpace">
                <?php 
                    if(isset($staticSearchUrl) && $staticSearchUrl == true){
                        $typeText = 'Universities in '.$locationName.' ('.$countryName.') – Courses, Fees & Admissions';
                        echo '<h1 class="srpHeading">';
                        echo $typeText;
                        echo '</h1>';                  
                    }else{  
                        $typeText = $pageData['totalResultCount'].' ';
                        $typeText .= ($pageData['totalResultCount']==1)?'result ':'results ';
                        $typeText .= 'for <strong>'.htmlentities($searchedTerm).'</strong>';                        
                        echo '<h2 class="srpHeading">';
                        echo $typeText;
                        echo '</h2>';                  
                    }
                    ?>                
            </div>
            <div id="srpUnivTuples" class="clearwidth srpMarginTop">
                <?php $this->load->view('SASearch/universityTuple');?>
            </div>
            <?php $this->load->view('SASearch/pagination');?>
        </div>
    </div>
</div>
</div>
