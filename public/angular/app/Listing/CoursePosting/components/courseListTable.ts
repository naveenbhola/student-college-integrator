import { Component, OnInit, ViewChild, Input } from '@angular/core';
import {Router, ROUTER_DIRECTIVES} from '@angular/router';

import {MODAL_DIRECTIVES, BS_VIEW_PROVIDERS} from 'ng2-bootstrap/ng2-bootstrap';
import {ModalDirective} from '../../../Common/Components/modal/modal.component';
import { COURSE_SECTIONS } from '../classes/CourseConst';
import { REACTIVE_FORM_DIRECTIVES, FormGroup, FormArray, FormControl, Validators, ValidatorFn } from '@angular/forms';
import { My_Custom_Validators } from '../../../reusables/Validators';
import { Posting } from '../../../Common/Classes/Posting';
import { CoursePostingService } from '../services/coursePosting.service';
import { CourseDependencyService } from '../services/course-dependencies.service';
import { UserService } from '../../../Common/services/userservice';
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';

@Component({
    selector: 'courseListTable',
	templateUrl: '/public/angular/app/Listing/CoursePosting/views/'+getHtmlVersion('courseListTable.template.html'),
	directives: [ROUTER_DIRECTIVES, MODAL_DIRECTIVES, REACTIVE_FORM_DIRECTIVES],
	providers: [BS_VIEW_PROVIDERS, CoursePostingService]
})

export class CourseListTable extends Posting implements OnInit {
	@Input() courseResultsTable: Array<Object> = [];
	public openOptions: any = -1;
	cloneForm: FormGroup = new FormGroup({'courseId':new FormControl('')});
	callType: string = '';
	confirmationMessage: any = '';
	disableBodyMessage: any = '';
	bodyMessage: any = '';
	courseStatus: any = '';
	secondButton: any = '';
	firstButton: any = '';
	errorMsg1 = '';
	courseList;
	migrationCourseName: any = '';
	courseId: number;
	userGroup;
	courseName:string = '';
	submitPending = false;

	sections = COURSE_SECTIONS;

	constructor(private router: Router, private coursePosting: CoursePostingService, public userService: UserService,public dependencyService: CourseDependencyService) {
		super();
	}

	ngOnInit() {
		this.courseList = new FormGroup({});
		this.courseList.addControl('disable', new FormControl('http://www.shiksha.com', Validators.compose([
			Validators.required,
			Validators.maxLength(500),
			My_Custom_Validators.ValidateString('shiksha_link')
		])
		)
		);
		this.courseList.addControl('migrationCourseId', new FormControl('',My_Custom_Validators.validateWholeNumber(0)));
		this.courseList.addControl('responses', new FormControl(true));
		this.courseList.addControl('reviews', new FormControl(true));
		this.courseList.addControl('crs', new FormControl(true));
		this.courseList.addControl('questions', new FormControl(true));
		this.userService.getUserData().subscribe((data) => {
			this.userGroup = data['userGroup'];
		})
	}

	openOptionsForCourses(courseId: number, status: string,courseName) {
		this.courseId = courseId;
		this.courseName = courseName;
		if (courseId && this.openOptions != courseId + '_' + status)
			this.openOptions = courseId + '_' + status;
		else
			this.openOptions = -1;

		console.log(this.openOptions);
	}

	isListingAdmin() {
		return (this.userGroup == 'listingAdmin') ? true : false;
	}

	editUrl(courseId: number) {
		this.router.navigate(['/nationalCourse/CoursePosting/edit', courseId]);
	}

	@ViewChild('deletedPaidAlert') public deletedPaidAlert: ModalDirective;

