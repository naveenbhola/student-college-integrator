<h1 class="abroad-title addMainExamLink">Showing All CHPs</h1>  
     <div class="flRt">
         <a href="/chp/ChpCMS/addCHP" class="orange-btn addMainExamToggle addMainExamLink" style="padding:6px 7px 8px">+ Add CHP</a>
	 <a href="/chp/ChpCMS/manageSeoCHP" class="orange-btn addMainExamToggle addMainExamLink" style="padding:6px 7px 8px"> Manage SEO </a>
     </div>    
   </div>
        <div class="cms-form-wrapper clear-width">
          <!--manage chp's data-->
	
	  <div style="display: block;margin:20px 0px 10px;width: 100%;position: relative;" class="_container_">
  <!--<select>
    <option value="">select here</option>
  </select>
  <button type="button" id="filterStreams">Filter</button>-->

  <div style="margin-bottom: 20px;position: relative;display: inline-block;width: 40%;" class="_container_">
<select onChange="checkFilter(this);" style="height:35px;">
	<option value="">Select Filter</option>
	<option value="chpName" >CHP Name</option>
	<option value="summary">Is Overview blank ?</option>
	<option value="eligibilty">Is Eligibilty blank ?</option>
  <option value="publishDate">Date of Publish</option>
</select>
<div id="chpName" style="display:none;margin-left:0px;" class="filter">
<div style="
    display: inline-block;
    margin-left: 20px;
    width: 100%;
    position: relative;
"><input type="text" name="chpField" id="chpField" class="input-txt" onkeyup="myFunction('chpField','1')" placeholder="Search By CHP Name" minlength="1" maxlength="50" caption="chp" validate="validateStr" autocomplete="off" style="width:100%;box-sizing: border-box;">
</div>
</div>

<div id="publishDate" style="display:none;margin-left:0px;" class="filter">
<div style="
    display: inline-block;
    margin-left: 20px;
    width: 100%;
    position: relative;
"><input type="text" name="date" id="date" class="input-txt" onkeyup="myFunction('date','3')" placeholder="Search by DD-MM-YYYY" minlength="1" maxlength="50" caption="chp" validate="validateStr" autocomplete="off" style="width:100%;box-sizing: border-box;">
</div>
</div>

<div id="summary" style="display:none;float:right;width:215px"  class="filter">
<select onChange="myFunction('summaryField','4')" id="summaryField" style="height:35px;">
<option value="">Select</option>
<option value="yes">Yes</option>
<option value="no">No</option>
</select>
</div>
<div id="eligibilty" style="display:none;float:right;width:215px"  class="filter">
<select onChange="myFunction('eligibiltyField','6')" id="eligibiltyField" style="height:35px;">
<option value="">Select</option>
<option value="yes">Yes</option>
<option value="no">No</option>
</select>
</div>
</div>

<div style="float: right;display: none;" id="chpReset"><a href="/chp/ChpCMS/viewList" class="orange-btn" style="padding:6px 7px 8px">Reset</a></div>

<div class="search-section _container_" id="examList">
      <table class="cms-table-structure" border="1" cellspacing="0" cellpadding="0" id="myTable">
            		<tr>
            			<th>S.No.</th>
            			<th>CHP Name</th>
	                        <th>CHP Display Name</th>
            			<th>Date of Publish</th>
            			<th>Is Overview Blank?</th>
				<th>Latest Date of Overview Update</th>
				<th>Is Eligibilty Blank?</th>
				<th>Latest Date of Eligibilty Update</th>
				<th>Action</th>
            		</tr>

<?php foreach($list as $key=>$value){?>
<tr>
                                <td><?php echo ($key+1);?></td>
                                <td><?php echo $value['name'];?></td>
                                <td><?php echo $value['display_name'];?></td>
                                <td><?php echo date('d-m-Y',strtotime($value['created_on']));?></td>
                                <td><?php echo ($value['summary_status']) ? 'No' : 'Yes';?></td>
                                <td><?php echo ($value['summary_updated_on']) ? date('d-m-Y',strtotime($value['summary_updated_on'])) : '- -' ;?></td>
                                <td><?php echo ($value['eligibilty_status']) ? 'No' : 'Yes';?></td>
                                <td><?php echo ($value['eligibilty_updated_on']) ? date('d-m-Y',strtotime($value['eligibilty_updated_on'])) : '- -';?></td>
                                <td><a href="/chp/ChpCMS/editCHP/<?php echo $value['chp_id'];?>">Edit</a></td>
                        </tr>
<?php }?>

           </table> 		

</div>


        </div>
<script>
function myFunction(id, index) {
  var input, filter, table, tr, td, i;
  input = document.getElementById(id);
  filter = input.value.trim().toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[index];
    if (td) {
      if (td.innerText.trim().toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}

function checkFilter(ref){
  if(ref.value){
    $j('#chpReset').show();
  }else{
    $j('#chpReset').hide();
  }
  var elements = document.getElementsByClassName("filter");
  for (var i = 0; i < elements.length; i++){
  	elements[i].style.display = 'none';
  }
  document.getElementById('chpField').value='';
  myFunction('chpField', 1);
  document.getElementById('summaryField').value='';
  myFunction('summaryField', 4);
  document.getElementById('eligibiltyField').value='';
  myFunction('eligibiltyField', 6);
  if(ref.value){
    document.getElementById(ref.value).style.display='';  
    document.getElementById(ref.value).style.display='';
  }
  myFunction('date', 3);
}
</script>
