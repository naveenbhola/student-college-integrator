import {getGallerySchema} from  './../../listing/course/components/GalleryComponent';

export default function SeoSchema(stateData, fromWhere)
{
    var tmpObj  = {};
    var schemaText = '';
    var contactSchema = '';
    var reviewSchema ='';
    if(fromWhere == 'categoryPage' && stateData && typeof(stateData) !='undefined' && typeof(stateData.categoryData) !='undefined' && Object.keys(stateData).length>0){
        tmpObj = (typeof(stateData.categoryData.requestData.categoryData) !='undefined') ? stateData.categoryData.requestData.categoryData : {};
        var description = 'Check the list of all <COURSE> colleges/institutes in <LOCATION> listed on Shiksha. Get all information  related to admissions, fees, courses, placements, reviews & more on  <COURSE> colleges in <LOCATION> to help you decide which college you should be targeting for <COURSE> admissions in <LOCATION>.';
        var headingCriteria = (stateData.categoryData.requestData.headingCriteria) ? stateData.categoryData.requestData.headingCriteria : '';
        var locationString = (stateData.categoryData.requestData.locationString) ? stateData.categoryData.requestData.locationString : '';
        var numberOfItems = (stateData.categoryData.totalInstituteCount) ? stateData.categoryData.totalInstituteCount : 0;
        var headingMobile = (typeof(tmpObj) !='undefined' && Object.keys(tmpObj).length>0 && typeof(tmpObj.headingMobile) !='undefined' && tmpObj.headingMobile !=null) ? tmpObj.headingMobile : '';

        description = description.replace(/<COURSE>/g,headingCriteria);
        description = description.replace(/<LOCATION>/g,locationString);

        var str = '';
        for(var i in Object.keys(stateData.categoryData.categoryInstituteTuple)){
            var url = stateData.config.SHIKSHA_HOME+Object.keys(stateData.categoryData.categoryInstituteTuple)[i].url;
            var   j = ++i;
            str += '{"@type":"ListItem","position":'+j+',"url":"'+url+'"},';
        }

        str = str.replace(/,\s*$/, ""); //remove the last comma and any whitespace
        
        schemaText = '<script type="application/ld+json">{"@context":"http://schema.org","@type":"ItemList","name": "'+headingMobile+'","url":"'+stateData.config.SHIKSHA_HOME+'/'+tmpObj.url+'", "description": "'+description+'", "numberOfItems":'+numberOfItems+',"itemListElement":['+str+']}</script>';

    }else if(fromWhere == 'coursePage' && stateData && typeof(stateData) !='undefined' && typeof(stateData.courseData) !='undefined' && Object.keys(stateData).length>0 && Object.keys(stateData.courseData).length > 0){

        tmpObj = stateData.courseData;

        var affiliation = (tmpObj.affiliationData && typeof(tmpObj.affiliationData.name) !='undefined') ? "Affiliated to "+tmpObj.affiliationData.name : '';
        var cityName = (tmpObj.currentLocation) ? tmpObj.currentLocation.city_name : '';
        var institutesWithLocation = (tmpObj.instituteName.indexOf(cityName) != -1) ? tmpObj.instituteName : tmpObj.instituteName+' '+cityName;
        var description = (affiliation) ? affiliation : tmpObj.courseName+' at '+institutesWithLocation;

        contactSchema = generateContactSchema(tmpObj,fromWhere);
        reviewSchema = generateReviewSchema(tmpObj);
        var gallerySchema = getGallerySchema();
        schemaText = '<script type="application/ld+json">{"@context" : "http://schema.org","@type" : "Course","name"  : "'+tmpObj.courseName+'","description" : "'+description+'"'+contactSchema+'}</script>'+gallerySchema;

    }else if(fromWhere == 'institutePage' && stateData && typeof(stateData) !='undefined' && typeof(stateData.instituteData) !='undefined' && Object.keys(stateData).length>0 && Object.keys(stateData.instituteData).length > 0){

        tmpObj = stateData.instituteData;
        contactSchema = generateContactSchema(tmpObj,fromWhere);
        reviewSchema = generateReviewSchema(tmpObj,fromWhere);
        var gallerySchema = getGallerySchema();
        schemaText = '<script type="application/ld+json">'+contactSchema+'</script>'+gallerySchema;

    }  else if(fromWhere =='courseHomePage' && stateData && typeof(stateData) !='undefined' && typeof(stateData.courseHomePageData) !='undefined' && Object.keys(stateData).length>0 && Object.keys(stateData.courseHomePageData).length > 0){

    }
    else if(fromWhere == 'allChildPage' && stateData && typeof(stateData) !='undefined' && typeof(stateData.childPageData) !='undefined' && Object.keys(stateData).length>0 && Object.keys(stateData.childPageData).length > 0 )
     {      
        tmpObj = stateData.childPageData;
        contactSchema = generateContactSchema(tmpObj,fromWhere);
        schemaText = '<script type="application/ld+json">'+contactSchema+'</script>';
     }   
        
    return schemaText;
}



