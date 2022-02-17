import { Component,OnInit,Input,Output,OnChanges,EventEmitter,forwardRef,Provider } from '@angular/core';
import {Posting} from '../../../Common/Classes/Posting';
import { InstituteFacility,InstituteChildFacility } from '../Classes/InstituteEntities';
import { MapToIterable } from '../../../pipes/arraypipes.pipe';
import { MY_VALIDATORS } from '../../../reusables/Validators';
import { RegisterFormModelDirective } from '../../../reusables/registerForm';
import {REACTIVE_FORM_DIRECTIVES,NgForm} from "@angular/forms";
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';
import {MY_OBJ_VALIDATORS} from '../../ListingReusables/Validators/ObjectValidators';
import {ControlValueAccessor,NG_VALUE_ACCESSOR} from "@angular/forms";

export const FACILITIES_ALL_CONTROL_VALUE_ACCESSOR = new Provider(NG_VALUE_ACCESSOR,{useExisting:forwardRef(() => FacilitiesPostingComponent),multi:true});


@Component({
	selector : "facilities-posting",
	templateUrl: '/public/angular/app/Listing/InstitutePosting/Views/'+getHtmlVersion('facilitiesPosting.component.html'),
	directives:[MY_VALIDATORS,REACTIVE_FORM_DIRECTIVES, RegisterFormModelDirective],
	pipes:[MapToIterable],
    providers:[FACILITIES_ALL_CONTROL_VALUE_ACCESSOR]

})

export class FacilitiesPostingComponent extends Posting implements OnChanges,ControlValueAccessor{

	@Input() facilities;
    @Input() facilitiesData;
	@Input() instituteStaticData;
    @Input() errors; 
    @Input('form') form: NgForm;

    _onTouchedCallback: () => any = () => {};
    _onChangeCallback: (value: any) => void = (value) => {};

    writeValue(value : any){
        this.facilities = value;
    }

    registerOnChange(fn:any){
        this._onChangeCallback = fn;
    }

    registerOnTouched(fn:any){
        this._onTouchedCallback = fn;
    }

    @Output() toolTipEvent : EventEmitter<any> = new EventEmitter<any>();
    //activateFacilities = true;
    activateOthers = true;
    facChildError = [];
    fChildError = [];
    childIndex:number=-1;
    validatefacilityHostelFlag = 'valid';

    ngOnChanges(changes){
        for(let propName in changes){
            if(propName == 'instituteStaticData' && changes[propName]['currentValue']){
                this.createInstituteFacilities();
            }
            if(propName == 'instituteStaticData' || propName == 'facilitiesData'){
                if(this.instituteStaticData && this.facilitiesData){
                    this.fillInstituteFacilities();
					//setTimeout(() => { this.fillInstituteFacilities(); },0);
                }
            }
        }
    }

    fillInstituteFacilities(){
        let facilities = this.facilities;
        for(let facilityId in this.facilitiesData){
            facilities[facilityId]['is_present'] = this.facilitiesData[facilityId]['is_present'];
            facilities[facilityId]['description'] = this.facilitiesData[facilityId]['description'];

            for(let childId in this.facilitiesData[facilityId]['child_facilities']){
                let childFacility = facilities[facilityId]['child_facilities'][childId];
                childFacility['is_present'] = this.facilitiesData[facilityId]['child_facilities'][childId]['is_present'];
                childFacility['description'] = this.facilitiesData[facilityId]['child_facilities'][childId]['description'];
                this.facilitiesData[facilityId]['child_facilities'][childId]['other_fields'].forEach((val,index) => {
                    if(!index){
                        childFacility.removeOthers(index);
                    }
                    childFacility.addOthers(val);
                });
                let custom_fields = this.facilitiesData[facilityId]['child_facilities'][childId]['custom_fields'];
                custom_fields.forEach((val,index) => {
                    childFacility['custom_fields'].forEach((value)=>{
                        if(value['name'] == val['name']){
                            value['value'] = val['value'];
                        }
                    });
                });
                childFacility['values'] = this.facilitiesData[facilityId]['child_facilities'][childId]['values'];
            }
        }

        setTimeout(() => {},0);
    }

