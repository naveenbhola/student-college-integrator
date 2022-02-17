import { Component,OnInit,OnChanges,Input,Output,EventEmitter } from '@angular/core';
import { FileUploadService } from '../../../services/file-upload.service';
import { Posting } from '../../../Common/Classes/Posting';
import { SortArrayPipe } from '../../../pipes/arraypipes.pipe';
import { RegisterFormModelDirective } from '../../../reusables/registerForm';
import {REACTIVE_FORM_DIRECTIVES,NgForm,FormControl} from "@angular/forms";
import { MY_VALIDATORS } from '../../../reusables/Validators';

import { UploadMedia } from '../../../reusables/uploadMedia';
import { WarningModal } from '../../../reusables/warningModal';

import { Http, Response, Headers, RequestOptions } from '@angular/http';

import { Observable }     from 'rxjs/Observable';
import { InstituteLocationService } from '../../../services/institute-locations.service';
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';


@Component({
	selector : "media-posting",
	templateUrl: '/public/angular/app/Listing/InstitutePosting/Views/'+getHtmlVersion('mediaposting.component.html'),
	styles: ['.or-span {border-radius: 50%;padding: 5px 4px;margin-right: 12px;background-color: #ECF0F1;}'],
	providers:[FileUploadService],
	directives:[UploadMedia,WarningModal,RegisterFormModelDirective,REACTIVE_FORM_DIRECTIVES,MY_VALIDATORS],
  pipes:[SortArrayPipe]
})


export class MediaPostingComponent extends Posting implements OnInit,OnChanges{

    @Input() instituteObj;
    @Input() label= '';
    @Input() locationData;
    @Input() showLogoWidget = false;
    @Input() instituteLocationObj;
    @Input('form') form: NgForm;
  	checkedFlag = true;
    @Input() submitPending;

    @Output() toolTipEvent: EventEmitter<any> = new EventEmitter<any>();
    @Output() fireSaveInstitute : EventEmitter<any> = new EventEmitter<any>();

    locationList = [];
    media_type;
    locations = [];
    tags = [];
    resetMediaWidget = true;
    mediaTitle = '';
    mediaUrl='';
    state;
    allLocationsFlag = true;
    disabledTitle="";
    institutePhotoErrMsg = '';
    temp_photo_data;
    temp_video_data;
    instituteVideoErrMsg = '';
    uploadArr = [];
    checkValidYoutubeResponse;
    modalContent;
    isModalOpened:boolean = false;
    modalEle:number;
    showLoader = 0;
    cityLocalityNames=[];
    tags_list = [];
    tags_events_list = [];
    videoSelectionError = '';
    photoSelectionError = '';
    numErrorPhoto = [];
    numErrorVideo = [];
    changedFlag : boolean = true;

	constructor(public fileUploadService: FileUploadService,private http:Http,public locationService: InstituteLocationService){
    	super();
  	}


    @Input() set mediaType(type){
        if(type == 'Photo'){
            this.media_type = 'photos';
        }
        else if(type == 'Video'){
            this.media_type = 'videos';
        }
    }

    @Input() set tagList(type){
        if(typeof(type) !== undefined && type != null){
          this.tags_list = JSON.parse(JSON.stringify(type));
          this.populateTagList();
        }
    }

    populateTagList(){
          this.tags_events_list = JSON.parse(JSON.stringify(this.tags_list));;
          for(var index in this.instituteObj.events){
            if(typeof this.instituteObj.events[index].name != 'undefined' && this.instituteObj.events[index].name != '')
              this.tags_events_list.push({'label':"Event : "+this.instituteObj.events[index].name, 'value':this.instituteObj.events[index].randomIdentifier});
          }
          this.reCheckMediaTagsSelection('photos');
          this.reCheckMediaTagsSelection('videos');
          this.changeDetected();
    }

    get mediaType(){
        if(this.media_type == 'photos'){
            return 'Photo';
        }
        else if(this.media_type == 'videos'){
            return 'Video';
        }
    }

    processStaticData(){
    	let key;
    	for (var statesKey in this.locationData.locations) {
             for(var cities in this.locationData.locations[statesKey]['cities']) {
                 let tempCityId = this.locationData.locations[statesKey]['cities'][cities]['id'];
                 let tempCityName = this.locationData.locations[statesKey]['cities'][cities]['name'];

                 key = tempCityId+"_0";
                 this.cityLocalityNames[key] = tempCityName;
                 for(var localities in this.locationData.locations[statesKey]['cities'][tempCityId]['localities']){
                 	key = tempCityId+"_"+localities;
                 	this.cityLocalityNames[key] = tempCityName+" - "+this.locationData.locations[statesKey]['cities'][tempCityId]['localities'][localities].name;
                 }
             }
        }
    }

