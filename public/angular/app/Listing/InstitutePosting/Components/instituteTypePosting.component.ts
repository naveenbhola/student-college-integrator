import { Component,OnInit,Input,Output,EventEmitter } from '@angular/core';
import {Posting} from '../../../Common/Classes/Posting';
import {REACTIVE_FORM_DIRECTIVES, FormGroup, FormControl, NgForm} from "@angular/forms";
import { InstitutePostingService } from '../../../services/institute-posting.service';
import { RegisterFormModelDirective } from '../../../reusables/registerForm';
import { MY_VALIDATORS } from '../../../reusables/Validators';
import { InstituteLocationService } from '../../../services/institute-locations.service';
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';

@Component({
	selector : "institute-posting",
	directives:[MY_VALIDATORS,REACTIVE_FORM_DIRECTIVES,RegisterFormModelDirective],
	templateUrl: '/public/angular/app/Listing/InstitutePosting/Views/'+getHtmlVersion('instituteTypePosting.component.html'),
	providers:[InstitutePostingService]
})

export class InstituteTypePostingComponent extends Posting implements OnInit{

	@Input() instituteObj; 
	@Input() instituteStaticData;
	@Input() mode;
	@Input('form') form: NgForm;
	validateInstituteTypeFlag = 'On';

	@Output() toolTipEvent: EventEmitter<any> = new EventEmitter<any>();

	constructor(private institutePosting: InstitutePostingService,public locationService: InstituteLocationService){
		super();
	}

	ngOnInit()
	{
		this.locationService.handleHierarchyChange$.subscribe(() => {
			
			this.validateInstituteType(this.instituteObj.institute_type);
        });
	}

	validateInstituteType(event){
		if(typeof(this.instituteObj.parent_entity_id) === 'undefined' || this.instituteObj.is_dummy == 'true' || this.instituteObj.is_satellite_entity == true){
			return;
		}

		this.institutePosting.validateInstituteTypeInHierarchy(this.instituteObj.parent_entity_id,
															   this.instituteObj.parent_entity_type,
															   event,this.instituteObj.institute_id)
		.subscribe(
            data => { 
            	if(data.alreadyExists == true){
            		this.validateInstituteTypeFlag = '';
            	}
            	else{
            		this.validateInstituteTypeFlag = 'On';	
            	}
            },
             error => {console.log('Failed to check institute type');}
        );
	}

}
