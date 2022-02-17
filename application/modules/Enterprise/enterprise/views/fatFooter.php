<div class="FAT-footer-cms">
	<h2>Configure FAT Footer on Category Pages</h2>        	
	<div class="fat-footer-section">
		<h3>Select Criteria</h3>
		<div class="child-section">
			<label>Cateory Page:</label>
			<div class="fat-fields-cont">
				<ul>
					<li>
						<select id="maincategory" name="maincategory" size="1" onclick="$j('#LoadDiv').hide();">
							<option value="">Select a Category</option>
							<?php
								foreach($catTree as $category){
									if($category['parentId'] == 1){
										echo '<option value="'.$category['categoryID'].'">'.$category['categoryName'].'</option>';
									}
								}
							?>
						</select>
						<span id="c_categories_combo" onclick="$j('#LoadDiv').hide();">
						
						</span>
						<script>
						var completeCategoryTree = eval(<?php echo $categoryList; ?>);
						getCategories(true,"c_categories_combo","subcategory","subcategory",false,false);
						</script>
						<select id="ldbcourse" name="ldbcourse" size="1" onclick="$j('#LoadDiv').hide();">
							<option value="">Select a Course</option>
							<?php
								foreach($catTree as $category){
									if($category['parentId'] > 1){
										echo '<optgroup label="'.$category['categoryName'].'" title="'.$category['categoryName'].'">';
										//echo '<option> lable="'.$category['categoryName'].'"</option>';
										foreach($courseList[$category['categoryID']] as $course){
											echo '<option value="'.$course['SpecializationId'].'">'.$course['CourseName'].'</option>';
										}
										echo '</optgroup>';
									}
								}
								//echo "<pre>".print_r($courseList,true)."</pre>";
							?>
						</select>
					</li>
					<li><div style="margin:5px 0">
					<div id="errortext" class="errorMsg">
					</div></li>
					<li>AND</li>
					
					<li>
						<input type="radio" name="locationFlag" checked="checked" value="0" /> All City &nbsp;&nbsp;&nbsp;
						<input type="radio" name="locationFlag" value="1" /> Location Pages
					</li>
				</ul>
				<div class="spacer10 clearFix"></div>
				<input type="button" value="Submit" class="orange-button" onclick="loadFooterDiv();" />
			</div>
		</div>
	</div>
	<div id="LoadDiv" style="display:none">
		
	</div>
	<div class="button-sec" style="visibility:hidden"><input type="button" value="Add Coloumns" class="gray-button" /></div>
	
</div>

