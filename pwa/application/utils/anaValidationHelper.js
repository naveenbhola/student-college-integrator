import React from 'react';

export const validateAnAField = (obj) => {
  let boxValue = obj.value.trim();
  let boxId = obj.id;
  let valid = true;
  if(boxValue.length == 0){
    document.querySelector('#'+obj.id+'_error').innerHTML = 'Please enter the '+obj.getAttribute('caption');
    valid = false;
  }else if(boxValue.length < 20){
    document.querySelector('#'+obj.id+'_error').innerHTML = 'The '+obj.getAttribute('caption')+' must contain atleast 20 characters.';
    valid = false;
  }

  if(!valid){
    document.querySelector('#'+obj.id+'_error').style.display = 'block';
    document.querySelector('#'+obj.id+'_error').parentElement.style.display = 'inline';
  }else{
    document.querySelector('#'+obj.id+'_error').style.display = 'none';
    document.querySelector('#'+obj.id+'_error').parentElement.style.display = 'none';
  }
  return valid;
}

export const validateAnAPostTextCustomValidations = (phone, links, textToBeValidated, caption) => {
  if(textToBeValidated != ""){
    var patt = "";
    var result = false;
    // Phone Numbers should not present in the title / Description1
    if(phone == true){
        patt = /[0-9]{8,1000}/g;
        result = patt.test(textToBeValidated);
        if(result == true){
            return "Mobile/Phone numbers are not allowed in "+caption;
        }
    }

    // Links should not be there in the string
    if(links == true){
        patt = new RegExp("(http|ftp|https)://([\\w_-]+(?:(?:\\.[\\w_-]+)+))([\\w.,@?^=%&:/~+#-]*[\\w@?^=%&/~+#-])?");
        result = patt.test(textToBeValidated);
        if(result == true){
            return "Links are not allowed in "+caption;
        }

        if(textToBeValidated.indexOf("www.") != -1){
            return "Links are not allowed in "+caption;
        }

    }
  } else if(caption.toLowerCase() == "discussion") {
      return "Please enter the description";
  }
  return true;
}

export const validateAnAQuesCourse = () => {
  var input = null, ul, li, a, i;
  document.querySelector('#instituteCoursesQP').value = '';
  if(document.querySelector('#cLst_error') != null){
    document.querySelector('#cLst_error').style.display = 'none';
  }
  if(document.querySelector('#askCourseSelected') != null){
    input = document.querySelector('#askCourseSelected').value.toLowerCase().trim();
  }
  if(input){
    ul = document.getElementById("cLst");
    li = ul.getElementsByTagName("a");
    for (i = 0; i < li.length; i++) {
        var str = false;
        var items = li[i].innerHTML.trim().toLowerCase();
        var courseId = li[i].getAttribute('data-id');
        if(input == items){
          str = true;
          document.querySelector('#instituteCoursesQP').value = courseId;
          break;
        }
    }
    return (str) ? true : false;
  }
  return true;
}
