<style type="text/css">
	.row {width: inherit;}
	button.btn {margin-right: 10px;}
	.table > thead>tr> th{font-size: 16px;font-weight: 600;white-space: nowrap;}
	.table >tbody>tr> td,.fbFormBtn,.fbCSVDownload{font-size: 14px;}
	.fbCSVDownload {margin-top: 5px;}

</style>
<div class="row">
	    <div class="col-sm-12">
		    	<div class="form-group row">
		    		<div class="col-sm-12">
		    			<button type="button" class="btn btn-primary listMap" onclick="location.href='/enterprise/FBLeadResponseInterface/listingResponseMapping';">FB FORMS</button>

		    			<button type="button" class="btn btn-primary resMap" onclick="location.href='/enterprise/FBLeadResponseInterface/responseMappingForm';">CREATE FB LEAD MAPPING</button>

		    			<button type="button" class="btn btn-primary fbExptn" onclick="location.href='/enterprise/FBLeadResponseInterface/showFBExceptionList';">FB EXCEPTION</button>

		    			<button type="button" class="btn btn-primary cityMap" onclick="location.href='/enterprise/FBLeadResponseInterface/showFBMapCity';">CITY MAPPING</button>

		    		</div>
		    	</div>

		</div>
	</div>

<script type="text/javascript">	
	  $(<?php echo '".'.$class_name.'"'?>).css('background-color','#FC8104');
</script>