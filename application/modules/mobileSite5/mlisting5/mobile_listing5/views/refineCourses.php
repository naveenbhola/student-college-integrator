 <?php $courses=$institute->getCourses();
   $i=0;
   foreach($courses as $course){
      if($course->getCourseLevel()!='')
        $courseLevel[$course->getCourseLevel()]=$course->getCourseLevel();
    }
?>
             
 <?php if(((count($courseLevel)>=2) || count($category['categoryInfoArray'])>=2)&& count($courses)>=4) {?> 
  <header class="refine-box">
                <ul>
                <?php if(count($courseLevel)>=2) {$flag='true';?>
                <li class="pr5">
                        <p>Refine by</p>
                    <select id="level">
                        <option value="Level"> Level</option>
                         <?php foreach ($courseLevel as $levelOfcourse){ ?>
                            <option value="<?php echo $levelOfcourse; ?>">
                          <?php echo $levelOfcourse; ?>
                            </option>
                            <?php } ?>
                    </select>
                </li>
                <?php } if(count($category['categoryInfoArray'])>=2){?>
              <?php if($flag=='true'){ ?> <li> <?php } ?>
                <?php if($flag=='false'){ ?> <li class="pr5"> <p>Refine by </p> <?php }?>
                    <select id="category">
                        <option value="Category"> Category </option>
                        <?php foreach($category['categoryInfoArray'] as $key=>$categoryObj){ ?>
                            <option value="<?php echo $categoryObj->getId(); ?>">
                          <?php echo $categoryObj->getName();?>
                            </option>
                            <?php } ?>
                    </select>
                </li>
                <?php }?>
            </ul>

 </header>
  <?php }?>


<script>

level='false';
category='false';


//called on changing the level.. 
$("#level").on("change", function() {
   
    $("#noRes").hide(); //hide the no result block..
    
    if($(this).val()=='Level'){   //if the user chooses to show all the levels
        level='false';
        if(category=='false'){    // if no category has been chosen..
          $("dt[id*='level_']").show();
          $("dd[id*='level_']").show();
          checkIfAllHidden('0');
        }
        else{                   // if category has been chosen already..
           ids_to_show3="catId_"+catId+"_";
           $("dt[id*='"+ids_to_show3+"']").show();
           $("dd[id*='"+ids_to_show3+"']").show();
            checkIfAllHidden($("dt[id*='"+ids_to_show3+"']"));
        }
       
         return;
    }
    
    var ids_to_hide = "level_";
    
    if(category=='false'){  // if user chooses some level and no category has been chosen yet..
       ids_to_show1 = "level_" + $(this).val();
       levelId=$(this).val();
       level='true';
    }
    else{    // if user chooses some level and category has been already chosen..
       ids_to_show1 = "level_" + $(this).val()+"_catId_"+catId;
       levelId=$(this).val();
       level='true';
    }
    /// to hide and show respective elements..
    
    $("dt[id*='"+ids_to_hide+"']").hide();
    $("dt[id*='"+ids_to_show1+"']").show();
    $("dd[id*='"+ids_to_hide+"']").hide();
    $("dd[id*='"+ids_to_show1+"']").show();
    checkIfAllHidden($("dt[id*='"+ids_to_show1+"']"));
    
});

$("#category").on("change", function() {

     $("#noRes").hide();
     
     if($(this).val()=='Category'){
        category='false';
      
      if(level=='false'){ 
          $("dt[id*='level_']").show();
          $("dd[id*='level_']").show();
          checkIfAllHidden('0');
      }
      else{
          ids_to_show4="level_"+levelId+"_";
          $("dt[id*='"+ids_to_show4+"']").show();
          $("dd[id*='"+ids_to_show4+"']").show();
          checkIfAllHidden($("dt[id*='"+ids_to_show4+"']"));
      }
      return;
    }
    var ids_to_hide = "catId_";
    if(level=='false'){
        ids_to_show2 = "catId_"+$(this).val()+"_";
        catId=$(this).val();
        category='true';
    }
    else{
        ids_to_show2 = "level_"+levelId+"_catId_" + $(this).val()+"_";
        catId=$(this).val();
        category='true';
    }
    
    $("dt[id*='"+ids_to_hide+"']").hide();
    $("dd[id*='"+ids_to_hide+"']").hide();
    $("dt[id*='"+ids_to_show2+"']").show();
    $("dd[id*='"+ids_to_show2+"']").show();
    checkIfAllHidden($("dt[id*='"+ids_to_show2+"']"));
});
 
function checkIfAllHidden(ids_to_show){
  
    if ( $("dt[id^='level_']:visible").length == 0){
       $("#noRes").show();
       return false;
    }
  
  
  if(ids_to_show!='' || ids_to_show!='undefined'){
    if(ids_to_show=='0'){
      setAccordion(ids_to_show);
      return ;
    }
    $(ids_to_show).each(function( index ){
      idToShowAfterRefining=($(this).attr("id").split("_")[5] );
      setAccordion(idToShowAfterRefining);
      return false;
    });  
  }
 
  
}
</script>

