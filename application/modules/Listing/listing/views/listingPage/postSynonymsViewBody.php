<div id="form">

	<h2>Edit the synonyms</h2>
	<div>
		<div>Enter institude id:</div> <input type="number" onchange="enableClick()" onkeyup="checkifempty()" id="instid" name="instid" size="40" <?php if(isset($instid)) echo "value={$instid}"; ?>><button type="button" onclick="getdata()">GET</button><span id="msg"></span>
	</div>
	
	<div>
		<div>Synonyms (Enter comma seperated values)</div>
		<textarea id="synonyms" onchange="enableClick()" rows="4" cols="40" name="synonyms"><?php if(isset($synonyms)) echo "{$synonyms}"; ?></textarea>
	</div>
	<div>
		<div>Acronyms (Enter comma seperated values)</div>
		<textarea id="acronyms" onchange="enableClick()" rows="4" cols="40" name="acronyms"><?php if(isset($acronyms)) echo "{$acronyms}"; ?></textarea>
	</div>

	<div>
		<input type="button" value="Submit" id="submit" onclick="postdata()">
		<b id="insertionresult"><?php if(isset($insertionresult)) echo $insertionresult; ?></b>
	</div>

	<div id="status"></div>
</div>