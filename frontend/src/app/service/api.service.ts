import { Injectable } from '@angular/core';
import { HttpClient} from "@angular/common/http";
import { HttpHeaders} from "@angular/common/http";

@Injectable({
  providedIn: 'root'
})

export class ApiService {

	uri = 'http://localhost:8080';
	
	constructor(private http: HttpClient) { }
	
	tokenizer() {
		this.http.get(this.uri+"/token").subscribe(data =>{
			localStorage.setItem('token', data["token"]);
  		});
	}
	getToken(){
		return localStorage.getItem("token");
	}

	getHeaders(){
		return new HttpHeaders({
     		'Content-Type' : 'application/json',
     		'Authorization': "Bearer "+this.getToken()
		});
	}

	listCourses(){
		return this.http.get(this.uri+"/courses/all",{ headers: this.getHeaders() });
	}

	listStudents(){
		return this.http.get(this.uri+"/students/all", {headers: this.getHeaders() });
	}

	addCourse(data){
		return this.http.post(this.uri+"/courses",data, { headers: this.getHeaders() });
	}

	addStudent(data){
		return this.http.post(this.uri+"/students",data, { headers: this.getHeaders() });
	}

	getCourse(id){
		return this.http.get(this.uri+"/courses/"+id, { headers: this.getHeaders() });
	}
	getStudent(id){
		return this.http.get(this.uri+"/students/"+id, { headers: this.getHeaders() });
	}
	editCourse(id,data){
		return this.http.put(this.uri+"/courses/"+id,data, { headers: this.getHeaders() });
	}
	editStudent(id,data){
		return this.http.put(this.uri+"/students/"+id,data, { headers: this.getHeaders() });
	}

}
