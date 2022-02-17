<style>
#suggestions_container li{     border-bottom: 1px solid #f6f6f6;
  color: #808080;
  cursor: pointer;
  display: block;
  font-size: 12px;
  padding: 8px 10px;
  text-align: left;
  text-decoration: none;}
#suggestions_container li span strong{  color: #000;  display: inline; font-size: 12px; color:#808080; font-weight: bold;}
</style>
<div class="campus-conne-heading">
  <h1> <?php echo $m_meta_title;?></h1>
</div>

<div class="cr_searchBx campus-conne-container">
      <div class="student-directly">
      <ul>
      <li>
      <span>Ask Current Student Directly</span><br>
      Find all about college admissions,<br> 
placements, campus life &amp; more
      </li>
      <li>
      <span>Know the Inside-Story</span><br>
  Catch the latest buzz on top colleges <br>
 &amp; see what others are talking about
      
      </li>
      </ul>
      </div>
     <div class="cr_inputsrchBx bordRdus3px">
        <div class="cr_inputsrchBxInnr" id="cmp-search-box">
          <i class="icns-colgrvw icn-search" onclick="gotoSearchPage('<?php echo SHIKSHA_HOME;?>','keywordSuggest');" style="cursor: pointer;"></i>
          <input type="text" placeholder="Search by college name to ask or view questions" name="keyword" id="keywordSuggest" minlength="1" autocomplete="off"/>
        </div>
        <div class="cr_sugestd">
            	<ul id="suggestions_container" style="text-align: left;"></ul>
        </div>
  </div>
    </div>
<script>
//hide autosuggestor container click on outside of container
$j(document).click(function (e)
{  
    var container = $j("#suggestions_container");
    var container2 = $j("#keywordSuggest, #cmp-search-box");
    if (!container.is(e.target) && container.has(e.target).length === 0 && !container2.is(e.target) && container2.has(e.target).length === 0)
    {   
        if ($j('#suggestions_container').length>0) {
		container.slideUp(400);
	}
    }
});
</script>