    createInstituteFacilities(){
        let facilitiesObjs = {};
        let facilities = this.instituteStaticData.facilities;
        for(let facilityId in facilities){
            let facility = facilities[facilityId];
            let temp = {};
            temp['id'] = facility['id'];
            temp['name'] = facility['name'];
            temp['display_type'] = facility['display_type'];
            facilitiesObjs[temp['id']] = new InstituteFacility(temp);
            for(let childId in facility['children']){
                let temp = {};
                let childfacility = facility['children'][childId];
                temp['id'] = childId;
                temp['name'] = childfacility['name'];
                temp['display_type'] = childfacility['display_type'];
                temp['custom_fields'] = [];
                for(let custom_field_id in childfacility['custom_fields']){
                    let custom_field = childfacility['custom_fields'][custom_field_id];
                    temp['custom_fields'].push({'name':custom_field['name'],'value':custom_field['value'],'id':custom_field['id']});
                }
                let childFacilityObj = new InstituteChildFacility(temp);
                if(childfacility['display_type'] == 'add_more'){
                    childFacilityObj.addOthers();
                }
                facilitiesObjs[facilityId]['child_facilities'][temp['id']] = childFacilityObj;
            }
        }
        this.facilities = facilitiesObjs;
        setTimeout(() => { /*this.facilities = JSON.parse(JSON.stringify(facilitiesObjs));*/ this._onChangeCallback(this.facilities); }, 0);
    }


    validateOntextChange(child,value,childIndex){
        let roomCount:number  = this.facilities[3]['child_facilities'][childIndex+4]['custom_fields'][0]['value'];
        let bedCount:number = this.facilities[3]['child_facilities'][childIndex+4]['custom_fields'][1]['value'];
        if(child == 'Number of Rooms'){
           if(parseInt(value)<=0){
               this.fChildError[childIndex] = 'should be greater than 0.'; 
               this.validatefacilityHostelFlag = '';         
           }else if(parseInt(value)>bedCount && bedCount>0){
               this.facChildError[childIndex] = 'no of beds should be greater than or equals to number of rooms.';
               this.validatefacilityHostelFlag = '';
           }else{
               this.fChildError[childIndex] = '';
               this.facChildError[childIndex] = '';
               this.validatefacilityHostelFlag = 'valid';
           }
           
        }else if(child == 'Number of beds'){
            if(parseInt(value)<roomCount && roomCount>0){
                 this.facChildError[childIndex] = 'no of beds should be greater than or equals to number of rooms.';
                 this.validatefacilityHostelFlag = '';
            }else{
                this.facChildError[childIndex] = '';
                this.validatefacilityHostelFlag = 'valid';
            }
        }           
    }

    addOthers(facilityKey,childFacilities){
        setTimeout(() => {
            console.log(this.facilities[facilityKey]['child_facilities'][childFacilities['id']]['other_fields']);;
            this.facilities[facilityKey]['child_facilities'][childFacilities['id']]['other_fields'].push({'value':''});
        },0);
    }

    changedetected(facilityKey,childFacilities){
        this.facilities[facilityKey]['child_facilities'][childFacilities['id']]['other_fields'] = this.facilities[facilityKey]['child_facilities'][childFacilities['id']]['other_fields'].filter(function(){return true;});
        setTimeout(() => {this._onChangeCallback(this.facilities);},0);
    }

    removeOthers(facilityKey,childFacilities,position,flag = true){
        this.facilities[facilityKey]['child_facilities'][childFacilities['id']]['other_fields'].splice(position,1);
        setTimeout(() =>{},0);
        this.changedetected(facilityKey,childFacilities);
 
    }
}