	buttonAction(event: any, courseStatus: string,paidFree: any = '') {
		this.callType = event;
		
		if (event == 'disable') {
			if (this.courseList.contains('disable')) {
				this.courseList.controls.disable.updateValue('http://www.shiksha.com');
			}

			this.confirmationMessage = 'Are you sure you want to disable "'+this.courseName+'" course?';
			this.disableBodyMessage = 'true';
			this.bodyMessage = '';
			this.secondButton = '';
			this.firstButton = 'Disable course';
			this.confirmationLayer.show();
		}
		else if (event == 'delete') {
			if(paidFree == 1 || paidFree == 2 || paidFree == 375)
			{
				this.deletedPaidAlert.show();
			}
			else
			{
				(<FormControl>this.courseList.controls['migrationCourseId']).updateValue('',{emitEvent:true});
				(<FormControl>this.courseList.controls['responses']).updateValue(true,{emitEvent:true});
				(<FormControl>this.courseList.controls['reviews']).updateValue(true,{emitEvent:true});
				(<FormControl>this.courseList.controls['crs']).updateValue(true,{emitEvent:true});
				(<FormControl>this.courseList.controls['questions']).updateValue(true,{emitEvent:true});
				this.confirmationMessage = 'Are you sure you want to delete "'+this.courseName+'" course?';
				this.firstButton = "Delete course";
				this.secondButton = "";
				this.bodyMessage = 'true';
				this.disableBodyMessage = '';
				this.confirmationLayer.show();
			}
			
		}
		else if (event == 'deleteConfirm') {
			if (this.migrationCourseName != '') {
				this.confirmationMessage = 'Are you sure you want to map all responses, reviews, questions and redirect users of this course to: "' + this.migrationCourseName + '"?';
			} else {
				this.confirmationMessage = 'Are you sure you want to delete "'+this.courseName+'" course?';
			}

			this.firstButton = "Confirm and Delete";
			this.secondButton = "Go Back";
			this.bodyMessage = '';
			this.disableBodyMessage = '';
			this.confirmationLayer.show();
		} else if (event == 'enable') {
			this.confirmationMessage = 'Are you sure you want to enable course "'+this.courseName+'"?';
			this.firstButton = "Yes";
			this.secondButton = "No";
			this.courseStatus = courseStatus;
			this.bodyMessage = '';
			this.disableBodyMessage = '';
			this.confirmationLayer.show();
		}
		else if (event == 'makeLive') {
			this.confirmationMessage = 'Are you sure you want to publish course "'+this.courseName+'"?';
			this.firstButton = "Yes";
			this.secondButton = "No";
			this.courseStatus = courseStatus;
			this.bodyMessage = '';
			this.disableBodyMessage = '';
			this.confirmationLayer.show();

		}
		else if (event == 'makeLiveEnable') {
			this.confirmationMessage = 'Are you sure you want to enable and publish course "'+this.courseName+'"?';
			this.firstButton = "Yes";
			this.secondButton = "No";
			this.courseStatus = courseStatus;
			this.bodyMessage = '';
			this.disableBodyMessage = '';
			this.confirmationLayer.show();
		}
	}


	@ViewChild('confirmationLayer') public confirmationLayer: ModalDirective;

	public hideConfirmationLayer() {
		this.confirmationLayer.hide();
		this.submitPending = false;
	}

	firstButtonEvent() {
		console.log(this.callType);
		if (this.callType == 'delete') {
			this.validateListing();
		}
		else if (this.callType == 'deleteConfirm') {
			this.deleteCourseListing();
		}
		else if (this.callType == 'disable') {
			this.disableCourseListing();
			//this.validateDisableUrl(formData);
		}
		else if (this.callType == 'disableConfirm') {
			//this.setListingDisableUrl(formData);
		}
		else if (this.callType == 'enable') {
			this.enableCourseListing();
		}
		else if (this.callType == 'makeLive') {
			this.makeLive();
		} else if (this.callType == 'makeLiveEnable') {
			this.makeLiveEnable();
		}
	}

	secondButtonEvent() {
		console.log(this.callType);
		if (this.callType == 'makeLive') {
			this.hideConfirmationLayer();
		}
		else if (this.callType == 'delete') {
			//this.router.navigate(this.migrateResponseurl);
		}
		else if (this.callType == 'deleteConfirm') {
			this.hideConfirmationLayer();
		}
		else if (this.callType == 'disableConfirm') {
			this.hideConfirmationLayer();
		} else if (this.callType == 'makeLiveEnable') {
			this.hideConfirmationLayer();
		}
	}


