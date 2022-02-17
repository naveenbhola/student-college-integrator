<?php 
if($searchTupleType=='university'){
  $this->load->view("SASearch/searchV2UniversityTuple");
}else{ ?>
<script> var showCompareLayerPage = true; </script>
<?php
  $this->load->view("SASearch/searchV2CourseTuple");
}
?>