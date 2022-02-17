<?php  if($paginator->isPaginationPossible()) {
    if($pendingTables === true){
        $class= "flRt";
    }else{
        $class="flLt";
    }
    ?>
<script>
function changeResultPerPage(){
location.assign(document.getElementById('selectResultPerPage').value);
}
</script>
   <div class="display-field <?php echo $class; ?>">
   <label>Display : </label>
   <select id="selectResultPerPage" class="universal-select display-select" onChange = "changeResultPerPage();">
   <?php $paginator->generateMenuForResultPerPage();?>
   </select>
   </div>
<?php }?>                    