	public validateListing() {
		console.log(this.courseList.controls.migrationCourseId.value);
		//this.submitPending = true;
		let migrationCourseId = this.courseList.controls.migrationCourseId.value;
		if (migrationCourseId == this.courseId) {
			alert('Entered course id is same as deleted course id');
		} else {
			if (migrationCourseId) {
				this.coursePosting.validateCourseId(migrationCourseId).subscribe(
					data => {
						console.log(data);
						//this.submitPending = false;
						if (data.status == 'fail') {
							alert(data['message']);
							this.migrationCourseName = null;
						} else {
							this.migrationCourseName = data['courseName'];
							this.buttonAction('deleteConfirm', '');
						}
					},
					error => { alert('Internal error'); }
				);
			} else {
				this.migrationCourseName = '';
				this.buttonAction('deleteConfirm', '');
			}
        }
	}

	public deleteCourseListing() {
		this.submitPending = true;
		this.coursePosting.deleteCourseListing(this.courseId, 
											   this.courseList.controls.migrationCourseId.value,
											   this.courseList.controls.responses.value,
											   this.courseList.controls.reviews.value,
											   this.courseList.controls.crs.value,
											   this.courseList.controls.questions.value).subscribe(
           	data => {
           			  this.submitPending = false;
				if (data.status == 'fail') {
					alert(data['message']);
					this.hideConfirmationLayer();
				} else {
					alert(data['message']);
					this.backToHome();
				}
			},
            error => { alert('Internal error'); }
        );
	}

	getLocation(href) {
	    var l = document.createElement("a");
	    l.href = href;
	    return l;
	};

	disableCourseListing() {
		this.submitPending = true;
		let disable_url = this.getLocation(this.courseList.controls.disable.value);
		let disable_relative_url = disable_url.pathname+disable_url.search;
		this.coursePosting.disableCourseListing(this.courseId, disable_relative_url).subscribe(
           	data => {
           			  this.submitPending = false;
				if (data.status == 'fail') {
					alert(data['message']);
					this.hideConfirmationLayer();
				} else {
					alert(data['message']);
					this.backToHome();
				}
			},
            error => { alert('Internal error'); }
        );
	}

	enableCourseListing() {
		this.submitPending = true;
		this.coursePosting.enableCourseListing(this.courseId,this.courseStatus).subscribe(
           	data => {
           			  this.submitPending = false;
				if (data.status == 'fail') {
					alert(data['message']);
					this.hideConfirmationLayer();
				} else {
					alert(data['message']);
					this.backToHome();
				}
			},
            error => { alert('Internal error'); }
        );
	}

	makeLive() {
		this.submitPending = true;
		this.coursePosting.makeLiveCourseListing(this.courseId, this.courseStatus).subscribe(data => { alert(data['msg']);this.submitPending = false; this.backToHome(); },
			error => { alert('Internal Error'); this.backToHome(); });
	}

	makeLiveEnable() {
		this.submitPending = true;
		this.coursePosting.makeLiveAndEnableCourseListing(this.courseId, this.courseStatus).subscribe(data => { alert(data['msg']);this.submitPending = false; this.backToHome(); },
			error => { alert('Internal Error'); this.backToHome(); });
	}

	backToHome() {
		location.href = "/nationalCourse/CoursePosting/viewList";
    }

    @ViewChild('courseCloneModal') public courseCloneModal: ModalDirective;

    showCloneModal(courseId) {
        this.sections.forEach((item) => {
            this.cloneForm.addControl(item.value, new FormControl(false));
        });
        (<FormControl>this.cloneForm.controls['courseId']).updateValue(courseId,{emitEvent:true});
        this.courseCloneModal.show();
    }

    hideCourseCloneModal() {
        this.courseCloneModal.hide();
        // this.cloneForm = new FormGroup({});
    }

    cloneCourse() {
        let sections = ['hierarchyForm', 'courseTypeForm'];
        for (let control in this.cloneForm.controls) {
            if (control!='courseId' && this.cloneForm.controls[control].value) {
                sections.push(control);
            }
        }
        if (sections.length > 0) {
            this.dependencyService.cloneSections(sections, this.cloneForm.controls['courseId'].value);
        }
        this.hideCourseCloneModal();
        this.router.navigate(['/nationalCourse/CoursePosting/create']);
    }
}