import { AuthServiceService } from "../../../auth-service.service";
import { Router, ActivatedRoute } from '@angular/router';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-edit-student',
  templateUrl: './edit-student.component.html',
  styleUrls: ['./edit-student.component.css']
})
export class EditStudentComponent implements OnInit {

  constructor(private http: AuthServiceService,
  				private router: Router,
  				private r: ActivatedRoute) { }

  std:any = {};
  courses: any = [];
  ngOnInit(): void {
  	const id = this.r.snapshot.params["id"];
  	this.http.getStudent(id).subscribe(data=>{
  		this.std = data;
  	});
    this.http.listCourses().subscribe(data=>{
      this.courses = data;
    });
  }

  save(value){
    const id = this.r.snapshot.params["id"];
    this.http.editStudent(id,value).subscribe(data=>{
        this.router.navigate(['student']);
    });
  }
}
