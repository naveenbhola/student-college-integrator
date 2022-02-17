function getShikshaCountryCriteria(countryId) {
    var countryText = 'All';
    switch(trim(countryId)){
        case '2': countryText = 'India'; break;
        case '3': countryText = 'USA'; break;
        case '4': countryText = 'UK'; break;
        case '5': countryText = 'Australia'; break;
        case '6': countryText = 'Singapore'; break;
        case '7': countryText = 'New Zealand'; break;
        case '8': countryText = 'Canada'; break;
        case '9': countryText = 'Germany'; break;
    }
    return countryText;
}

function getShikshaCityCriteria(cities) {
    var cityIds = cities.split(',');
    var citiesText = '';
    var countryId = '';
    for(var cityIdCount =0; cityIdCount < cityIds.length; cityIdCount++) {
        var cityId = trim(cityIds[cityIdCount]);
        if(cityId == '') {continue;}
        if(citiesText != '') {
            citiesText += ' , ';
        }
        if(countryId == '') {
            for(var country in cityList){
                if(cityList[country][cityId] != null) {
                    countryId = country;
                    break;
                }
            }
        }
        try{
        citiesText += cityList[countryId][cityId];
        } catch(e){}
    }
    return citiesText;
}

function getShikshaCategoryCriteria(categories) {
    var categoryIds = categories.split(',');
    var categoryText = '';
    var categoryTextTree = new Array();
    for(var categoryIdCount =0; categoryIdCount < categoryIds.length; categoryIdCount++) {
        var categoryId = trim(categoryIds[categoryIdCount]);
        if(categoryId == '') { continue; }
        var categoryParentId = trim(completeCategoryTree[categoryId][1]);
        categoryParentId = categoryParentId == '' ? 0 : categoryParentId;
        if(!categoryTextTree[categoryParentId]) {
            categoryTextTree[categoryParentId] = new Array();
        }
        categoryTextTree[categoryParentId].push(completeCategoryTree[categoryId][0]);
    }
    for(var parent in categoryTextTree) {
        categoryText += '<br/><i>' + completeCategoryTree[parent][0] + '</i> => ' +categoryTextTree[parent].join(' , ')+'<br/>';
    }
    return categoryText;
}

