<?php $this->load->view('enterprise/subtabsStudyAbroadWidgets');?>
<div id="mainWrapper">
	<!-- Left Column Starts-->
    <div class="SA-cms-cont">
        <div class="choseStudyLocation">
        	<form>
            	<ul>
                	<li>
						<label>Select Region:</label>
                        <div>
							<select name="regions" id="regions">
								<option value="">Select a Region</option>
								<?php
									foreach($regions as $key=>$region){
										echo "<option value='".$key."'>".$region."</option>";
									}
								?>
							</select>
						</div>
                   </li>
                    <li>
						<label>&nbsp;</label>
                        <div>
							OR
                        </div>
                   </li>
                   <li>
						<label>Select Country:</label>
                        <div>
                        		<select name="country1" id="country1">
								<option value="">Select a Country</option>
								<?php
									foreach($countries as $key=>$country){
										echo "<option value='".$key."'>".$country."</option>";
									}
								?>
							</select>
                        </div>
                   </li>
                   
                   <li>
						<label>Select Category:</label>
                        <div>
                        	 <select id="maincategory" name="maincategory" size="1">
                                <option value="">Select Category</option>
                                <?php
                                        foreach($catTree as $category){
                                                if($category['parentId'] == 1){
                                                        echo '<option value="'.$category['categoryID'].'">'.$category['categoryName'].'</option>';
                                                }
                                        }
                                ?>
							</select>
                        	<input type="button" title="Go" value="Go" class="saveButton" onclick="getSAWidgetArticles('top');" />
                        </div>
                   </li>
                </ul>
            </form>
        </div>
        
        <div class="examSections">
        	<ul>
            	<li class="lastRow" id="articleList">

                </li>
            </ul>
        </div>

        </div>
        
    	<div class="clearFix"></div>
    </div>
    <div class="clearFix spacer5"></div>
</div>
