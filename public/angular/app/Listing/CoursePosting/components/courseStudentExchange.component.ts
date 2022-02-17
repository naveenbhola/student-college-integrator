import { Component, OnInit,Input } from '@angular/core';
import { REACTIVE_FORM_DIRECTIVES, FormGroup,FormControl, FormArray, Validators } from '@angular/forms';
import { Posting } from '../../../Common/Classes/Posting';


@Component({	
	selector: 'studentExchange',
	templateUrl: '/public/angular/app/Listing/CoursePosting/views/courseStudentExchange.template.html',
	directives:[REACTIVE_FORM_DIRECTIVES]
})
export class CourseStudentExchangeComponent extends Posting implements OnInit {
	@Input('group')

    public courseStudentExchange: FormGroup;
	ngOnInit(){
		this.courseStudentExchange.addControl('is_available',new FormControl(''));
		this.courseStudentExchange.controls['is_available'].valueChanges.subscribe((data) => {
			if(data == 'Yes'){
				this.generateControls();				
			}else{
				this.courseStudentExchange.removeControl('duration_value');		
				this.courseStudentExchange.removeControl('duration_unit');		
				this.courseStudentExchange.removeControl('description');		
			}
			
		});			
		
		
	}

	generateControls(){
		this.courseStudentExchange.addControl('duration_value',new FormControl(''));
		this.courseStudentExchange.addControl('duration_unit',new FormControl('Months'));
		this.courseStudentExchange.addControl('description',new FormControl());
	}
}