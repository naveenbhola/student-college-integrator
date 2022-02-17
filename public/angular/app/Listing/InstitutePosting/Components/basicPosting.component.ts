import { Component,OnInit,Input,OnChanges ,Output,EventEmitter} from '@angular/core';
import {Posting} from '../../../Common/Classes/Posting';
import { MY_VALIDATORS } from '../../../reusables/Validators';
import { RegisterFormModelDirective } from '../../../reusables/registerForm';
import {REACTIVE_FORM_DIRECTIVES, FormGroup, FormControl, NgForm} from "@angular/forms";

import { InstitutePostingService } from '../../../services/institute-posting.service';
import { MapToIterable, SortArrayPipe } from '../../../pipes/arraypipes.pipe';
import { InstituteLocationService } from '../../../services/institute-locations.service';
import {ContactDetails} from '../Classes/InstituteEntities';
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';

@Component({
	selector : "basic-posting",
	templateUrl: '/public/angular/app/Listing/InstitutePosting/Views/'+getHtmlVersion('basicposting.component.html'),
	directives:[MY_VALIDATORS,REACTIVE_FORM_DIRECTIVES, RegisterFormModelDirective],
	providers:[InstitutePostingService],
	pipes:[MapToIterable, SortArrayPipe]
})

export class BasicPostingComponent extends Posting implements OnInit,OnChanges{

	@Input() instituteObj;
    @Input() instituteStaticData; 
	@Input() mode; 
    @Input('form') form: NgForm;

	@Input() locationData;
    @Output() toolTipEvent: EventEmitter<any> = new EventEmitter<any>();
    cityList = [];
    cityListMap = [];
    localities = [];
    currentYear;
    activateSynonym = true;      
    previousMainLocation;
    changedFlag : boolean = true;
	constructor(private institutePosting: InstitutePostingService,public locationService: InstituteLocationService){
		super();
	}

	ngOnInit(){
        if(this.mode == 'add'){
            this.instituteObj.addSynonym();
        }
        else{
             let cityId = this.instituteObj.main_location.city_id;
             let localityId = this.instituteObj.main_location.locality_id;
            this.updateCitiesByState(this.instituteObj.main_location.state_id);
            this.instituteObj.main_location.city_id = cityId;
            this.cityChanged(this.instituteObj.main_location.city_id,this.instituteObj.main_location.city_id,true);
            this.instituteObj.main_location.locality_id = localityId;
        }
        let d = new Date();
        this.currentYear = d.getFullYear();
      this.previousMainLocation = JSON.parse(JSON.stringify(this.instituteObj.main_location));
	}

    ngOnChanges(changes){
        for(let propName in changes){
            if(propName == 'locationData' && changes['locationData']['currentValue']){
                this.locationData = changes['locationData']['currentValue'];this.processStaticData();
            }
        }
    }

   	processStaticData() {
        this.cityList = [];
        for (var statesKey in this.locationData.locations) {
             for(var cities in this.locationData.locations[statesKey]['cities']) {
                 let tempCityId = this.locationData.locations[statesKey]['cities'][cities]['id'];
                 let tempCityName = this.locationData.locations[statesKey]['cities'][cities]['name'];

                 this.cityList.push({id: tempCityId, name: tempCityName});
                 this.cityListMap[tempCityId] = [];
                 this.cityListMap[tempCityId]['name'] = tempCityName;
                 this.cityListMap[tempCityId]['state_id'] = statesKey;
                 this.cityListMap[tempCityId]['localities'] = this.locationData.locations[statesKey]['cities'][tempCityId]['localities'];
             }
        }       
    }

    updateCitiesByState(event) {
        this.instituteObj.main_location.state_id = event;
        var stateId = this.instituteObj.main_location.state_id;
        if(stateId == 0 || stateId == null) {
            this.processStaticData();
        }
        else {
            this.cityList = [];
            for(var cities in this.locationData.locations[stateId]['cities']) {
                this.cityList.push({id: this.locationData.locations[stateId]['cities'][cities]['id'], name: this.locationData.locations[stateId]['cities'][cities]['name']});
            }
        }
        this.updateLocalitiesByCity(0);
    }

    updateLocalitiesByCity(event) {
        this.instituteObj.main_location.city_id = event;
        if(typeof this.cityListMap[event] !== 'undefined')
        	this.instituteObj.main_location.state_id = this.cityListMap[event]['state_id'];
        this.instituteObj.main_location.locality_id = 0;
        this.localities = [];
        var cityId = this.instituteObj.main_location.city_id;
        var stateId = this.instituteObj.main_location.state_id;
        if((stateId != 0) && (cityId != 0)) {
            var localities = this.locationData.locations[stateId]['cities'][cityId]['localities'];
            if(localities != null || localities != 'undefined') {
                for(var locality in localities) {
                    this.localities.push({id:localities[locality]['id'], name:localities[locality]['name']})
                }
            }
        }
    }

