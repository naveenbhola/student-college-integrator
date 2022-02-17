<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('imageUpload'); ?>"></script>
<div class="abroad-cms-rt-box">
    <div class="abroad-cms-head" style="margin-top:0;">
	    <h2 class="abroad-sub-title">All Content Navbars</h2>
        <div class="flRt"><a href="/contentNavbarPosting/ContentNavbarPosting/addEditContentNavbarLinks" class="orange-btn" style="padding:6px 7px 8px">+ Add New Content Navbar</a></div>
    </div>
    <div class="search-section">
        <div class="adv-search-sec clear-width" style="box-sizing:border-box;border-bottom: 1px solid #ccc;">
            <div class="clearwidth"></div>
			<table id="contentNavbarTable" class="cms-table-structure" border="1" cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <th width="5%" align="center">S.No.</th>
		    	        <th width="40%">
		    				<span class="flLt">Navbar title</span>
		    	        </th>
		    	        <th width="25%">
		    				<span class="flLt">Content type</span>
		    	        </th>
		    	        <th width="35%">
		    		        <span class="flLt">Last updated</span>
		    	        </th>
		    	    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        
    </div>
</div>
<script>
    var tableName = '<?php echo (ENT_SA_CONTENT_NAVBARS); ?>';
</script>
<style>
table.dataTable {
  border-collapse: collapse;
}
.dataTables_wrapper .dataTables_length {
  float: none;position: absolute;right: 0;
}
.dataTables_wrapper .dataTables_filter {
  float: none;  text-align: left;
}
.dataTables_wrapper .dataTables_filter input {
  /*margin-left: 0.5em;*/
  border: medium none;
  color: #ccc;
  float: left;
  height: 25px;
  margin: 0;
  outline: none;
  width:225px;
}
.dataTables_wrapper .dataTables_info {
}
.dataTables_wrapper .dataTables_paginate {
  background: #eaeaea none repeat scroll 0 0;  margin-top: 10px;  padding:4px;
}
.dataTables_wrapper .dataTables_paginate .paginate_button {
  padding: 2px 6px;
}
.dataTables_wrapper .dataTables_paginate .paginate_button.current, .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
  color: #fff !important;
  background: #999 none repeat scroll 0 0;  
}
table.dataTable tbody th, table.dataTable tbody td
{
  padding: 5px;
}
.dataTables_wrapper .dataTables_info {
  float: right;position: absolute;right: 150px;top: 10px;
}
.dataTables_info {
  top: -4px !important;
}
</style>
