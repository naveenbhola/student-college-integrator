<!-- home page main container html -->
<div id="homepageMiddlePanel" class="main_content reset" >
	<div class="container">
		<div class="n-row">
			<div class="col-lg-5 pL0">
				<div class="home_Nav01">
					<h1>Study in India</h1>
					<div class="nav01_L1">
						<ul class="nav01_L2">
							<?php
							$this->load->config("home/homeConfig");
							$subCatStrings = $this->config->item('subCatStrings');
							foreach($tabsContentByCategory as $catId=>$info)
							{
							?>
							<li>
							<a href="javascript:void(0);">
							<?php
							$info['name'] = ($info['name']=='Animation, Visual Effects, Gaming & Comics (AVGC)')?'Animation, VFX, Gaming & Comics':$info['name'];
							?>
							<p><?=$info['name']?></p>
							<span><?=implode('<i></i>',$subCatStrings[$catId])?></span>
							</a>
							<div class="nav01_L3">
								<ul>
								<?php
								foreach($info['subcats'] as $id => $subCatArr)
								{
								?>
									<li><a href="<?=$subCatArr['url']?>" title="<?=$subCatArr['name']?>"><?=$subCatArr['name']?></a></li>
								<?php
								}
								?>
								</ul>
							</div>
							</li>
							<?php
							}
							?>
						</ul>
					</div>
				</div>
			</div>
			<div class="col-lg-4">
				<div class="homeWdgt homeWdgt03 mB32">
					<div class="w01_head">
						<h1 class="wgt_HeadT1">Study Abroad</h1>
						<p class="wgt_HeadT2">Explore universities in popular countries</p>
					</div>
					<div class="n-homeFlags">
						<?php $studyAbroadMap = $this->config->item('studyAbroadMap');
						foreach($studyAbroadMap as $country => $data):?>
						<span <?=($data['lastRow'])?'class="mB0"':''?>>
							<a label="<?=$data['label']?>" href="<?=$data['url']?>"><i class="flags <?=$data['flagClass']?>"></i></a>
							<a label="<?=$data['label']?>" href="<?=$data['url']?>"><p><?=$data['label']?></p></a>
						</span>
						<?php endforeach; ?>
						<p class="clr"></p>
					</div>				
				</div>
				
				<?php //start review and campus connect widget
					$this->load->view('home/homepageRedesign/college_review_widget');
					$this->load->view('home/homepageRedesign/campus_connect_widget');
					//end-widget ?>
				
			</div>

			<div class="col-lg-3 pR0 fRight">
				<?php //start get expert guidance widget
				     $this->load->view('home/homepageRedesign/get_expert_guidance_widget');
					//end get expert widget ?>

				<div class="homeWdgt homeWdgt07">
					<div class="w01_head">
						<h1 class="wgt_HeadT1">Careers after 12th</h1>
						<p class="wgt_HeadT2">Find the right career for you</p>	
					</div>
					<div class="w07_carrerAfter">
						<a href="<?=SHIKSHA_HOME?>/career-as-engineer-cc-41">
							<img class="lazy" data-original="../public/images/dummy5.png" />
							<p>Engineer</p>
						</a>
						<a href="<?=SHIKSHA_HOME?>/career-as-hotel-manager-cc-62">
							<img class="lazy" data-original="../public/images/dummy6.png" />
							<p>Hotel Manager</p>
						</a>
						<a href="<?=SHIKSHA_HOME?>/career-as-fashion-designer-cc-45">
							<img class="lazy" data-original="../public/images/dummy7.png" />
							<p>Fashion Designer</p>
						</a>
						<a href="<?=SHIKSHA_HOME?>/career-as-lawyer-cc-69">
							<img class="lazy" data-original="../public/images/dummy8.png" />
							<p>Lawyer</p>
						</a>
					</div>
					<span class="w07_strt_here">
						<a class="btn_orangeT1" style="color: #fff !important;" href="<?=SHIKSHA_HOME?>/career-options-after-12th">Start here</a>
					</span>			
					<div class="w07_get_rcomdtn">
						<p class="wgt_HeadT2">Get Career recomendations by Mrs. Kumkum Tandon</p>
						<span>
							<img src="../public/images/dummy9.png" />
							<p>MA (Psy.), M.Ed, Dip.Edu.Psy.Vocl Guidance & Counseling </p>
							<b>(NCERT, Delhi)</b>
						</span>
					</div>
				</div>
			</div>
			<p class="clr"></p>
		</div>
		<div class="n-row">
			<?php $this->load->view('home/homepageRedesign/homepageBannerWidget'); ?>
		</div>
		<div class="n-row">
			<?php $this->load->view('home/homepageRedesign/homepageBottomWidget'); ?>
			<p class="clr"></p>
		</div>
	</div>
</div>