function updateShikshaCriterias() {
    var criteriaElements = document.getElementsByTagName('criteria');
    for(var criteriaElementCount = 0; criteriaElementCount < criteriaElements.length; criteriaElementCount++){
        var newCriteriaStr = '';
        if( criteriaElements[criteriaElementCount].innerHTML.indexOf('Shiksha') < 0) {continue;}
        var criterias = criteriaElements[criteriaElementCount].innerHTML.split('<br>');
        for(var criteriaCount =0; criteriaCount < criterias.length; criteriaCount++) {
            var matches= criterias[criteriaCount].match(/<b>Shiksha(.*)--&gt;<\/b>#(.*)#/);
            if(matches != null) {
                var criteriaType = matches[1];
                var criteriaStr = matches[2];
                //alert(criteriaType);
                switch(trim(criteriaType)){
                    case 'Countries':
                        criteriaStr = getShikshaCountryCriteria(criteriaStr) + '<br/>';
                        break;
                    case 'Categories':
                        criteriaStr = getShikshaCategoryCriteria(criteriaStr);
                        break;
                    case 'City':
                        criteriaStr = getShikshaCityCriteria(criteriaStr);
                        break;
                    case 'Keywords':
                        criteriaStr = criteriaStr + '<br/>';
                        break;
                }
                if(criteriaStr != ''){
                    newCriteriaStr += '<b>Shiksha '+ criteriaType  +'--></b> '+ criteriaStr +' <br/>';
                }
            }
        }
        criteriaElements[criteriaElementCount].innerHTML = newCriteriaStr;
    }

}

function createShikshaCountryDD(countryDDId) {
    var shikshaCountriesList = new Array();
    shikshaCountriesList[1] = 'All';
    shikshaCountriesList[2] = 'India';
    shikshaCountriesList[3] = 'USA';
    shikshaCountriesList[4] = 'UK';
    shikshaCountriesList[5] = 'Australia';
    shikshaCountriesList[6] = 'Singapore';
    shikshaCountriesList[7] = 'New Zealand';
    shikshaCountriesList[8] = 'Canada';
    shikshaCountriesList[9] = 'Germany';
    for(var countryListCount = 1; countryListCount < shikshaCountriesList.length; countryListCount++){
        var countryOption = document.createElement('option');
        countryOption.value = countryListCount;
        countryOption.innerHTML = shikshaCountriesList[countryListCount];
        document.getElementById(countryDDId).appendChild(countryOption);
    }
}

// var completeCategoryTree = eval({"89":["Accounting  ","4"],"110":["Acting, Modelling   ","7"],"111":["Advertising     ","7"],"70":["Aeronautical, Aerospace ","2"],"71":["Agriculture, Environmental Sciences, Forestry, Wild Life","2"],"104":["Air Hostess Training, Aviation  ","6"],"105":["Airlines & Ticketing    ","6"],"1":["All","0"],"145":["Animation   ","12"],"12":["Animation, Multimedia","1"],"136":["Application Programming ","10"],"69":["Fashion & Textile Design    ","13"],"152":["Architecture","2"],"125":["Arts & Humanities   ","9"],"9":["Arts, Law and Languages","1"],"90":["Banking ","4"],"4":["Banking and Finance","1"],"72":["Biological Sciences, Biotechnology, Bio-chemistry, Life Sciences","2"],"70":["Interior Design   ","13"],"80":["Business Administration, Management in General  ","3"],"91":["CA \/ CWA \/ CS \/ CFA \/ Other Professional Courses  ","4"],"73":["Chemistry, Chemical Engineering ","2"],"74":["Civil Engineering   ","2"],"155":["Civil Services, Politics and Railways","9"],"154":["Clinical Research","5"],"126":["Creative Arts, Commercial Arts, Performing Arts ","9"],"95":["Dental Sciences ","5"],"71":["Jewellery & Accessory Design ","13"],"75":["Electronics, Computer Sciences, Information Technology","2"],"137":["Embedded, VLSI, ASIC, Chip Design   ","10"],"76":["Engineering in General  ","2"],"106":["Event Management    ","6"],"72":["Industrial, Automotive, Product Design  ","13"],"112":["Films and Television    ","7"],"81":["Finance ","3"],"139":["Front Office    ","11"],"147":["Graphic Designing   ","12"],"132":["Hardware Courses    ","10"],"73":["Interaction Design   ","13"],"96":["Health Care Management  ","5"],"97":["Homeopathy  ","5"],"6":["Hospitality, Tourism, Aviation","1"],"107":["Hotel Management    ","6"],"82":["Human Resources ","3"],"153":["Industrial Engineering","2"],"83":["Information Technology  ","3"],"10":["Information Technology","1"],"131":["Information Technology in General   ","10"],"92":["Insurance","4"],"190":["Fashion & Textile Design   ","13"],"84":["International Business  ","3"],"140":["Inventory   ","11"],"113":["Journalism  ","7"],"127":["Languages   ","9"],"129":["Law, Legal  ","9"],"3":["Management and Business","1"],"114":["Mass Communication in General   ","7"],"77":["Mathematics, Statistics, Related Subjects   ","2"],"78":["Mechanical Engineering  ","2"],"7":["Media, Films, Mass Communications","1"],"5":["Medicine and Health Care","1"],"98":["Medicine in General ","5"],"134":["Networking  ","10"],"99":["Neurosciences   ","5"],"100":["Nursing & Related Courses   ","5"],"85":["Operations  ","3"],"144":["Others ","11"],"148":["Others..    ","12"],"103":["Others..    ","5"],"88":["Others..    ","3"],"116":["Others..    ","7"],"109":["Others..    ","6"],"94":["Others..    ","4"],"79":["Others..    ","2"],"130":["Others..    ","9"],"191":["Interior Design    ","13"],"138":["Others..    ","10"],"192":["Jewellery & Accessory Design ","13"],"193":["Industrial, Automotive, Product Design ","13"],"194":["Interaction Design ","13"],"101":["Physiotherapy   ","5"],"13":["Design","1"],"102":["Psychology  ","5"],"115":["Public Relations    ","7"],"11":["Retail","1"],"86":["Retail  ","3"],"87":["Sales & Marketing   ","3"],"2":["Science and Engineering","1"],"93":["Securities & Trading    ","4"],"141":["Shop Floor management   ","11"],"128":["Sociology, Social Sciences  ","9"],"133":["Software Courses    ","10"],"142":["Store management    ","11"],"143":["Supply Chain Management \/ Distribution  ","11"],"135":["Telecom ","10"],"108":["Travel & Toursim    ","6"],"146":["Web Designing   ","12"]});

var completeCategoryTree = eval({"89":["Accounting  ","4"],"110":["Acting, Modelling   ","7"],"111":["Advertising     ","7"],"70":["Aeronautical, Aerospace ","2"],"71":["Agriculture, Environmental Sciences, Forestry, Wild Life","2"],"104":["Air Hostess Training, Aviation  ","6"],"105":["Airlines & Ticketing    ","6"],"1":["All","0"],"145":["Animation   ","12"],"12":["Animation, Multimedia","1"],"136":["Application Programming ","10"],"69":["Fashion & Textile Design    ","13"],"152":["Architecture","2"],"125":["Arts & Humanities   ","9"],"9":["Arts, Law and Languages","1"],"90":["Banking ","4"],"4":["Banking and Finance","1"],"72":["Biological Sciences, Biotechnology, Bio-chemistry, Life Sciences","2"],"70":["Interior Design   ","13"],"80":["Business Administration, Management in General  ","3"],"91":["CA \/ CWA \/ CS \/ CFA \/ Other Professional Courses  ","4"],"73":["Chemistry, Chemical Engineering ","2"],"74":["Civil Engineering   ","2"],"155":["Civil Services, Politics and Railways","9"],"154":["Clinical Research","5"],"126":["Creative Arts, Commercial Arts, Performing Arts ","9"],"95":["Dental Sciences ","5"],"71":["Jewellery & Accessory Design ","13"],"75":["Electronics, Computer Sciences, Information Technology","2"],"137":["Embedded, VLSI, ASIC, Chip Design   ","10"],"76":["Engineering in General  ","2"],"106":["Event Management    ","6"],"72":["Industrial, Automotive, Product Design  ","13"],"112":["Films and Television    ","7"],"81":["Finance ","3"],"139":["Front Office    ","11"],"147":["Graphic Designing   ","12"],"132":["Hardware Courses    ","10"],"73":["Interaction Design   ","13"],"96":["Health Care Management  ","5"],"97":["Homeopathy  ","5"],"6":["Hospitality, Tourism, Aviation","1"],"107":["Hotel Management    ","6"],"82":["Human Resources ","3"],"153":["Industrial Engineering","2"],"83":["Information Technology  ","3"],"10":["Information Technology","1"],"131":["Information Technology in General   ","10"],"92":["Insurance","4"],"190":["Fashion & Textile Design   ","13"],"84":["International Business  ","3"],"140":["Inventory   ","11"],"113":["Journalism  ","7"],"127":["Languages   ","9"],"129":["Law, Legal  ","9"],"3":["Management and Business","1"],"114":["Mass Communication in General   ","7"],"77":["Mathematics, Statistics, Related Subjects   ","2"],"78":["Mechanical Engineering  ","2"],"7":["Media, Films, Mass Communications","1"],"5":["Medicine and Health Care","1"],"98":["Medicine in General ","5"],"134":["Networking  ","10"],"99":["Neurosciences   ","5"],"100":["Nursing & Related Courses   ","5"],"85":["Operations  ","3"],"144":["Others ","11"],"148":["Others..    ","12"],"103":["Others..    ","5"],"88":["Others..    ","3"],"116":["Others..    ","7"],"109":["Others..    ","6"],"94":["Others..    ","4"],"79":["Others..    ","2"],"130":["Others..    ","9"],"191":["Interior Design    ","13"],"138":["Others..    ","10"],"192":["Jewellery & Accessory Design ","13"],"193":["Industrial, Automotive, Product Design ","13"],"194":["Interaction Design ","13"],"101":["Physiotherapy   ","5"],"13":["Design","1"],"102":["Psychology  ","5"],"115":["Public Relations    ","7"],"11":["Retail","1"],"86":["Retail  ","3"],"87":["Sales & Marketing   ","3"],"2":["Science and Engineering","1"],"93":["Securities & Trading    ","4"],"141":["Shop Floor management   ","11"],"128":["Sociology, Social Sciences  ","9"],"133":["Software Courses    ","10"],"142":["Store management    ","11"],"143":["Supply Chain Management \/ Distribution  ","11"],"135":["Telecom ","10"],"108":["Travel & Toursim    ","6"],"146":["Web Designing   ","12"],"239":["Business","1"],"240":["Engineering","1"],"241":["Computers","1"],"242":["Science","1"],"243":["Medicine","1"],"244":["Humanities","1"],"245":["Law","1"],"1508":["MBA","1"],"1509":["MS","1"],"1510":["BE/BTECH","1"]
});