    ngOnInit(){

    	this.locationService.handleMainLocationChanges$.subscribe(() => {
			this.rePopulateMediaLocationList();
        });

        this.locationService.handleLocationChanges$.subscribe(() => {
        	this.rePopulateMediaLocationList();
        });

        this.locationService.handleMediaLocationSelection$.subscribe(() => {
          this.reValidateLocationSelection();
        })
		
		  if(this.instituteObj.postingListingType == 'University')
        {
          this.allLocationsFlag = false;
        }

        this.locationService.handleEventChange$.subscribe(() => {
            this.populateTagList();
        });

        if(this.allLocationsFlag){
        	this.disabledTitle = "All Locations Selected";
        }
	}

	populateLocationList(){
		this.rePopulateMediaLocationList();
        
	}
	locationListSelected;
	rePopulateMediaLocationList(){
		let locationObj,locationListObj;
	    	//this.locationList = [{'value':'0_0','label':'Select All Locations','uncheckAllOnSelect':true}];
	    	this.locationList = [];
	    	for(let objIndex in this.instituteObj.locations){
	            	locationObj = this.instituteObj.locations[objIndex];
	            	locationListObj = [];
	            	locationListObj['value'] = locationObj['city_id']+"_"+locationObj['locality_id'];
	            	locationListObj['label'] = this.cityLocalityNames[locationListObj['value']];
	                this.locationList.push(locationListObj);
	        }
	        if(this.instituteObj.main_location.city_id != 0){
	        		locationObj = this.instituteObj.main_location;
	        		locationListObj = [];
	            	locationListObj['value'] = locationObj['city_id']+"_"+locationObj['locality_id'];
	            	locationListObj['label'] = this.cityLocalityNames[locationListObj['value']];
	            	this.locationList.push(locationListObj);
	        }
       this.reCheckMediaLocationSelection('videos');
       this.reCheckMediaLocationSelection('photos');
       this.isChecked();
	}

    reCheckMediaLocationSelection(mediaType)
    {
         if(this.instituteObj[mediaType].length > 0)
        {
            for(let i in this.instituteObj[mediaType])
            {
                for(let k in this.instituteObj[mediaType][i]['locations'])
                {
                  let removeFlag = false;
                  for(let m in this.locationList)
                  {
                    if(this.locationList[m].value !== this.instituteObj[mediaType][i]['locations'][k])  
                        removeFlag = true;
                     else
                     {
                        removeFlag = false;
                        break;
                     }
                  }
    //              if(this.locationList.indexOf(this.instituteObj['photos'][0]['locations'][k]) > -1)
                  if(removeFlag)
                  {
                    this.instituteObj[mediaType][i]['locations'].splice(k,1);
                    this.isChecked();
                  }
                }    
            }
        }
    }

    reCheckMediaTagsSelection(mediaType)
    {
      if(this.instituteObj[mediaType].length > 0)
        {
            for(let i in this.instituteObj[mediaType])
            {
         for(let k in this.instituteObj[mediaType][i]['tags'])
                {
                    let removeFlag = false;
                    for(let m in this.tags_events_list)
                    {
                      if(this.tags_events_list[m].value !== this.instituteObj[mediaType][i]['tags'][k])  
                          removeFlag = true;
                       else
                       {
                          removeFlag = false;
                          break;
                       }
                    }
                  if(removeFlag)
                  {
                    this.instituteObj[mediaType][i]['tags'].splice(k,1);
                    this.changeDetected();
                  }

                } 
              }
         }
    }

	ngOnChanges(changes){
        for(let propName in changes){
            if(propName == 'locationData' && changes['locationData']['currentValue']){
                this.locationData = changes['locationData']['currentValue'];
                this.processStaticData(); 
                this.populateLocationList();
            }
        }
    }

    checkValidYoutubeUrl(youtubeUrl, postUrl){
      let body = 'url='+youtubeUrl;
      let headers = new Headers({ 'Content-Type': 'application/x-www-form-urlencoded' });
      let options = new RequestOptions({ headers: headers });

      return this.http.post(postUrl,body, options).map(res => res.json()).catch(this.handleError);
    }

    extractData(res: Response) {
      let body = res.json();
      return body.data || {};
    }

    handleError(error: any) {
      // In a real world app, we might use a remote logging infrastructure
      // We'd also dig deeper into the error to get a better message
      let errMsg = (error.message) ? error.message :
        error.status ? `${error.status} - ${error.statusText}` : 'Server error';
      console.error(errMsg); // log to console instead
      return Observable.throw(errMsg);
    }

