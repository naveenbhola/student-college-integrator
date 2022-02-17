<div class="clpwrapper">
  <div class="clp-response">
     <div class="clptable-block">

        <?php if(!empty($institutes)) { ?>

          <div class="clpinstitue-txt">
           <h2 class="clp-instname"><?php echo $userDetails['firstname'].' '.$userDetails['lastname']; ?> - <span>Client Id: <?php echo $clientId;?></span></h2>
           <p class="clp-selct">Please select one UILP listing shown below</p>
          </div>
          
          <table class="hover-table">
           <tr>
             <th>UILP ID</th>
             <th>Institute/University Name</th>
           </tr>
           <?php 
           foreach($institutes as $institute) { ?>
           <tr>
             <td><p onclick="getCourseFromUniversityInstitute('<?php echo $institute['listing_type_id'];?>','<?php echo $institute['listing_type'];?>')"><?php echo $institute['listing_type_id'];?></p></td>
             <td><p onclick="getCourseFromUniversityInstitute('<?php echo $institute['listing_type_id'];?>','<?php echo $institute['listing_type'];?>')"><strong><?php echo $institute['listing_title'];?></strong></p></td>
           </tr>
          <?php } ?>
          </table>

        <?php }  else  { ?>

          <div class="clpinstitue-txt" style="text-align:center">
           <h2 class="clp-instname"><span>No Data Exist for Client Id: <?php echo $clientId;?></span></h2>
          </div>

        <?php } ?>

        <div class="submit-col">
          <div class="btn-flexbox">
           <button type="button" name="button" class="pwa-btns pwa-secondary" onclick="showPage('1');">Back</button>
          </div>
        </div>

     </div>

  </div>
</div>
