import { Component, OnInit, Input, Output, EventEmitter, OnDestroy } from '@angular/core';
import { REACTIVE_FORM_DIRECTIVES, FormGroup, FormControl, Validators } from '@angular/forms';
import { Posting } from '../../../Common/Classes/Posting';
import { DELIVERY_METHOD, EDUCATION_TYPE } from '../classes/CourseConst';
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';


@Component({
	selector: 'scheduleInfo',
	templateUrl: '/public/angular/app/Listing/CoursePosting/views/'+getHtmlVersion('scheduleInformationSection.component.html'),
	directives: [REACTIVE_FORM_DIRECTIVES]
})
export class ScheduleInformationComponent extends Posting implements OnInit, OnDestroy {

	@Output() toolTipEvent: EventEmitter<any> = new EventEmitter<any>();
	@Input() courseObj;
	@Input() courseStaticData;
	@Input() mode;
	@Input() courseData;
	@Input('group')
    public scheduleForm: FormGroup;

    subscribeArray = {};

	scheduleList;
	DMCONST = DELIVERY_METHOD;
	ETCONST = EDUCATION_TYPE;
	ngOnInit() {
		this.scheduleForm.addControl('education_type', new FormControl(this.ETCONST.fullTime));
		this.scheduleForm.addControl('delivery_method', new FormControl(this.DMCONST.classroom, Validators.required));
		this.subscribeArray['education_type'] = this.scheduleForm.controls['education_type'].valueChanges.subscribe((data) => {
			this.handleEducationChange(data);
		});
	}

	handleEducationChange(data) {
		if (data == this.ETCONST.partTime) {
			this.scheduleForm.addControl('schedule', new FormControl(''));
			(<FormControl>this.scheduleForm.controls['delivery_method']).updateValue('');
			if (!this.subscribeArray['delivery_method']) {
				this.subscribeArray['delivery_method'] = this.scheduleForm.controls['delivery_method'].valueChanges.subscribe((data) => {
					this.handleDeliveryMethodChange(data);
				});
			}
		} else {
			this.scheduleForm.removeControl('schedule');
			//this.scheduleForm.removeControl('delivery_method');
			(<FormControl>this.scheduleForm.controls['delivery_method']).updateValue(this.DMCONST.classroom);
			this.scheduleForm.removeControl('time_of_learning');
			if (this.subscribeArray['delivery_method']) {
				this.subscribeArray['delivery_method'].unsubscribe();
				delete this.subscribeArray['delivery_method'];
			}
		}
	}


	handleDeliveryMethodChange(data) {
		if (data == this.DMCONST.online) {
			// console.log('bbb');
			this.scheduleForm.addControl('time_of_learning', new FormControl(''));
		} else {
			this.scheduleForm.removeControl('time_of_learning');
		}
	}

	setSelectedSchedule(res) {
		(<FormControl>this.scheduleForm.controls['schedule']).updateValue(res.selected);
	}

	ngOnDestroy() {
		for (let name in this.subscribeArray) {
			this.subscribeArray[name].unsubscribe();
		}
	}
}
