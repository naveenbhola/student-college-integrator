import { Injectable } from '@angular/core';
import { CanActivate,ActivatedRouteSnapshot, RouterStateSnapshot, Resolve, Router } from '@angular/router';
import { UserService } from './userservice';
import { Observable }     from 'rxjs/Observable';

@Injectable()
export class UserGuard implements Resolve<any>{
	constructor(public userService: UserService) {}

	resolve(route: ActivatedRouteSnapshot): Observable<any> | boolean {
		return this.userService.getUserData();
	}
}