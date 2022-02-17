
<div class="fltr_sec">
	<div class="fltr_bx">
		<div class="flt_dv">
			<ul class="">
				<li><label>Filter List By:</label></li>
				<li class="exm-bloc2">
                    <label class="col_labl">College</label>
                    <div class="flt_slt fnt__sb wd-inht">
                        <p class="flt-dv cat_lyr"><span><?=$instituteName;?></span></p>
                        <div class="flt-lyr flt-cstm-lyr fnt__n hid">
                            <ul>
                                <?php foreach ($filterData['collegeFilters'] as $instId => $instituteDetail) { ?>
                                        <li <?php if($instituteDetail['id']==$instituteId) { echo 'class =disb'; }?> ><a href="<?=$instituteDetail['url'];?>"><p><?=$instituteDetail['name']; ?></p></a></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </li>
				
				<li class="exm-bloc4">
					<label class="col_labl">Category</label>
					<div class="flt_slt fnt__sb wd-inht">
						<p class="flt-dv cat_lyr"><span><?php echo $categoryName; ?></span></p>
						<div class="flt-lyr flt-cstm-lyr fnt__n hid">
							<ul>
								<?php foreach ($filterData['categoryFilters'] as $cat=>$url) { ?> 
									  
									<li <?php if(strtolower($cat)==strtolower($categoryName)) { echo 'class ="disb" ';}?> ><a href="<?php echo $url;?>"><?php echo $cat;?></a></li>
					            <?php }
					             ?>
							</ul>
						</div>
					</div>
				</li>

				<?php if(count($filterData['specializationFilters'])>0){?>
					<li class="exm-bloc4">
						<label class="col_labl">Specialization</label>
						<div class="flt_slt fnt__sb wd-inht">
							<?php $specDetails = $filterData['specializationFilters'][$specializationId];?>
							<p class="flt-dv cat_lyr"><span><?php echo empty($specDetails)?'All':$specDetails['name']; ?></span></p>
							<div class="flt-lyr flt-cstm-lyr fnt__n hid">
								<ul>
								<li <?php if(!($specializationId>0)){ echo 'class="disb" ';}else{echo 'class="spFlt"';} ?> ><a href="javascript:void(0)"><?php echo 'All';?></a></li>
									<?php foreach ($filterData['specializationFilters'] as $value) { ?> 
										<li <?php if($value['specialization_id']==$specializationId) { echo 'class="disb" ';}else{echo 'class="spFlt"';} echo "sp_id=".$value['specialization_id']?> ><a href="javascript:void(0)"><?php echo $value['name'];?></a></li>
						            <?php }
						             ?>
								</ul>
							</div>
						</div>
					</li>
				<?php } ?>
				</li>
				<li class = 'cutOff-blc'>
                    <label class="col_labl">Cut-Off</label>
                    <div class="flt_slt fnt__sb wd-inht">
                        <p class="flt-dv cat_lyr"><span><?php echo ($cutoffRange==0)? $filterData['cutoffFiltersCompleteList'][0]: $filterData['cutoffFiltersCompleteList'][$cutoffRange][0].'%-'.$filterData['cutoffFiltersCompleteList'][$cutoffRange][1].'%';?></span></p>
                        <div class="flt-lyr flt-cstm-lyr fnt__n hid">
                        	<ul>
                        		<?php foreach ($filterData['cutoffFiltersCompleteList'] as $key => $value) {
                        			if($filterData['bucketToShow'][$key]){
                        		 ?> 
                        			<li <?php if($key==$cutoffRange) { echo 'class =disb';}?> ><a href="javascript:void(0)" class='filterCutoff' value = <?= $key; ?>><?php echo ($key ==0) ? $value: $value[0].'%-'.$value[1].'%';?></a></li>
                                <?php }
                            	}
                                ?>
                      		</ul>
                        </div>
                    </div>
                </li>
			</ul>
		</div>
	</div>
</div>

