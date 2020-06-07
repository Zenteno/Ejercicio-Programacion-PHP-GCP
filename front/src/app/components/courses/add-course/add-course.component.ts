import { Component, OnInit } from '@angular/core';
import { AuthServiceService } from "../../../auth-service.service";
import { Router } from '@angular/router';

@Component({
  selector: 'app-add-course',
  templateUrl: './add-course.component.html',
  styleUrls: ['./add-course.component.css']
})
export class AddCourseComponent implements OnInit {

  constructor(private http: AuthServiceService,
  				private router: Router) { }

  ngOnInit(): void {
  }

  save(value): void{
  	this.http.addCourse(value).subscribe(data=>{
		this.router.navigate(['course']);
  	})
  }

}
