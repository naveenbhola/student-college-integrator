<?php global $queryStringFieldNameToAliasMapping; 
            $appliedFilters = $request->getAppliedFilters();
            $appliedDestCountries = $appliedFilters['saScholarshipCountryId'];
?>
<li>
   <input type="checkbox" class="main__check">
   <i class="custm__ico"></i>
   <h3 class="main__titl f12-clr3 fnt-sbold">Destination country</h3>
   <div id="ctryScroll" class="slct__country">
            <ul class="sub__menu">
               <?php foreach($scholarshipData['filterData']['saScholarshipCountryId'] as $countryId => $ctryData){ ?>
                  <li>
                    <?php 
                        unset($scholarshipData['filterData']['saScholarshipCountryId_parent'][$countryId]);
                    ?>
                    <a class="chek__box">
                          <div class="">
                          <input type="checkbox" name="destinationCountry[]" id="dest_<?php echo $countryId; ?>" class="inputChk" value="<?php echo $countryId; ?>" alias="<?php echo $queryStringFieldNameToAliasMapping['destinationCountry'];?>" <?php echo (in_array($countryId,$appliedDestCountries)?'checked':''); ?>/>
                           <label class="f12-clr3 check__opt" for="dest_<?php echo $countryId; ?>">
                                 <i></i>
                                 <?php echo $ctryData['name']." (".$ctryData['count'].")"; ?>
                           </label>
                          </div>
                    </a>
                  </li>
                  <?php } ?>
               <?php foreach($scholarshipData['filterData']['saScholarshipCountryId_parent'] as $countryId => $ctryData){ ?>
                  <li>                    
                    <a class="chek__box">
                          <div class="">
                          <input type="checkbox" disabled name="destinationCountry[]" id="dest_<?php echo $countryId; ?>" class="inputChk" value="<?php echo $countryId; ?>" alias="<?php echo $queryStringFieldNameToAliasMapping['destinationCountry'];?>" <?php echo (in_array($countryId,$appliedDestCountries)?'checked':''); ?>/>
                           <label class="f12-clr3 check__opt" for="dest_<?php echo $countryId; ?>">
                                 <i></i>
                                 <?php echo $ctryData['name']." (0)"; ?>
                           </label>
                          </div>
                    </a>
                  </li>
                  <?php } ?>
            </ul>
   </div>
</li>