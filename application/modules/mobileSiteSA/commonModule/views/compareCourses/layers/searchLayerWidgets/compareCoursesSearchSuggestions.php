<ul>

<?php foreach($results as $univSuggestion){
		
		$univSuggestion['saAutosuggestUnivNameFacet'] = str_replace('&amp;',' and ',$univSuggestion['saAutosuggestUnivNameFacet']);
		$searchText = str_replace('&','and',$searchText);
        $searchText=trim($searchText);
		$start = stripos($univSuggestion['saAutosuggestUnivNameFacet'],$searchText);
		if($start === false)
		{
			$suggestionLabel = $univSuggestion['saAutosuggestUnivNameFacet'];
		}else{
			$replacement = substr($univSuggestion['saAutosuggestUnivNameFacet'],$start, strlen($searchText));
			$suggFrags =  explode($replacement, $univSuggestion['saAutosuggestUnivNameFacet'],2);
			$suggestionLabel = $suggFrags[0].'<strong>'.$replacement.'</strong>'.$suggFrags[1];
		}
		?>
		<li class="uniSug" univId="<?php echo $univSuggestion['saAutosuggestUnivId']; ?>">
				<div class="suggstr-box">
						<p class="src-cTitle"><?php echo $suggestionLabel; ?></p>
						<div class="src-clr"></div>
				</div>
		</li>
<?php } ?>
</ul>