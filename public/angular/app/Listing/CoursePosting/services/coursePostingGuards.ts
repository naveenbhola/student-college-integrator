import { Injectable } from '@angular/core';
import { CanActivate, CanDeactivate, ActivatedRouteSnapshot, RouterStateSnapshot, Resolve, Router } from '@angular/router';
import { Observable }     from 'rxjs/Observable';
import { ListingBaseService } from '../../../services/listingbase.service';
import { CoursePostingService } from '../services/coursePosting.service';
import { CourseDependencyService } from '../services/course-dependencies.service';
import { UserService } from '../../../Common/services/userservice';


import { CoursePostingCreateComponent } from '../components/coursePostingCreate.component';

@Injectable()
export class ListingAdminGuard implements Resolve<any>{
    constructor(public userService:UserService,public router:Router) {}

    resolve(route: ActivatedRouteSnapshot): Observable<any> | boolean {
        return this.userService.getUserData().map((data) => {
            if(data && data['userGroup'] == 'listingAdmin'){
                return true;
            }
            else{
                window.location.href = '/enterprise/Enterprise/unauthorizedEnt';
                return false;
            }
        });
    }
}

@Injectable()
export class ListingSpecialUsersGuard implements Resolve<any>{

    userList = ["listingallaccess@shiksha.com"];
    constructor(public userService:UserService,public router:Router) {}

    resolve(route: ActivatedRouteSnapshot): Observable<any> | boolean {
        return this.userService.getUserData().map((data) => {
            if(data && data['userGroup'] == 'listingAdmin' && this.userList.indexOf(data['email']) > -1){
                return true;
            }
            else{
                window.location.href = '/enterprise/Enterprise/unauthorizedEnt';
                return false;
            }
        });
    }
}

export interface CanComponentDeactivate {
 canDeactivate: () => Observable<boolean> | boolean;
}

@Injectable()
export class CourseDeactivateGuard implements CanDeactivate<CanComponentDeactivate>{
    canDeactivate(component:CanComponentDeactivate){
        return component.canDeactivate ? component.canDeactivate() : true;
    }
}

@Injectable()
export class CourseEditGuard implements Resolve<any>{
	constructor(public coursePosting: CoursePostingService) {
	}

	resolve(route: ActivatedRouteSnapshot): Observable<any> | boolean {
		let courseId = +route.params['id']; 
		return this.coursePosting.getCourseData(courseId);
	}
}

@Injectable()
export class CourseCloneGuard implements Resolve<any>{
    constructor(public coursePosting: CoursePostingService,public dependencyService: CourseDependencyService) {}

    resolve(route: ActivatedRouteSnapshot): Observable<any> | boolean {
        return this.dependencyService.cloneSource.flatMap((data) => {
            if (data && data['sections'] && data['sections'].length > 0) {
                return this.coursePosting.cloneCourse(data);
            }
            else{
                return Observable.of(true);
            }
        }).take(1);
    }
}

@Injectable()
export class CourseStaticDataGuard implements Resolve<any>{
    constructor(public coursePosting: CoursePostingService) {
    }

    resolve(route: ActivatedRouteSnapshot): Observable<any> | boolean {
        return this.coursePosting.getCourseStaticData();
    }
}

@Injectable()
export class CourseHierarchyTreeGuard implements Resolve<any>{
    constructor(public listingBaseService: ListingBaseService) {
    }

    resolve(route: ActivatedRouteSnapshot): Observable<any> | boolean {
        return this.listingBaseService.getHierarchyTree();
    }
}

@Injectable()
export class CourseHierarchiesGuard implements Resolve<any>{
    constructor(public listingBaseService: ListingBaseService) {
    }

    resolve(route: ActivatedRouteSnapshot): Observable<any> | boolean {
        return this.listingBaseService.getHierarchies();
    }
}
