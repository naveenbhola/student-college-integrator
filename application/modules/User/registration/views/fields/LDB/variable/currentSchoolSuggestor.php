<link rel="stylesheet" href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion('jquery.auto-complete');?>">
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('jquery.auto-complete');?>"></script>
<script>
        var attemptCount=0;
        var autoSuggestorInterval = setInterval(function()
        {
            attemptCount++;
            if(typeof($j().autoComplete)=='function'){
            $j('#currentSchool_<?php echo $regFormId; ?>').autoComplete({
                minChars: 1,
                source: function(term, suggest){
                    term = term.toLowerCase(); 
                    var suggestions = [];
                    for (i=0;i<choices.length;i++)
                        if (~choices[i].toLowerCase().indexOf(term)) suggestions.push(choices[i]);
                    suggest(suggestions);
                }
            });
            clearInterval(autoSuggestorInterval);
            }else{
                console.log('not done');
                if(attemptCount>10){
                 clearInterval(autoSuggestorInterval);   
                }
            } 
        },500);
</script>