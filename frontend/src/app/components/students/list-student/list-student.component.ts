import {
	Component,
	OnInit,
	Inject
} from '@angular/core';
import {
	MatDialog,
	MatDialogRef,
	MAT_DIALOG_DATA
} from '@angular/material/dialog';
import {
	ApiService
} from "../../../service/api.service";

class DialogData {
	id: number;
	name: string;
	lastName: string;
	rut: string;
	age: number;
	course: number;
}

@Component({
	selector: 'app-list-student',
	templateUrl: './list-student.component.html',
	styleUrls: ['./list-student.component.css']
})
export class ListStudentComponent implements OnInit {

	students: any = [];
	columnsToDisplay = ['name', "lastName", "rut", "age", "id"];
	constructor(private api: ApiService, private dialog: MatDialog) {}

	openDialog(data: any = null): void {
		const dialogRef = this.dialog.open(AddStudentComponent, {
			width: '250px',
			data: data == null ? new DialogData() : data
		});

		dialogRef.afterClosed().subscribe(result => {
			if (result != undefined) {
				if (!("id" in result)) {
					this.api.addStudent(result).subscribe(x => {
						this.ngOnInit();
					});
				} else {
					this.api.editStudent(result["id"], result).subscribe(x => {
						this.ngOnInit();
					});
				}
			}

		});
	}

	edit(id): void {
		const student = this.students.find((x) => x.id == id);
		this.openDialog({ ...student
		});
	}

	ngOnInit(): void {
		this.api.listStudents().subscribe(st => {
			this.students = st;
		});

	}

}

@Component({
	selector: 'add-student-dialog',
	templateUrl: 'add-student.html',
})
export class AddStudentComponent implements OnInit {

	courses: any = [];
	constructor(
		public dialogRef: MatDialogRef < AddStudentComponent > ,
		@Inject(MAT_DIALOG_DATA) public data: DialogData, private api: ApiService) {}

	onNoClick(): void {
		this.dialogRef.close();
	}

	ngOnInit() {
		this.api.listCourses().subscribe(c => {
			this.courses = c;
		})
	}

}

