import { Component, OnInit } from '@angular/core';
import { AuthServiceService } from "../../../auth-service.service";
import { Router } from '@angular/router';


@Component({
  selector: 'app-add-student',
  templateUrl: './add-student.component.html',
  styleUrls: ['./add-student.component.css']
})

export class AddStudentComponent implements OnInit {

  constructor( private http: AuthServiceService,
  				private router: Router ) { }

  courses: any= [];
  ngOnInit(): void {
  	this.http.listCourses().subscribe(data=>{
  		this.courses = data;
  	});
  }

  save(value): void {
  	this.http.addStudent(value).subscribe(data=>{
  		this.router.navigate(['student']);
  	})
  }

}
