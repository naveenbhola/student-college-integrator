import { Injectable } from '@angular/core';
import { CanActivate, CanDeactivate, ActivatedRouteSnapshot, RouterStateSnapshot, Resolve, Router } from '@angular/router';
import { Observable }     from 'rxjs/Observable';
import { ListingBaseService } from '../../../services/listingbase.service';

@Injectable()
export class StreamsGuard implements Resolve<any>{
    constructor(public listingBaseService: ListingBaseService) {
    }

    resolve(route: ActivatedRouteSnapshot): Observable<any> | boolean {
        return this.listingBaseService.getStreams();
    }
}

@Injectable()
export class BaseAttributesGuard implements Resolve<any>{
    constructor(public listingBaseService: ListingBaseService) {
    }

    resolve(route: ActivatedRouteSnapshot): Observable<any> | boolean {
        return this.listingBaseService.getCourseAttributes();
    }
}

@Injectable()
export class PopularCoursesGuard implements Resolve<any>{
    constructor(public listingBaseService: ListingBaseService) {
    }

    resolve(route: ActivatedRouteSnapshot): Observable<any> | boolean {
        return this.listingBaseService.getPopularCourses();
    }
}

@Injectable()
export class BaseCoursesGuard implements Resolve<any>{
    constructor(public listingBaseService: ListingBaseService) {
    }

    resolve(route: ActivatedRouteSnapshot): Observable<any> | boolean {
        return this.listingBaseService.getBasecourses();
    }
}