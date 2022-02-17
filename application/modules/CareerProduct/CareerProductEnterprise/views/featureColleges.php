<div>
    <?php $this->load->view('CareerProductEnterprise/subtabsCareers');?>
</div>

<script>var careerObj = new CareerEnterprise();</script>

<div>

        <div class="sectoion-title"   style="margin-top:75px">
            <h2 style="color:#cacaca" ><a>Set Featured Careers</a></h2>
        </div>

        <div class="featured-career-form">
        		<ul>
        			<li>
        				<label>Select</label>
        				<select id ="career" onchange="careerObj.getFeaturedCollegesData('career');" class="featured-select">
                            <option value=0>Career</option>
                            <?php for($i=0;$i<count($careerList);$i++):?>
                    <option value="<?php echo $careerList[$i]['careerId'];?>"><?php echo $careerList[$i]['name'];?></option>
                    <?php endfor;?>
        				</select>
        			</li>
        			<li>
        				<div class="sectoion-title">
							<h2>Client 1</h2>
						</div>
						<div>
                                <input id ="client1" maxlength="10" class="client-txtfield" value="Enter Client Id" default="Enter Client Id" onblur ="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')">                            
                                <input type="hidden" id="rowId1" />
								<input type="text" id="title1" maxlength="100" class="client-txtfield" value="Enter Institute Title" default="Enter Institute Title" onblur ="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')"/>
								<input type="text" id="url1" maxlength="200" class="client-txtfield" style="width:295px;" value="Enter Institute URL" default="Enter Institute URL" onblur ="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')"/>
								<a onclick="if((careerObj.validateFeaturedCollegeField('career','client1','title1','url1')) != false){careerObj.addFeaturedColleges('career','client1','title1','url1','rowId1', 1);}" class="orange-button" style="margin-right:10px;padding:7px 10px;">Set Featured</a>
								<a onclick="careerObj.deleteFeaturedColleges('1');" class="orange-button" style="padding:7px 10px;">Remove</a>
						</div>
        			</li>
        			<li>
        				<div class="sectoion-title">
							<h2>Client 2</h2>
						</div>
						<div>
                                <input id ="client2" maxlength="10" class="client-txtfield" value="Enter Client Id" default="Enter Client Id" onblur ="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')">
                                <input type="hidden" id="rowId2"/>
								<input type="text" id="title2" maxlength="100" class="client-txtfield" value="Enter Institute Title" default="Enter Institute Title" onblur ="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')"/>
								<input type="text" id="url2" maxlength="250" class="client-txtfield" style="width:295px;" value="Enter Institute URL" default="Enter Institute URL" onblur ="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')"/>
								<a onclick="if((careerObj.validateFeaturedCollegeField('career','client2','title2','url2')) != false){careerObj.addFeaturedColleges('career','client2','title2','url2','rowId2', 2);}" class="orange-button" style="margin-right:10px;padding:7px 10px;">Set Featured</a>
								<a onclick="careerObj.deleteFeaturedColleges('2')"; class="orange-button" style="padding:7px 10px;">Remove</a>
						</div>
        			</li>
        			<li>
        				<div class="sectoion-title">
							<h2>Client 3</h2>
						</div>
						<div>
                                <input id ="client3" maxlength="10" class="client-txtfield" value="Enter Client Id" default="Enter Client Id" onblur ="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')">
                                <input type="hidden" id="rowId3"/>
								<input type="text" id="title3" maxlength="100" class="client-txtfield" value="Enter Institute Title" default="Enter Institute Title" onblur ="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')"/>
								<input type="text" id="url3" maxlength="250" class="client-txtfield" style="width:295px;" value="Enter Institute URL" default="Enter Institute URL" onblur ="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')"/>
								<a onclick="if((careerObj.validateFeaturedCollegeField('career','client3','title3','url3')) != false){careerObj.addFeaturedColleges('career','client3','title3','url3','rowId3', 3);}" class="orange-button" style="margin-right:10px;padding:7px 10px;">Set Featured</a>
								<a onclick="careerObj.deleteFeaturedColleges('3')"; class="orange-button" style="padding:7px 10px;">Remove</a>
						</div>
        			</li>
        		</ul>
        </div>
</div>