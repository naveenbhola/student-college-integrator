import { Component,OnInit,Input,Provider,forwardRef,Output,EventEmitter } from '@angular/core';
import {Posting} from '../../../Common/Classes/Posting';
import { MY_VALIDATORS } from '../../../reusables/Validators';
import { AcademicStaff } from '../Classes/InstituteEntities';
import { RegisterFormModelDirective } from '../../../reusables/registerForm';
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';


import {REACTIVE_FORM_DIRECTIVES,ControlValueAccessor,NG_VALUE_ACCESSOR} from "@angular/forms";

export const ACADEMIC_STAFF_CONTROL_VALUE_ACCESSOR = new Provider(NG_VALUE_ACCESSOR,{useExisting:forwardRef(() => AcademicstaffpostingComponent),multi:true});

@Component({
    selector : "academicstaff-posting",
    templateUrl: '/public/angular/app/Listing/InstitutePosting/Views/'+getHtmlVersion('academicstaffposting.component.html'),
    directives:[MY_VALIDATORS,REACTIVE_FORM_DIRECTIVES,RegisterFormModelDirective],
    providers:[ACADEMIC_STAFF_CONTROL_VALUE_ACCESSOR]
})

export class AcademicstaffpostingComponent extends Posting implements OnInit,ControlValueAccessor{

    @Input() instituteObj;
    @Input() instituteStaticData;
    @Input() errors;
    @Input() mode;
    @Input() form;
    activateAcademicStaff = true;
    staffArr:any = [];
    newPosition = -1;

    @Output() toolTipEvent: EventEmitter<any> = new EventEmitter<any>();

    _onTouchedCallback: () => any = () => {};
    _onChangeCallback: (value: any) => void = (value) => {};

    ngOnInit(){
        if(this.mode == 'add'){
            this.addAcademicStaff();
        }
    }

    get staff(){
        return this.staffArr;
    }

    changedetected(){
        this.staff = this.staff.filter(function(){return true;});
        setTimeout(() => {},0);
    }

    set staff(value){
        this.staffArr = value;
        this._onChangeCallback(this.staffArr);
    }

    writeValue(value : any){
        this.staff = value;
    }

    registerOnChange(fn:any){
        this._onChangeCallback = fn;
    }

    registerOnTouched(fn:any){
        this._onTouchedCallback = fn;
    }

    addAcademicStaff(){
        setTimeout(() =>  {
            this.staff.push(new AcademicStaff({'position':this.staff.length+1}));
        },0);
        this.changedetected();
    }

    removeAcademicStaff(position,flag = true){
        this.staff.splice(position-1,1);
        setTimeout(() => {
            for(let staff of this.staff){
                if(staff.position > position){
                    --staff.position;
                }
            }
        },0);
        this.changedetected();
    }

    changeStaffPosition(currentPosition,newPosition){
        if(newPosition == -1){
            return;
        }
        let academicStaff = [];
        for(let staff of this.staff){
            academicStaff.push(Object.assign({},staff));
        }

        if(currentPosition < newPosition){
          for(let staff of academicStaff){
            if(staff.position > currentPosition && staff.position <= newPosition){
              --staff.position;
            }
          }
          academicStaff[currentPosition-1]['position'] = newPosition;  
        }
        else{
          for(let staff of academicStaff){
            if(staff.position >= newPosition && staff.position < currentPosition){
              ++staff.position;
            }
          }
          academicStaff[currentPosition-1]['position'] = newPosition;
        }

        academicStaff.sort((a,b) => {
          if(a['position']<b['position']){return -1}
          if(a['position']>b['position']){return 1}
          return 0;
        });
        this.staff = academicStaff;

        setTimeout(() => {
            this.rebindFormControl('academicStaff');
        },100);
    }
}
