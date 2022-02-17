<script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('imageUpload'); ?>"></script>
<div class="abroad-cms-rt-box">
    <div class="abroad-cms-head" style="margin-top:0;">
	    <h2 class="abroad-sub-title">All Country home pages</h2>
    </div>
    <div class="search-section">
        <div class="adv-search-sec clear-width" style="box-sizing:border-box;border-bottom: 1px solid #ccc;">
		  <div class="cms-adv-box" style="position: absolute; left: 615px; margin-top:11px; z-index: 99999;">
          <a href="Javascript:void(0);" onclick = "searchDatatable();" class="orange-btn" style="padding:4px 10px;">Go</a>
          </div>

            <div class="clearwidth"></div>

			<table id="countryHomeListTable" class="dataTableContainer cms-table-structure" border="1" cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <th width="5%" align="center">S.No.</th>
		    	        <th width="50%">
		    				<span class="flLt">Country Name</span>
		    	        </th>
		    	        <th width="25%">
		    		        <span class="flLt">Updated by</span>
		    	        </th>
		    	        <th width="25%">
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
			var tableName = '<?=(ENT_SA_VIEW_COUNTRYHOME_WIDGETS)?>';
</script>
<style>
table.dataTable {
  border-collapse: collapse;
}
.dataTables_wrapper .dataTables_length {
  float: none;position: absolute;right: 0;top: 14px;
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
</style>