function generateContactSchema(data,fromWhere)
{
    var email = '',contact='',website_url='',address='';
        var contactObj = (typeof(data.currentLocation) !='undefined' && typeof(data.currentLocation.contact_details)!='undefined') ? data.currentLocation.contact_details : '';
        if(typeof(contactObj) !='undefined' && contactObj != null){
            email = (contactObj && contactObj.admission_email) ? contactObj.admission_email : contactObj.generic_email; 
            contact = (contactObj && contactObj.admission_contact_number) ? contactObj.admission_contact_number : contactObj.generic_contact_number;     
            website_url = (contactObj.website_url) ? contactObj.website_url : null;
            address = (contactObj.address) ? contactObj.address :null;
        }
    var contactSchema ='';
    var aggregateReviewSchema = '';
    if(fromWhere == "coursePage"){
        aggregateReviewSchema = generateAggregateReviewSchema(data,data.coursePaid);
        contactSchema = ',"provider" : {"@type" : "CollegeOrUniversity","name" : "'+data.instituteName+'","url"  : "'+website_url+'","email" : "'+email+'","telephone": "'+contact+'","address" : "'+address+'"'+aggregateReviewSchema+'}';
    }
    else if(fromWhere == "institutePage" || fromWhere == 'allChildPage'){
        var isPaid = false;
        if(typeof data.courseWidget !='undefined' && data.courseWidget && typeof data.courseWidget.instituteHasPaidCourse !='undefined'){
            isPaid = data.instituteHasPaidCourse;
        }
        aggregateReviewSchema = generateAggregateReviewSchema(data,isPaid);
        contactSchema = '{"@context" : "http://schema.org","@type" : "CollegeOrUniversity","name"  : "'+data.listingNameWithCityLocality+'","url"  : "'+website_url+'","email" : "'+email+'","telephone": "'+contact+'","address" : "'+address+'"'+aggregateReviewSchema+'}';
    }
    return contactSchema;
}

function generateAggregateReviewSchema(data,isPaid = false)
{
    var aggregateReviewData = (data.aggregateReviewWidget && data.aggregateReviewWidget != null && data.aggregateReviewWidget.aggregateReviewData && data.aggregateReviewWidget.aggregateReviewData.aggregateRating)? data.aggregateReviewWidget.aggregateReviewData.aggregateRating.averageRating:'';
    var totalCount = (aggregateReviewData.count != null)? aggregateReviewData.count:'' ;
    var averageRating = (aggregateReviewData.mean != null)? aggregateReviewData.mean:'';
    var isPaid = (isPaid === null)?false:isPaid;
    var aggregateReviewSchema = '' ;
    if( aggregateReviewData && averageRating && (!isPaid || (isPaid && averageRating > 3.5))){
        aggregateReviewSchema = ',"aggregateRating":{"@type" : "AggregateRating","bestRating":"5","ratingValue":"'+averageRating+'","reviewCount":"'+totalCount+'","worstRating":"1"}';
    }
    return aggregateReviewSchema;
}

function generateReviewSchema(data,fromWhere){

    var website_url='';
    var name = '';
    if(fromWhere == "coursePage"){
        name = data.instituteName;
    }
    else if(fromWhere == "institutePage"){
        name = data.listingNameWithCityLocality;
    }
    var contactObj = (typeof(data.currentLocation) !='undefined' && typeof(data.currentLocation.contact_details)!='undefined') ? data.currentLocation.contact_details : '';
    if(typeof(contactObj) !='undefined' && contactObj != null){
        website_url = (contactObj.website_url) ? contactObj.website_url : null;
    }

    var reviewObj = (data.reviewWidget && data.reviewWidget.reviewData && data.reviewWidget.reviewData.reviewsData)?data.reviewWidget.reviewData.reviewsData:null;
    var totalCount = (data.reviewWidget && data.reviewWidget.reviewData)?data.reviewWidget.reviewData.totalReviews:0;
    var totalSchema = '';
    var maxCount  = 4;

    for(var index=0;index<totalCount;index++){
        if(index < maxCount && reviewObj[index]){
            var description = reviewObj[index].placementDescription +"\n"+ reviewObj[index].infraDescription+ "\n"+ reviewObj[index].facultyDescription+ "\n"+ reviewObj[index].reviewDescription;
            var userName = (reviewObj[index].reviewerDetails)?reviewObj[index].reviewerDetails.userName:'';
            var averageRating = reviewObj[index].averageRating;
            var title = reviewObj[index].reviewTitle;
            var reviewSchema = '';
            var perReviewSchema = '';
            reviewSchema = ',"reviewRating":{"@type":"Rating","ratingValue":'+averageRating+'},"author":{"@type":"Person","name":"'+userName+'"},"name":"'+title+'","reviewBody":"'+description+'","publisher":{"@type":"Organization","name":"Shiksha"}';
            perReviewSchema = '<script type="application/ld+json">{"@context":"http://schema.org/","@type":"Review","itemReviewed":{"@type":"CollegeOrUniversity","name":"'+name+'","url":"'+website_url+'"}'+reviewSchema+'}</script>';
            totalSchema += perReviewSchema;
        }

    }
    return totalSchema;
}