<script>
	function addField(col,text,url){
		text = text||'';
		url = url||'';
		if(currentFields[col-1] > 9){
			return false;
		}
		$j('#add-field'+col).before('<li class="anchor-text'+col+'">\
							<div class="column-fields">\
								<label>Anchor Text: </label>\
								<input type="text" class="universal-txt-field" value="" id="anchor-text'+col+'_'+(currentFields[col-1]+1)+'" />\
							</div>\
							<div class="column-fields" style="width:400px" >\
								<label>URL:</label>\
								<input type="text" class="universal-txt-field" value="" id="anchor-url'+col+'_'+(currentFields[col-1]+1)+'"  />\
							</div>\
						</li>');
		$j('#anchor-text'+col+'_'+(currentFields[col-1]+1)+'').val(text);
		$j('#anchor-url'+col+'_'+(currentFields[col-1]+1)+'').val(url);
		currentFields[col-1]++;
		if(currentFields[col-1] > 9){
			$j('#add-field'+col).hide();
		}
	}
	function loadFooterDiv(){
		$j('#LoadDiv').hide();
		var checkArray = [];
		$('errortext').innerHTML = '';
		if($('ldbcourse').value){
			checkArray.push($('ldbcourse').value);
			entityType = 'course';
		}
		if($('maincategory').value){
			checkArray.push($('maincategory').value);
			entityType = 'category';
		}
		if($('subcategory').value){
			checkArray.push($('subcategory').value);
			entityType = 'subcat';
		}
		if(checkArray.length != 1){
			$('errortext').innerHTML = "Select one out of Category, Sub-Category or LDB Course";
			return false;
		}
		currentFields = new Array(0,0,0,0,0);
		locationFlag = $j('input[name=locationFlag]:radio:checked').val();
		entityId  = checkArray[0];
		$j.ajax({
                async: false,
                url: '/enterprise/Enterprise/getCatgoryPageFatFooter/'+entityType+'/'+entityId+'/'+locationFlag,
                type: 'POST',
                success: function(res) {
					$j('#LoadDiv').html(res).show();
                    for(var i=0;i<5;i++){
						if(footerFields != '' && footerFields[i]){
							$j('input:radio[name="localityPref'+(i+1)+'"]').filter('[value="'+footerFields[i]['localityPref']+'"]').attr('checked', true);
							$j('input:radio[name="replacecity'+(i+1)+'"]').filter('[value="'+footerFields[i]['replacecity']+'"]').attr('checked', true);
							$j.each(footerFields[i]['fields'],function(index,element){
								if(element['text'] || element['url']){
									addField(i+1,element['text'],element['url']);
								}
							});
							addField(i+1);
							$j('#anchor-text-'+(i+1)+'').val(footerFields[i]['localityAnchor']);
							setColumnAsLocality((i+1));
						}else{
							addField(i+1);
							$j('input:radio[name="localityPref'+(i+1)+'"]').filter('[value="No"]').attr('checked', true);
							$j('input:radio[name="replacecity'+(i+1)+'"]').filter('[value="No"]').attr('checked', true);
						}
						if(locationFlag == 0){
							$j('.localityPref'+(i+1)+'').hide();
							$j('.replaceCity'+(i+1)+'').hide();
							$j('#localityAnchor'+(i+1)+'').hide();
						}
					}
                }
		});
		
	}
	
	function setColumnAsLocality(col){
		if(locationFlag != 0){
			var localityPref  = $j('input[name="localityPref'+col+'"]:radio:checked').val();
			if(localityPref == "Yes"){
				$j('.replaceCity'+(col)).hide();
				$j('#localityAnchor'+(col)).show();
				$j('#add-field'+(col)).hide();
				$j('.anchor-text'+(col)).hide();
			}else{
				$j('.replaceCity'+(col)).show();
				$j('#localityAnchor'+(col)).hide();
				if(currentFields[col-1] <= 9){
					$j('#add-field'+(col)).show();
				}
				$j('.anchor-text'+(col)).show();
			}
		}
		
	}
	
	function saveFooter(){
		var footerFields = {};
		for(var i=0;i<5;i++){
			footerFields[i] = {};
			footerFields[i]['localityPref'] = $j("[name='localityPref"+(i+1)+"']:checked").val();
			footerFields[i]['replacecity'] = $j("[name='replacecity"+(i+1)+"']:checked").val();
			footerFields[i]['localityAnchor'] = $j("#anchor-text-"+(i+1)).val();
			footerFields[i]['fields'] = {}; 
			for(var j=1;j<=10;j++){
				footerFields[i]['fields'][j-1] = {};
				footerFields[i]['fields'][j-1]['text'] = $j('#anchor-text'+(i+1)+'_'+j).val();
				footerFields[i]['fields'][j-1]['url'] = $j('#anchor-url'+(i+1)+'_'+j).val();		
			}
		}
		//alert(JSON.stringify(footerFields));
		fatFooter  = url_base64_encode(JSON.stringify(footerFields));
		$j.ajax({
                async: false,
                url: '/enterprise/Enterprise/setCatgoryPageFatFooter/'+entityType+'/'+entityId+'/'+locationFlag,
                type: 'POST',
				data:{
					'fatFooter' : fatFooter
				},
                success: function(res) {
					alert("Fat Footer is set.");
                }
		});
		
	}

</script>