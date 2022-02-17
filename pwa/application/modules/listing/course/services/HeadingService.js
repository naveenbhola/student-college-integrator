import { getRequest } from '../../../../utils/ApiCalls';
import APIConfig from './../../../../../config/apiConfig';

export function createHeading(program, instituteName, batch_year){
  var placementHeading = '';
  if(batch_year){
    placementHeading = 'Showing placement details for '+program+' programs of '+instituteName+' for '+batch_year+' batch.';
  }
  else{
    placementHeading = 'Showing placement details for '+program+' programs of '+instituteName;
  }
  return placementHeading;
}

export function prepareHeadingData(batch_year, course, course_type, instituteName){
  var program = '';
  switch (course_type) {
    case 'clientCourse':
        return '';
        break;
    case 'streamId':
          return getRequest(APIConfig.GET_MULTIPLE_STREAMS+'?streamIds='+course)
          .then((response) =>{
          program = response.data.data[course].name;
          return createHeading(program, instituteName, batch_year);
          })
          .catch((err)=> console.log('Issue==',err));
        break;
    case 'substreamId':
        return getRequest(APIConfig.GET_MULTIPLE_SUBSTREAMS+'?substreamIds='+course)
        .then((response) =>{
          program = response.data.data[course].name;
          return createHeading(program, instituteName, batch_year);
          })
          .catch((err)=> console.log('Issue==',err));
        break;
    case 'baseCourse':
          return getRequest(APIConfig.GET_MULTIPLE_BASECOURSES+'?baseCourseIds='+course)
          .then((response) =>{
          program = response.data.data[course].name;
          return createHeading(program, instituteName, batch_year);
          })
          .catch((err)=> console.log('Issue==',err));
        break;
    default:                  
        break;
  }
}
