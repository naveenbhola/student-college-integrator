import { Component, OnInit, OnDestroy } from '@angular/core';
import { PopulargroupService }       from '../services/populargroup.service';
import { FormGroup,FormControl,FormArray,REACTIVE_FORM_DIRECTIVES,Validators } from '@angular/forms';
import { ListingBaseClass, ListingBaseService } from '../services/listingbase.service';
import { Router,ActivatedRoute } from '@angular/router';
import { HierarchyFormGroup } from '../reusables/hierarchyFormGroup';
import { Observable }     from 'rxjs/Rx';

@Component({
	selector: 'populargroupAdd',
	templateUrl: '/public/angular/app/forms_populargroup/populargroup-add.component.html',
	providers:[PopulargroupService],
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

export class PopulargroupAddComponent extends ListingBaseClass implements OnInit, OnDestroy {

	mode = 'add';
	private sub: any;
	popularGroupForm:any;
	hierarchyMapping:any;
	submitPending = false;
	hierarchyTree;
	hierarchyArray;
	popularGroupData;
	popularGroupId = 0;
	selectedHierarchies = [];

	constructor(private populargroupService: PopulargroupService, private router: Router, private route: ActivatedRoute, public listingBaseService: ListingBaseService) {
		super(listingBaseService);
		this.popularGroupForm = this.generateFormControls();
	}

	generateFormControls(){
		let myForm = new FormGroup({});
		myForm.addControl('name',new FormControl("",Validators.required));
		myForm.addControl('alias',new FormControl());
		myForm.addControl('synonym',new FormControl());
		this.hierarchyMapping = new FormArray([]);
		myForm.addControl('hierarchyArray',this.hierarchyMapping);
		return myForm;
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
	}

	afterFormGroupInitialize(){
		this.sub = this.route.params.subscribe(params => {
			let id = +params['id'];
			if(id){
				this.mode = 'edit';
				this.populargroupService.getPopulargroup(id)
				.subscribe(data => { 
					this.popularGroupData = data; this.popularGroupId = id;
					let controls = this.popularGroupForm.controls;
					controls.name.updateValue(data['name']);
					controls.alias.updateValue(data['alias']);
					controls.synonym.updateValue(data['synonym']);
					this.selectedHierarchies = data['hierarchyArray'];
				}
				, error => { alert('No such popularGroup exists'); this.backToHome(); });
			}
		});
	}
	
	backToHome(){
		this.router.navigate(['/cmsPosting/populargroupPosting/viewList']);	
	}

	submitPopulargroup() {
		if (this.popularGroupForm.dirty) {
			if (this.popularGroupForm.valid){
				this.submitPending = true;
				this.populargroupService.addPopulargroup(this.popularGroupForm.value,this.mode,this.popularGroupId)
					.subscribe(
						data => { alert(data['message']);if(data.status == 'success'){this.submitPending = false; this.backToHome();}else{this.submitPending = false;} },
						error => { alert('Internal error');this.backToHome(); }
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

	get diagnostic(){
		return JSON.stringify(this.popularGroupForm.value);
	}
}