    addMedia(type,mediaTitle,mediaUrl=''){
        if(type == 'photos'){
            this.showLoader = 1;
            this.addPhoto(type,mediaTitle);   
            this.showLoader = 0;
            if(this.instituteObj.postingListingType == 'University')
            {
              this.allLocationsFlag = false;
            }    
        }else if(type == 'videos'){
            this.showLoader = 1;
            if(mediaUrl){
              this.checkValidYoutubeUrl(mediaUrl, 'nationalInstitute/InstitutePosting/uploadInstituteMedia/videos').subscribe(
                res => { 
                    if(res.data.error && res.data.error.msg != '') {
                      this.instituteVideoErrMsg = res.data.error.msg;
                    }
                    else {
                        this.instituteVideoErrMsg = '';
                        this.temp_video_data = res.data.media;
                        this.addVideo(type,mediaTitle,mediaUrl);
                    }
                    this.showLoader = 0;
                },
                  error => {alert('Failed to get youtube response');}
              );  
            }else{
              this.instituteVideoErrMsg = "Oops, you haven't entered youtube url";
              this.showLoader = 0;
            }
            
        }          
    }

    removeMedia(index){
        if(this.media_type == 'photos'){
          this.instituteObj.removePhoto(index);   
        }else if(this.media_type == 'videos'){
          this.instituteObj.removeVideo(index);   
        }

        this.isModalOpened = false;
        this.modalEle = null;
    }

    changeMediaPosition(currentPosition,newPosition){
        if(currentPosition < newPosition){
          for(let media of this.instituteObj[this.media_type]){
            if(media.position > currentPosition && media.position <= newPosition){
              --media.position;
            }
          }
          this.instituteObj[this.media_type][currentPosition-1]['position'] = newPosition;  
        }
        else{
          for(let media of this.instituteObj[this.media_type]){
            if(media.position >= newPosition && media.position < currentPosition){
              ++media.position;
            }
          }
          this.instituteObj[this.media_type][currentPosition-1]['position'] = newPosition;
        }

        this.instituteObj[this.media_type].sort((a,b) => {
          if(a['position']<b['position']){return -1}
          if(a['position']>b['position']){return 1}
          return 0;
        });
    }

    removeMediaAll(){
        if(this.media_type == 'photos'){
          this.instituteObj.photos = [];
        }else if(this.media_type == 'videos'){
          this.instituteObj.videos = [];
        }
        this.isModalOpened = false;
        this.modalEle = null; 
    }

    addPhoto(type,mediaTitle){
        if(typeof this.temp_photo_data != 'undefined' && Object.keys(this.temp_photo_data).length && mediaTitle && (this.allLocationsFlag || this.locations.length > 0)){
          this.institutePhotoErrMsg = '';
          this.instituteObj.addPhoto({
                                      title:mediaTitle,
                                      type:type,
                                      locations:this.locations,
                                      tags:this.tags,
                                      media_id:this.temp_photo_data.media_id,
                                      media_url:this.temp_photo_data.media_url,
                                      media_thumb_url:this.temp_photo_data.media_thumb_url,
                                      position:this.instituteObj.photos.length+1,
                                      all_locations_flag:this.allLocationsFlag
                                    });

          this.resetMediaWidget = false;
          this.temp_photo_data = [];
          this.locations = [];
          this.allLocationsFlag = true;
          this.disabledTitle = "All Locations Selected";
          this.tags = [];
          this.mediaTitle='';
          
          (<FormControl>this.form.form.controls['Photo_mediaTitle']).updateValue('');

          setTimeout(() => {this.resetMediaWidget = true;},0);
        }else{
          if(!mediaTitle){
            this.institutePhotoErrMsg= "Oops, you haven't entered title";
          }
          else if(!this.allLocationsFlag && this.locations.length == 0){
          	this.institutePhotoErrMsg= "Oops, you haven't selected any location";	
          }
          else{
            this.institutePhotoErrMsg= "Oops, you haven't selected any photo";  
          }
        }
    }

