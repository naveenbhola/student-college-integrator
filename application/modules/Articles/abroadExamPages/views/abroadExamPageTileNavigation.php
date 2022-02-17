<div class="clearfix grid-wrap">
	<ul class="grid-list clearwidth">
		<li>
			<a href="javascript:void(0)" onclick="navigate('examPageHeadingTitle');"><div class="exam-grid-1 grid-pattern">
				<strong><?= str_replace('<exam-name>',$examPageObj->getExamName(),$abroadExamPageTilesContent[1]['title']);?></strong>
				<p><?= ($this->config->config['about_exam'][$examPageObj->getExamName()] !='')?$this->config->config['about_exam'][$examPageObj->getExamName()]:$abroadExamPageTilesContent[1]['text'];?></p>
				<i class="abroad-exam-sprite exam-abt-icon"></i>
			</div>
			</a>
			<a href="<?= $sectionLinks[2];?>">
			<div class="exam-grid-2">
				<strong><?= str_replace('<exam-name>',$examPageObj->getExamName(),$abroadExamPageTilesContent[2]['title']);?></strong>
				<p><?= str_replace('<exam-name>',$examPageObj->getExamName(),$abroadExamPageTilesContent[2]['text']);?></p>
				<i class="abroad-exam-sprite exam-pattern-icon"></i>
			</div>
			</a>
			<a href="<?= $sectionLinks[3];?>">
			<div class="exam-grid-3 no-margin grid-pattern">
				<strong><?= str_replace('<exam-name>',$examPageObj->getExamName(),$abroadExamPageTilesContent[3]['title']);?></strong>
				<p><?= str_replace('<exam-name>',$examPageObj->getExamName(),$abroadExamPageTilesContent[3]['text']);?></p>
				<i class="abroad-exam-sprite exam-score-icon"></i>
			</div>
			</a>
		</li>
		<li>
			<a href="<?= $sectionLinks[4];?>">
			<div class="exam-grid-4">
				<strong><?= str_replace('<exam-name>',$examPageObj->getExamName(),$abroadExamPageTilesContent[4]['title']);?></strong>
				<p><?= str_replace('<exam-name>',$examPageObj->getExamName(),$abroadExamPageTilesContent[4]['text']);?></p>
				<i class="abroad-exam-sprite exam-date-icon"></i>
			</div>
			</a>
			<a href="<?= $sectionLinks[5];?>">
			<div class="exam-grid-5">
				<strong><?= str_replace('<exam-name>',$examPageObj->getExamName(),$abroadExamPageTilesContent[5]['title']);?></strong>
				<p><?= str_replace('<exam-name>',$examPageObj->getExamName(),$abroadExamPageTilesContent[5]['text']);?></p>
				<i class="abroad-exam-sprite exam-prep-tips-icon"></i>
			</div>
			</a>
			<a href="<?= $sectionLinks[6];?>">
			<div class="exam-grid-6 no-margin">
				<strong><?= str_replace('<exam-name>',$examPageObj->getExamName(),$abroadExamPageTilesContent[6]['title']);?></strong>
				<p><?= str_replace('<exam-name>',$examPageObj->getExamName(),$abroadExamPageTilesContent[6]['text']);?></p>
				<i class="abroad-exam-sprite exam-paper-icon"></i>
			</div>
			</a>
		</li>
		<li>
			<a href="<?= $sectionLinks[7];?>">
			<div class="exam-grid-7 grid-pattern">
				<strong><?= str_replace('<exam-name>',$examPageObj->getExamName(),$abroadExamPageTilesContent[7]['title']);?></strong>
				<p><?= str_replace('<exam-name>',$examPageObj->getExamName(),$abroadExamPageTilesContent[7]['text']);?></p>
				<i class="abroad-exam-sprite exam-syllabus-icon"></i>
			</div>
			</a>
			<a href="<?= $sectionLinks[8];?>">
			<div class="exam-grid-8">
				<strong><?= str_replace('<exam-name>',$examPageObj->getExamName(),$abroadExamPageTilesContent[8]['title']);?></strong>
				<p><?= str_replace('<exam-name>',$examPageObj->getExamName(),$abroadExamPageTilesContent[8]['text']);?></p>
				<i class="abroad-exam-sprite exam-college-icon"></i>
			</div>
			</a>
			<a href="<?= $sectionLinks[9];?>">
			<div class="exam-grid-9 no-margin grid-pattern">
				<strong><?= str_replace('<exam-name>',$examPageObj->getExamName(),$abroadExamPageTilesContent[9]['title']);?></strong>
				<p><?= str_replace('<exam-name>',$examPageObj->getExamName(),$abroadExamPageTilesContent[9]['text']);?></p>
				<i class="abroad-exam-sprite exam-article-icon"></i>
			</div>
			</a>
		</li>
	</ul>
</div>