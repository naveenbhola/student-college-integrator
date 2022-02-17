import { Component,OnInit,Input,ViewChild,OnChanges,Output,EventEmitter } from '@angular/core';
import {Posting} from '../../../Common/Classes/Posting';
import { MY_VALIDATORS } from '../../../reusables/Validators';
import { RegisterFormModelDirective } from '../../../reusables/registerForm';
import {REACTIVE_FORM_DIRECTIVES, FormGroup, FormControl, NgForm} from "@angular/forms";

import { InstitutePostingService } from '../../../services/institute-posting.service';
import { MapToIterable, SortArrayPipe,LocationObjectHierarchy } from '../../../pipes/arraypipes.pipe';
import {MODAL_DIRECTIVES, BS_VIEW_PROVIDERS} from 'ng2-bootstrap/ng2-bootstrap';
import {ModalDirective} from '../../../Common/Components/modal/modal.component';
import { ContactDetails } from '../Classes/InstituteEntities';
import { Location } from '../../../Common/Classes/Location';
import { LocalityModal } from '../../../reusables/locality-modal';
import { WarningModal } from '../../../reusables/warningModal';
import { InstituteLocationService } from '../../../services/institute-locations.service';
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';

@Component({
	selector : "institute-location-posting",
	templateUrl: '/public/angular/app/Listing/InstitutePosting/Views/'+getHtmlVersion('institute-location-posting.component.html'),
	directives:[MY_VALIDATORS,REACTIVE_FORM_DIRECTIVES, RegisterFormModelDirective,MODAL_DIRECTIVES,LocalityModal,WarningModal],
	pipes:[MapToIterable, SortArrayPipe,LocationObjectHierarchy],
    providers:[BS_VIEW_PROVIDERS]
})

export class InstituteLocationPostingComponent extends Posting implements OnInit,OnChanges{

	@Input() instituteObj; 
	
	@Input() locationData;

    @Output() toolTipEvent: EventEmitter<any> = new EventEmitter<any>(); 

	cityList = [];
    cityListMap = [];
    localities = [];
    localitiesMap = [];
    mainContactDetails = new ContactDetails();
    mainContactDetailsLocObj;
    institutePickList = new Location();
    activateLocation = true;
    cityForLocalityLayer;

	constructor(private institutePosting: InstitutePostingService,public locationService: InstituteLocationService){
		super();
	}