    addVideo(type,mediaTitle,mediaUrl){
        if(typeof this.temp_video_data != 'undefined' && Object.keys(this.temp_video_data).length && mediaTitle && (this.allLocationsFlag || this.locations.length > 0)){
          this.instituteVideoErrMsg = '';
          this.instituteObj.addVideo({
                                      title:mediaTitle,
                                      type:type,
                                      locations:this.locations,
                                      tags:this.tags,
                                      media_id:this.temp_video_data.media_id,
                                      media_url:this.temp_video_data.media_url,
                                      media_thumb_url:this.temp_video_data.media_thumb_url,
                                      position:this.instituteObj.videos.length+1,
                                      all_locations_flag:this.allLocationsFlag
                                    });

          this.resetMediaWidget = false;
          this.temp_video_data = [];
          this.locations = [];
          if(this.instituteObj.postingListingType == 'University')
              this.allLocationsFlag = false;
          else
              this.allLocationsFlag = true;
          this.disabledTitle = "All Locations Selected";
          this.tags = [];
          this.mediaTitle='';
          this.mediaUrl='';
          (<FormControl>this.form.form.controls['Video_mediaTitle']).updateValue('');
          setTimeout(() => {this.resetMediaWidget = true;},0);
        }else if(this.instituteVideoErrMsg == ''){
          if(!mediaTitle){
            this.instituteVideoErrMsg= "Oops, you haven't entered title";
          }
          else if(!this.allLocationsFlag && this.locations.length == 0){
          	this.instituteVideoErrMsg= "Oops, you haven't selected any location";	
          }
          else{
            this.instituteVideoErrMsg= "Oops, you haven't selected any video";
          }          
        } 
    }


    uploadLogoCallback(response) {
        if(response == "start"){
          return;
        }
       if(response.logoData.length > 0) {
           this.instituteObj.logo_url = response.logoData;
       }
       else{
         this.instituteObj.logo_url = '';
       }     
    }

    clearUploadLogo(){
        this.instituteObj.logo_url = '';
        this.showLogoWidget = false;
        setTimeout(()=>{this.showLogoWidget= true;},0);
    }

     
    uploadPhotoCallback(response) {
        if(response == "start"){
          return;
        }
        if(response.hasOwnProperty('media')) {
            this.temp_photo_data = response.media;
            this.institutePhotoErrMsg = '';
        }
        else
        {
          this.temp_photo_data = '';
        }
    }


    setSelectedValues($event,index = null,mediaType = null){      
      if(index != null && mediaType != null){
        this.instituteObj[mediaType][index][$event.type] = $event.selected;
      }else{
        this[$event.type]= $event.selected;                
      }
    }

    mediaLocationChanged($event){

    	if($event == '0_0'){

    	}
    }

    showMediaModal(elementId = -1){
        this.isModalOpened = true;
        if(elementId >=0){
          this.modalContent = 'Are you sure you want to remove this '+this.mediaType+'?';
          this.modalEle = elementId;
        } else {
          this.modalContent = 'Are you sure you want to remove all '+this.mediaType+'s?';
          this.modalEle = -1;
        }
    }

    modalResponse(res){
        if(res == 'yes' && this.modalEle >= 0){
          this.removeMedia(this.modalEle);
        }else if(res == 'yes' && this.modalEle == -1){
          this.removeMediaAll();
        }else{
          this.isModalOpened = false;
          this.modalEle = null;
        }
    }

    allLocationFlagChanged(event){
    	if(!this.allLocationsFlag){
    		this.disabledTitle = "All Locations Selected";
    	}
    	else{
    		this.disabledTitle = "";
    	}
    }
    isChecked()
    {
      this.checkedFlag = false;
      setTimeout(() => {this.checkedFlag = true},0);
    }
    changeDetected()
    {
       this.changedFlag = false;
       setTimeout(() => {this.changedFlag = true},0);
    }
    reValidateLocationSelection()
    {
         
        this.numErrorPhoto = [];
        this.numErrorVideo = [];
        if(this.instituteObj['photos'])
        {
          for(let i in this.instituteObj['photos'])
          {
            if(this.instituteObj['photos'][i]['locations'].length == 0 && !this.instituteObj['photos'][i]['all_locations_flag'])
            {
                let j = (+i)+1;
                this.numErrorPhoto.push('photo'+j);
            }
          }
          if(this.numErrorPhoto.length > 0)
          {
            this.photoSelectionError = 'For '+this.numErrorPhoto.join(',')+" you haven't selected any location";
          }
          else
          {
             this.photoSelectionError =''; 
          }
        }
        if(this.instituteObj['videos'])
        {
           for(let i in this.instituteObj['videos'])
          {
            if(this.instituteObj['videos'][i]['locations'].length == 0 && !this.instituteObj['videos'][i]['all_locations_flag'])
            {
                let j = (+i)+1;
                this.numErrorVideo.push('video'+j);
            }
          }
          if(this.numErrorVideo.length > 0)
          {
            this.videoSelectionError = 'For '+this.numErrorVideo.join(',')+" you haven't selected any location";
          }
          else
          {
             this.videoSelectionError =''; 
          } 
        }
        if(!this.instituteObj['photos'] && !this.instituteObj['videos'])
        {
          this.photoSelectionError = '';
          this.videoSelectionError = ''; 
        }
        if(this.photoSelectionError == '' && this.videoSelectionError =='')
          this.fireSaveInstitute.emit({'val':false})
        else
          this.fireSaveInstitute.emit({'val':true})
    }
}
