import { Component,OnInit,Input,Provider,forwardRef,Output,EventEmitter } from '@angular/core';
import {Posting} from '../../../Common/Classes/Posting';
import { MY_VALIDATORS } from '../../../reusables/Validators';
import { InstituteEvent } from '../Classes/InstituteEntities';

import {ControlValueAccessor,NG_VALUE_ACCESSOR} from "@angular/forms";
import { InstituteLocationService } from '../../../services/institute-locations.service';
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';

export const EVENTS_CONTROL_VALUE_ACCESSOR = new Provider(NG_VALUE_ACCESSOR,{useExisting:forwardRef(() => EventPostingComponent),multi:true});

@Component({
	selector: 'event-posting',
	templateUrl: '/public/angular/app/Listing/InstitutePosting/Views/'+getHtmlVersion('event-posting.component.html'),
	directives:[MY_VALIDATORS],
	providers:[EVENTS_CONTROL_VALUE_ACCESSOR]
})
export class EventPostingComponent extends Posting implements OnInit,ControlValueAccessor {
	@Input() instituteStaticData;
	@Input() errors;
	@Input() mode;
	activateEvents = true;
	eventArr:any = [];
	newPosition = -1;

	@Output() toolTipEvent: EventEmitter<any> = new EventEmitter<any>();

	_onTouchedCallback: () => any = () => {};
	_onChangeCallback: (value: any) => void = (value) => {};

	constructor(public locationService: InstituteLocationService){
    	super();
  	}

	ngOnInit(){
	    if(this.mode == 'add'){
	    	this.addEvent();
	    }
	}

	get events(){
	    return this.eventArr;
	}

	changedetected(){
	    this.events = this.events.filter(function(){return true;});
	    // setTimeout(() => {},0);
	    // this.locationService.pushEventChange({});
	}

	set events(value){
	    this.eventArr = value;
	    this._onChangeCallback(this.eventArr);
	}

	writeValue(value : any){
	    this.events = value;
	}

	registerOnChange(fn:any){
	    this._onChangeCallback = fn;
	}

	registerOnTouched(fn:any){
	    this._onTouchedCallback = fn;
	}

	addEvent(){
	    setTimeout(() => {
	        this.events.push(new InstituteEvent({'position':this.events.length+1}));
	    },0);
	    this.changedetected();
	}

	removeEvent(position,flag = true){
		this.events.splice(position-1,1);
	    setTimeout(() => {
	        for(let event of this.events){
	            if(event.position > position){
	                --event.position;
	            }
	        }
	    },0);
	    this.changedetected();
	}

	changeEventPosition(currentPosition,newPosition){
	    if(newPosition == -1){
	        return;
	    }
	    let events = [];
	    for(let event of this.events){
	        events.push(Object.assign({},event));
	    }

	    if(currentPosition < newPosition){
	      for(let event of events){
	        if(event.position > currentPosition && event.position <= newPosition){
	          --event.position;
	        }
	      }
	      events[currentPosition-1]['position'] = newPosition;  
	    }
	    else{
	      for(let event of events){
	        if(event.position >= newPosition && event.position < currentPosition){
	          ++event.position;
	        }
	      }
	      events[currentPosition-1]['position'] = newPosition;
	    }

	    events.sort((a,b) => {
	      if(a['position']<b['position']){return -1}
	      if(a['position']>b['position']){return 1}
	      return 0;
	    });
	    this.events = events;

	    setTimeout(() => {
	        this.rebindFormControl('events');
	        // this.locationService.pushEventChange({});
	    },100);
	}
}
	