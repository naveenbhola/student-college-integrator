import { Component,Input ,OnInit,Output,EventEmitter} from '@angular/core';
import { Posting } from '../../../Common/Classes/Posting';
import { InstituteParentHierarchyComponent } from '../../ListingReusables/components/instituteParentHierarchy.component';
import { getHtmlVersion } from '../../../Common/utils/htmlVersioning';

@Component({
	selector : "parententity-posting",
	templateUrl: '/public/angular/app/Listing/InstitutePosting/Views/'+getHtmlVersion('parententityposting.component.html'),
	directives:[InstituteParentHierarchyComponent]
})

export class ParentEntityPostingComponent extends Posting implements OnInit{

	@Input() instituteObj; 
	@Input() mode;
    isSelectedHierarchy:boolean;
    selectedParentObj = [];

    @Output() toolTipEvent: EventEmitter<any> = new EventEmitter<any>();

    showselectedHierarichy: Array<Object> = [];
    ngOnInit()
    {
      if(this.mode == 'edit')
      {
          this.isSelectedHierarchy = true;
          for(let i in this.instituteObj.parentHierarichyDetails)
          {
            this.showselectedHierarichy.push(this.instituteObj.parentHierarichyDetails[i]);
          }
          this.selectedParentObj = this.showselectedHierarichy;
      }
      if(this.selectedParentObj.length == 0)
      {
          this.isSelectedHierarchy = false;
      }

    }
    selectedHierarchy(res){
      this.instituteObj.parent_entity_name = res.selectedParentEntity; 
      let instituteString = res.selectedParentEntity.split("_");
      this.instituteObj.parent_entity_id = instituteString[1]; 
      this.instituteObj.parent_entity_type = instituteString[0]; 
      
      this.isSelectedHierarchy = true;
      /*if(this.instituteObj.parent_entity_type == 'institute'){
        this.showSatelliteCampus = true;
      }else{
        this.showSatelliteCampus = false;
      }*/
      this.selectedParentObj  =    res.selectedParentObj;

      /*if(this.instituteObj.postingListingType != 'University')
      {
          let hierarchyLength = this.selectedParentObj.length;
          for(let i = hierarchyLength-1 ; i >=0 ;i --){
            let primary_type = this.selectedParentObj[i].type;
            this.instituteObj.seo_title = '';
            this.instituteObj.seo_description = '';
            this.instituteObj.primary_listing_id = '';
            this.instituteObj.primary_listing_type = '';
            if(primary_type == 'college' || primary_type =='university' || primary_type =='school'){
              let primary_id = this.selectedParentObj[i].id.split("_");
              this.instituteObj.primary_listing_id = primary_id[1];
              this.instituteObj.primary_listing_type = primary_type;
              break;
            }
          }
      }*/
      
      this.stopPostingDisabledMode();

    }
    stopPostingDisabledMode()
    {
        let parentObjLength = this.selectedParentObj.length;
        if( parentObjLength > 0 && (this.selectedParentObj[parentObjLength-1].disabled_url != '' ))
        {
            this.instituteObj.disabled_url = this.selectedParentObj[parentObjLength-1].disabled_url;
        }
        else
        {
            this.instituteObj.disabled_url = '';
        }
    }
    removeHierarichy()
    {
      this.selectedParentObj = [];
      this.isSelectedHierarchy = false;
      this.instituteObj.primary_listing_id = '';
      this.instituteObj.primary_listing_type = '';
      this.instituteObj.parent_entity_id = '';
      this.instituteObj.parent_entity_type = '';
    }
    toolTipModalParentShow(event:any)
    {
      this.toolTipEvent.emit(event);
    }
}
