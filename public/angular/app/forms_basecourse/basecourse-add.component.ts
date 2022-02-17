import { Component, OnInit, OnDestroy } from '@angular/core';
import { BasecourseService }       from '../services/basecourse.service';
import { FormGroup,FormControl,FormArray,REACTIVE_FORM_DIRECTIVES,Validators } from '@angular/forms';
import { ListingBaseClass, ListingBaseService } from '../services/listingbase.service';
import { Router,ActivatedRoute } from '@angular/router';
import { HierarchyFormGroup } from '../reusables/hierarchyFormGroup';
import { Observable }     from 'rxjs/Rx';


@Component({
	selector: 'basecourseAdd',
	templateUrl: '/public/angular/app/forms_basecourse/basecourse-add.component.html',
	providers:[BasecourseService],
	styles: [`
        .ng-invalid {
            border: 1px solid #a94442;
        }
        .ng-pristine{
        	border: 1px solid #ccc;
        }
    `],
	directives: [ REACTIVE_FORM_DIRECTIVES,HierarchyFormGroup ]
})

export class BasecourseAddComponent extends ListingBaseClass implements OnInit, OnDestroy {
	basecourseForm: any;
	basecourseData: any;
	mode:string = 'add';
	basecourseId:number = 0;
	hierarchyMapping:any;
	formSubmit:boolean = false;
	hierarchyArray:any;
	hierarchyTree:any;
	selectedHierarchies = [];
	courseLevels = [];
	courseCredentials = [];
	private sub: any;

	constructor(private basecourseService: BasecourseService, private router: Router, private route: ActivatedRoute, public listingBaseService: ListingBaseService) {
		super(listingBaseService);
		this.basecourseForm = this.generateFormControls();
	}

	generateFormControls(){
		let baseCourseForm = new FormGroup({});
		baseCourseForm.addControl('basecourseName',new FormControl("",Validators.required));
		baseCourseForm.addControl('basecourseAlias',new FormControl("",Validators.maxLength(20)));
		baseCourseForm.addControl('basecourseSynonym',new FormControl("",Validators.maxLength(100)));
		baseCourseForm.addControl('basecourseLevel',new FormControl('',Validators.required));
		baseCourseForm.addControl('basecourseCredential1',new FormControl('',Validators.required));
		baseCourseForm.addControl('basecourseCredential2',new FormControl('0'));
		baseCourseForm.addControl('basecourseIsPopular',new FormControl('0'));
		baseCourseForm.addControl('basecourseIsHyperlocal',new FormControl('0'));
		baseCourseForm.addControl('basecourseIsExecutive',new FormControl('0'));

		this.hierarchyMapping = new FormArray([]);
		baseCourseForm.addControl('hierarchyArray',this.hierarchyMapping);
		return baseCourseForm;
	}


	ngOnInit() {
		let o1 = this.listingBaseService.getHierarchyTree();
		let o2 = this.listingBaseService.getHierarchies();
		Observable.forkJoin([o1,o2]).subscribe(
			data => {
				this.hierarchyTree = data[0];
				this.hierarchyArray = data[1];
				this.afterFormGroupInitialize();				
			},
			error => {alert('Failed to get data');}
		);

		let courseAttributes = this.basecourseService.getCourseAttributes().subscribe(

			data => {
				for(let oneCourseLevel in data['Course level']){
					let courseLevel = {'level_id' : oneCourseLevel, 'level_name': data['Course level'][oneCourseLevel]};
					this.courseLevels.push(courseLevel);
				}


				for(let oneCourseCredential in data.Credential){
					let courseCredential = {'value' : oneCourseCredential, 'label': data.Credential[oneCourseCredential]};
					this.courseCredentials.push(courseCredential);
				}

			},

			error => {
				error; alert('No course attributes found');
			}

		);
	}

	afterFormGroupInitialize(){
		this.sub = this.route.params.subscribe(params => {
			let id = +params['id'];
			if(id){
				this.mode = 'edit';
				this.basecourseService.getBasecourse(id)
					.subscribe(data => {
						this.basecourseData = data; this.basecourseId = id;
						console.log(this.basecourseData);
						console.log("aaaa");
						let controls = this.basecourseForm.controls;
						controls.basecourseName.updateValue(data['name']);
						controls.basecourseAlias.updateValue(data['alias']);
						controls.basecourseSynonym.updateValue(data['synonym']);
						controls.basecourseLevel.updateValue(data['level']);
						controls.basecourseCredential1.updateValue(data['credential'][0]);
						controls.basecourseCredential2.updateValue(data['credential'][1]);
						controls.basecourseIsPopular.updateValue(data['is_popular']);
						controls.basecourseIsHyperlocal.updateValue(data['is_hyperlocal']);
						controls.basecourseIsExecutive.updateValue(data['is_executive']);
						this.selectedHierarchies = data['hierarchyArray'];
					}
					, error => { alert('No such base-course exists'); this.backToHome(); });
			}
		});
	}

	backToHome(){
		this.router.navigate(['/cmsPosting/basecoursePosting/viewList']);	
	}

	submitBasecourse() {
		if (this.basecourseForm.dirty) {
			if (this.basecourseForm.valid){
				this.formSubmit = true;
				this.basecourseService.addBasecourse(this.basecourseForm.value,this.mode,this.basecourseId)
					.subscribe(
						data => {
							alert(data['message']);if(data.status == 'success'){this.formSubmit = false; this.backToHome();}else{this.formSubmit = false;} },
						error => { alert('Internal error'); this.backToHome(); }
					);
			}
		}
		else{
			this.backToHome();
		}
	}

	ngOnDestroy(){
		this.sub.unsubscribe();
	}
}