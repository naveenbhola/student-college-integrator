import { Component, OnInit, OnDestroy } from '@angular/core';
import { CertificationProviderService }       from '../services/certification-provider.service';
import { ListingBaseClass, ListingBaseService } from '../services/listingbase.service';
import { Router,ActivatedRoute } from '@angular/router';
import { FormGroup,FormControl,FormArray,REACTIVE_FORM_DIRECTIVES,Validators } from '@angular/forms';


@Component({
	selector: 'certificationProviderAdd',
	templateUrl: '/public/angular/app/forms_certification_provider/certification-provider-add.component.html',
	providers:[CertificationProviderService],
	styles: [`
        .ng-invalid {
            border: 1px solid #a94442;
        }
        .ng-pristine{
        	border: 1px solid #ccc;
        }
    `],
	directives: [ REACTIVE_FORM_DIRECTIVES ]
})

export class CertificationProviderAddComponent extends ListingBaseClass implements OnInit, OnDestroy {
	certificationProviderForm:any
	submitPending                     = false;	
	certificationProviderData: any[];
	courseMapping:any;
	mode:string                    = 'add';
	baseCourseList:any;
	certificationProviderId:number = 0;
	initialized = false;	
	private sub: any;
	CourseMappingList = [];
	constructor(private cpService: CertificationProviderService, private router: Router, private route: ActivatedRoute, public listingBaseService: ListingBaseService) { 
		super(listingBaseService); 
		this.certificationProviderForm = new FormGroup({
			'name':new FormControl("",[Validators.required,Validators.pattern('[A-Za-z0-9 ]{1,100}')]),
			'alias':new FormControl("",Validators.pattern('[A-Za-z0-9 ]{1,100}')),
			'synonym':new FormControl("",Validators.pattern('[A-Za-z0-9, ]{1,150}')),
			'courseMapping':new FormArray([])
		});
		this.courseMapping = this.certificationProviderForm.controls['courseMapping'];
		setTimeout(()=>{this.initialized = true;},0);
	}

	addItemToCourseMapping(){
		let item = new FormGroup({
			'courseId':new FormControl("",Validators.required),			
		});
		
		this.courseMapping.push(item);
		let length = this.courseMapping.length;
		
		this['CourseMappingList'][length-1] = Object.values(this.baseCourseList);		
	}

	removeCourseMapping(index){
		this.courseMapping.removeAt(index);
		this.CourseMappingList.splice(index,1);
		this.courseMapping.markAsDirty();
	}

	ngOnInit() {
		this.listingBaseService.getBasecourses()
		.subscribe(baseCourseList => {
			this.baseCourseList = baseCourseList;
			this.sub = this.route.params.subscribe(params => {
				let id = +params['id'];
				if(id){
					this.mode = 'edit';
					this.cpService.getCertificationProvider(id)
					.subscribe(data => { 						
						this.certificationProviderData = data; 
						this.certificationProviderId = id; 
						let controls = this.certificationProviderForm.controls;
						controls.name.updateValue(data['name']);
						controls.alias.updateValue(data['alias']);
						controls.synonym.updateValue(data['synonym']);
						for(let item in data['courseMapping']){
							this.addItemToCourseMapping();
							this.courseMapping.controls[item].controls.courseId.updateValue(data['courseMapping'][item]['id'],{emitEvent:true});						
						}
					}, error => { 
						 error; alert('No such certification exists'); this.backToHome(); 
					});
				}else{
					this.addItemToCourseMapping();
				}
		});
		},error => alert('Failed to get base course list'));
		
	}

	
	
	backToHome(){
		this.router.navigate(['/cmsPosting/certificationProviderPosting/viewList']);	
	}
	
	submitCertificationProvider() {
		if (this.certificationProviderForm.dirty) {
			if (this.certificationProviderForm.valid){
				this.submitPending = true;
				this.cpService.addCertificationProvider(this.certificationProviderForm.value,this.mode,this.certificationProviderId)
					.subscribe(
						data => { alert(data['message']);if(data.status == 'success'){this.submitPending = false;this.backToHome();}else{this.submitPending = false;} },
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

	get diagnostic(){
		return JSON.stringify(this.certificationProviderForm.value);
	}
}