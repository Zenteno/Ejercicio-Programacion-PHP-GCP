<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Student;

class StudentController extends Controller
{
    /**
     * Retrieve the student for the given ID.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $student = Student::find($id);
        return response($student, ($student!=null) ? 200:404)
                  ->header('Content-Type', 'application/json');
    }

    /**
     * Display a listing of students.
     *
     * @return Response
     */
    public function index()
    {
        return Student::all();
    }

    /**
     * Display a listing of students paginated.
     *
     * @return Response
     */
    
    public function indexPaginated(){
        $size = $request->input('size', 10);
        $page = $request->input('page',1);
        return Student::paginate($size,['*'],'page',$page);
    }


    /**
     * Store a newly created student in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $data = Student::create($request->all());
        return response('',201);
    }

    
    /**
     * Update the specified student in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $student = Student::find($id);
        if($student==null)
            return response(["msg"=>"not found"],404);
        else
            $student->fill($request->all());
        return response(["msg"=>"successfully updated"],200);
    }


    /**
     * Remove the specified student from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $student = Student::find($id);
        if($student==null)
            return response(["msg"=>"not found"],404);
        $student->delete();
        return response(["msg"=>"successfully deleted"],200);
    }
}