	ngOnInit(){

        this.locationService.handleMainLocationChanges$.subscribe(() => {
            this.rebindFormControl('location');
        });
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
                 for(var localities in this.locationData.locations[statesKey]['cities'][tempCityId]['localities']){
                     this.localitiesMap[localities] = this.locationData.locations[statesKey]['cities'][tempCityId]['localities'][localities].name;
                 }
             }
        }
    }

    updateCitiesByState(event) {
        this.institutePickList.state_id = event;
        var stateId = this.institutePickList.state_id;
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
        this.institutePickList.city_id = event;
        if(typeof this.cityListMap[event] !== 'undefined')
            this.institutePickList.state_id = this.cityListMap[event]['state_id'];
        this.institutePickList.locality_id = 0;
        this.localities = [];
        var cityId = this.institutePickList.city_id;
        var stateId = this.institutePickList.state_id;
        if((stateId != 0) && (cityId != 0)) {
            var localities = this.locationData.locations[stateId]['cities'][cityId]['localities'];
            if(localities != null || localities != 'undefined') {
                for(var locality in localities) {
                    this.localities.push({id:localities[locality]['id'], name:localities[locality]['name']})
                }
            }
        }
    }

    showModal(contactDetailsObj){
    	this.mainContactDetails = JSON.parse(JSON.stringify(contactDetailsObj.contact_details));
        //this.mainContactDetails = contactDetailsObj.contact_details;
        this.mainContactDetailsLocObj = contactDetailsObj;
    	this.showInstituteLocationModal();
    }

    modalContent;
    isModalOpened:boolean = false;
    modalTitle;
    selectedLocalities = [];
    previousSelectedLocalities = [];
    showLocalityLayer(cityId){
        this.selectedLocalities = [];
        this.cityForLocalityLayer = cityId;
        this.isModalOpened = true;
        this.modalTitle = this.cityListMap[cityId]+" Localities";
        this.modalContent = this.cityListMap[cityId]['localities'];

        for(let objIndex in this.instituteObj.locations){
            this.selectedLocalities.push(this.instituteObj.locations[objIndex].locality_id);
        }

        for(let obj in this.modalContent){
            if((this.selectedLocalities.indexOf(parseInt(obj)) !== -1 || this.selectedLocalities.indexOf(obj) !== -1) || obj == this.instituteObj.main_location.locality_id){
                this.modalContent[obj].state = true;
                this.modalContent[obj].disabled = true;
                this.previousSelectedLocalities.push(obj);
            }
            else{
                this.modalContent[obj].state = false;
                this.modalContent[obj].disabled = false;
            }
        }
    }

    modalHideResponse(){
        this.isModalOpened = false;
    }

    modalResponse(res){

        let currentSelectedLocalities = [];
        this.isModalOpened = false;
        let tempLocalities = [];
        for(let obj in res){
            if(res[obj].state == true && obj !== this.instituteObj.main_location.locality_id){
                tempLocalities.push(obj);
                currentSelectedLocalities.push(res[obj].id);
            }
        }

        let key;
        for(let i=0; i< tempLocalities.length; i++){
            key = this.cityForLocalityLayer+"_"+tempLocalities[i];
            if(!this.instituteObj.checkIfLocationExists(this.cityForLocalityLayer, tempLocalities[i])){
                let locArray = {};
                locArray['state_id'] = this.cityListMap[this.cityForLocalityLayer]['state_id'];
                locArray['city_id'] = this.cityForLocalityLayer;
                locArray['city_name'] = this.cityListMap[this.cityForLocalityLayer].name;
                locArray['locality_id'] = tempLocalities[i];
                locArray['locality_name'] = this.localitiesMap[tempLocalities[i]];
                this.instituteObj.addLocation(locArray);
            }
        }

        for(let i=0; i< this.previousSelectedLocalities.length; i++){
            if(currentSelectedLocalities.indexOf(this.previousSelectedLocalities[i]) === -1 ){
                this.instituteObj.removeLocation(this.cityForLocalityLayer, this.previousSelectedLocalities[i]);
            }
        }

        this.rebindFormControl('location');
        this.isModalOpened = false;
        this.locationService.pushLocationChange({});
    }

    @ViewChild('instituteLocModal') public instituteLocModal: ModalDirective;
    public showInstituteLocationModal():void {
      this.instituteLocModal.show();
    }

    public hideInstituteLocationModal():void {
      this.instituteLocModal.hide();
    }

    updateLocationContactData(event){

        let lat = this.mainContactDetails.latitude; 
        let long = this.mainContactDetails.longitude; 
        if(lat == '' && long != ''){
            alert("Latitude can't be empty");
            return false;
        }
        else if(long == '' && lat != ''){
            alert("Longitude can't be empty");
            return false;
        }
    	let tempLocationIndex = this.mainContactDetailsLocObj.city_id+"_"+this.mainContactDetailsLocObj.locality_id;
        
        this.mainContactDetailsLocObj.contact_details = JSON.parse(JSON.stringify(this.mainContactDetails));
        if(this.mainContactDetails.copy_toall_flag){
            // copy to all ordinary locations
            for(let objIndex in this.instituteObj.locations){
                if(tempLocationIndex != objIndex){
                    this.copyContactDetails(this.instituteObj.locations[objIndex]);
                }
            }

            // copy to main location
            let mainLocationIndex = this.instituteObj.main_location.city_id+"_"+this.instituteObj.main_location.state_id;
            if(tempLocationIndex != mainLocationIndex){
                this.copyContactDetails(this.instituteObj.main_location);
            }
            this.mainContactDetailsLocObj.contact_details.copy_toall_flag = false;
        }

        this.hideInstituteLocationModal();
    }

    copyContactDetails(locationObj){

        let tempContactDetails;
        tempContactDetails = JSON.parse(JSON.stringify(this.mainContactDetails));
        tempContactDetails.copy_toall_flag = false;
        locationObj.contact_details = tempContactDetails;
    }

    removeInstLocation(cityId, localityId){
        this.instituteObj.removeLocation(cityId, localityId);
        this.rebindFormControl('location');
        this.locationService.pushLocationChange({});
    }

    addNewLocation(){
        if(!this.institutePickList.city_id)
            return;

        if(this.instituteObj.checkIfLocationExists(this.institutePickList.city_id, this.institutePickList.locality_id)){
            alert("This location has already been Added!!!");
            return;
        }

        let locArray = {};
        locArray['state_id'] = this.cityListMap[this.institutePickList.city_id]['state_id'];
        locArray['city_id'] = this.institutePickList.city_id;
        locArray['city_name'] = this.cityListMap[this.institutePickList.city_id].name;
        locArray['locality_id'] = this.institutePickList.locality_id;
        locArray['locality_name'] = this.localitiesMap[this.institutePickList.locality_id];
        this.instituteObj.addLocation(locArray);
        this.rebindFormControl('location');
        this.institutePickList.state_id = 0;
        this.institutePickList.city_id = 0;
        this.processStaticData();
        this.locationService.pushLocationChange({});
    }

    removeAllLocalities(cityId){
        this.instituteObj.removeLocationByCity(cityId);
        this.rebindFormControl('location');
    }
    
    confirmModalContent;
    isConfirmModalOpened:boolean = false;
    confirmModalCity = '';
    confirmModalLocality = '';
    confirmModalType;
    showConfirmModal(cityId = '', localityId = '', type='deleteall'){
        this.isConfirmModalOpened = true;
        if(type=='deleteall')
            this.confirmModalContent = 'Are you sure you want to remove all localities of this city ?';
        else
            this.confirmModalContent = 'Are you sure you want to remove this item ?';
        this.confirmModalCity = cityId;
        this.confirmModalLocality = localityId;
        this.confirmModalType = type;
    }

    confirmModalResponse(res){
      if(res == 'yes'){
            let locationIds = [];
            if(this.confirmModalType == 'deleteall'){    
                for (var key in this.instituteObj.locations) {
                  if (this.instituteObj.locations.hasOwnProperty(key) && this.instituteObj.locations[key].city_id == this.confirmModalCity) {
                      if(typeof this.instituteObj.locations[key].institute_location_id !== 'undefined')
                          locationIds.push(this.instituteObj.locations[key].institute_location_id);
                  }
                }
                this.deleteLocations(locationIds);
            }
            else if(this.confirmModalType == 'delete' || this.confirmModalType == 'remove_locality'){
                let key = this.confirmModalCity+"_"+this.confirmModalLocality;
                if(typeof this.instituteObj.locations[key].institute_location_id !== 'undefined')
                    locationIds.push(this.instituteObj.locations[key].institute_location_id);
                this.deleteLocations(locationIds);
            }
        
      }else{
        this.isConfirmModalOpened = false;
        this.confirmModalCity = '';
        this.confirmModalLocality = '';
      }
    }

    deleteLocations(locationIds){
        this.institutePosting.checkLocationMappedToCourse(locationIds).subscribe(
            data => { 
                if(data.mapped == true){
                    alert("This location is mapped to a course. You cannot delete it.");
                    this.isConfirmModalOpened = false;
                }
                else{
                    if(this.confirmModalType == 'deleteall'){
                        this.instituteObj.removeLocationByCity(this.confirmModalCity);
                    }
                    else if(this.confirmModalType == 'delete'){
                        this.instituteObj.removeLocationByCity(this.confirmModalCity, this.confirmModalLocality);
                    }
                    else if(this.confirmModalType == 'remove_locality'){
                        this.removeInstLocation(this.confirmModalCity, this.confirmModalLocality);
                    }
                    this.rebindFormControl('location');
                    this.isConfirmModalOpened = false;
                    this.locationService.pushLocationChange({});
                }
            },
             error => {console.log('Failed to validate location deletion');}
        );
    }

}
