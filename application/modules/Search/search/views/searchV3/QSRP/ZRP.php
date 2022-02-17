<?php $this->load->view('nationalCategoryList/categoryPageHeader'); ?>

<div class="zrp_new">
    <p class="zrp_title">Showing Results for </p>
    <div class="shw_zrp_rslts"><p class="data_filters">Sorry no results found for "<?php echo $keyword; ?>"</p>
        <a class="new_btns a_q" id="modifySearchQues">Modify Search</a>
        <a class="new_btns m_s" id="askQuesZRP">Ask Question</a>
    </div>
</div>

<?php $this->load->view('common/footerNew'); ?>

<script type="text/javascript">
    var retainSearchKeyword = '<?php echo addslashes($keyword); ?>';
    initializeQSRP('zrp');
</script>