    stateChanged(event){ 
          if(this.instituteObj.postingListingType == 'University')
          {
                this.checkLocationMappedToCourse('state',event,'','','');
          }
          else
          {
            if(this.mode != 'add' && event != this.instituteObj.main_location.state_id) {
                this.checkLocationMappedToCourse('state',event,'','','');
            }
            else {
              this.stateChangedEvent(event)
          }
        }
        
      } 
      stateChangedEvent(event)
      {
          this.previousMainLocation = JSON.parse(JSON.stringify(this.instituteObj.main_location)); 
          this.updateCitiesByState(event); 
      }

    //last parameter used for identifying calling from ngOninit or city selection change 
    cityChanged(event, cityId,initCall = false){
        if(this.instituteObj.postingListingType == 'University' && !initCall)
        {
            this.checkLocationMappedToCourse('city',event,'',cityId, '');
        }
        else{
          if(this.mode != 'add' && event != this.instituteObj.main_location.city_id) {
              this.checkLocationMappedToCourse('city',event,'',this.instituteObj.main_location.city_id, '');
          }
          else {
            this.cityChangedEvent(event, cityId);
          }
        }
        
    }
    cityChangedEvent(event, cityId)
    {
        this.previousMainLocation = JSON.parse(JSON.stringify(this.instituteObj.main_location));
        this.updateLocalitiesByCity(event);

        if(this.instituteObj.postingListingType == 'University'){
          this.rebindFormControl('location');
          this.locationService.pushMainLocationChange({});
        }
    }

    localityChanged(event, cityId, localityId){
        if(this.instituteObj.postingListingType == 'University')
        {
            this.checkLocationMappedToCourse('locality',event,'',cityId, localityId);
        }
        else{
          if(this.mode != 'add' && event != this.instituteObj.main_location.locality_id) {
            this.checkLocationMappedToCourse('locality',event,'',cityId, localityId);
          } else {
            this.localityChangedEvent(event, cityId, localityId);
          }
        }
    }
    localityChangedEvent(event, cityId, localityId)
    {
        this.previousMainLocation = JSON.parse(JSON.stringify(this.instituteObj.main_location));
        this.instituteObj.main_location.locality_id = event;
        
        this.updateInstituteMainLocality(cityId, event);
    }
    updateInstituteMainLocality(cityId, localityId, moveLocation = true){
        if(this.instituteObj.postingListingType != 'University')
        {
            this.instituteObj.main_location.institute_location_id = '';
            let moved = true;
            if(this.instituteObj.postingListingType != 'University')
            {
                if(moveLocation){
                    moved = this.instituteObj.moveLocationToMainLocation(cityId, localityId);
                }

                if(!moved || !moveLocation){
                    if(typeof this.previousMainLocation !== 'undefined' && (cityId != this.previousMainLocation.city_id || localityId!=0) && (this.previousMainLocation.institute_location_id != '' && typeof(this.previousMainLocation.institute_location_id) !== 'undefined'))
                        this.instituteObj.moveMainLocationToLocation(this.previousMainLocation);
                }
            }
            else{
                    this.instituteObj.main_location.contact_details = new ContactDetails;
            }

            
        }
        this.rebindFormControl('location');
        this.locationService.pushMainLocationChange({});
    }
    checkLocationMappedToCourse(sourceChange,event,stateId,cityId, localityId)
    {
        let locationIds = [];
        if(typeof this.instituteObj.main_location.institute_location_id !== 'undefined' && this.instituteObj.main_location.institute_location_id != "") {
               locationIds.push(this.instituteObj.main_location.institute_location_id);
        }
        else if( typeof this.previousMainLocation.institute_location_id !== 'undefined') {
               locationIds.push(this.previousMainLocation.institute_location_id);
        }

        this.institutePosting.checkLocationMappedToCourse(locationIds).subscribe(
            data => { 
                if(data.mapped == true){
                        alert("This location is mapped to a course. You cannot delete it.");
                        this.changeDetected();
                    }
                   else if(sourceChange == 'state'){
                       this.stateChangedEvent(event);
                       this.updateInstituteMainLocality(this.instituteObj.main_location.city_id,this.instituteObj.main_location.locality_id, false);
                   }
                   else if(sourceChange == 'city')
                   {
                       this.cityChangedEvent(event,cityId);
                       this.updateInstituteMainLocality(this.instituteObj.main_location.city_id,this.instituteObj.main_location.locality_id);
                   }
                   else{
                       this.localityChangedEvent(event,cityId,localityId);
                   }
                  }
                );
    }
    changeDetected()
    {
        this.changedFlag = false;
        setTimeout(() => {this.changedFlag = true;},0);
    }
}
