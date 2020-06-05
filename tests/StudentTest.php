<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class StudentTest extends TestCase
{
    use DatabaseMigrations;

    private $token;
    private $headers;
    
    protected function setUp() :void {
        parent::setUp();
        $response = $this->call('GET', '/token');
        $this->token = $response["token"];
        
        $this->headers= ["Authorization" => $this->token];
    }

    public function testValidatorsStudent(){
        $headers = $this->headers;
        #add normal course to add student
        $response = $this->json('POST','/courses',
            [
                "name" => "course 1",
                "code" => "1234"
            ]
            ,$headers);
        $this->assertEquals(201, $response->response->status());

        #add student with existing course
        $response = $this->json('POST','/students',
            [
                "name" => "Alberto",
                "lastName"=> "Zenteno",
                "rut" => "22222222-2",
                "course" => 1,
                "age" => 19
            ]
            ,$headers);
        $this->assertEquals(201, $response->response->status());

        #add student with non-existing course
        $response = $this->json('POST','/students',
            [
                "name" => "Alberto",
                "lastName"=> "Zenteno",
                "rut" => "22222222-2",
                "course" => 2,
                "age" => 19
            ]
            ,$headers);
        $this->assertEquals(400, $response->response->status());

        #add student with existing course and underage
        $response = $this->json('POST','/students',
            [
                "name" => "Alberto",
                "lastName"=> "Zenteno",
                "rut" => "22222222-2",
                "course" => 1,
                "age" => 18
            ]
            ,$headers);
        $this->assertEquals(400, $response->response->status());

        #add student without one of the parameters
        $response = $this->json('POST','/students',
            [
                "name" => "Alberto",
                "lastName"=> "Zenteno",
                "rut" => "22222222-2",
                "age" => 19
            ]
            ,$headers);
        $this->assertEquals(400, $response->response->status());

        #add student with non integer age
        $response = $this->json('POST','/students',
            [
                "name" => "Alberto",
                "lastName"=> "Zenteno",
                "rut" => "22222222-2",
                "age" => "19a",
                "course"=> 1
            ]
            ,$headers);
        $this->assertEquals(400, $response->response->status());

        #add student with non integer course
        $response = $this->json('POST','/students',
            [
                "name" => "Alberto",
                "lastName"=> "Zenteno",
                "rut" => "22222222-2",
                "age" => 19,
                "course"=> "1b"
            ]
            ,$headers);
        $this->assertEquals(400, $response->response->status());

        #add student with invalid rut
        $response = $this->json('POST','/students',
            [
                "name" => "Alberto",
                "lastName"=> "Zenteno",
                "rut" => "22222222-3",
                "age" => 19,
                "course"=> 1
            ]
            ,$headers);
        $this->assertEquals(400, $response->response->status());

        #add student with valid rut using dots
        $response = $this->json('POST','/students',
            [
                "name" => "Alberto",
                "lastName"=> "Zenteno",
                "rut" => "22.222.222-2",
                "age" => 19,
                "course"=> 1
            ]
            ,$headers);
        $this->assertEquals(201, $response->response->status());

        #add student with valid rut dash K lowercase
        $response = $this->json('POST','/students',
            [
                "name" => "Alberto",
                "lastName"=> "Zenteno",
                "rut" => "19997050-k",
                "age" => 19,
                "course"=> 1
            ]
            ,$headers);
        $this->assertEquals(201, $response->response->status());

        #add student with valid rut dash K uppercase
        $response = $this->json('POST','/students',
            [
                "name" => "Alberto",
                "lastName"=> "Zenteno",
                "rut" => "19997050-K",
                "age" => 19,
                "course"=> 1
            ]
            ,$headers);
        $this->assertEquals(201, $response->response->status());


        #add student with valid rut dash K with dots
        $response = $this->json('POST','/students',
            [
                "name" => "Alberto",
                "lastName"=> "Zenteno",
                "rut" => "19.997.050-K",
                "age" => 19,
                "course"=> 1
            ]
            ,$headers);
        $this->assertEquals(201, $response->response->status());
    }

    public function testListStudentWithToken(){
        $headers = $this->headers;
        $response = $this->json("GET",'/students/all',[],$headers);
        $this->assertEquals(200, $response->response->status());


        $response = $this->json("GET",'/students',[],$headers);
        $this->assertEquals(200, $response->response->status());
    }

    public function testListStudentWithOutToken(){
        $response = $this->json("GET",'/students/all');
        $this->assertEquals(401, $response->response->status());
    }

    public function testCRUD(){
        # I create 1 course and 2 students
        $headers = $this->headers;
        
        $response = $this->json('POST','/courses',[
            "name" => "Mathematics",
            "code" => "ALPH",
        ],$headers);
        
        $response = $this->json('POST','/students',
            [
                "name" => "Alberto",
                "lastName"=> "Zenteno",
                "rut" => "19997050-k",
                "age" => 19,
                "course"=> 1
            ]
            ,$headers);
        $response = $this->json('POST','/students',
            [
                "name" => "Alberto",
                "lastName"=> "Zenteno",
                "rut" => "33.333.333-3",
                "age" => 19,
                "course"=> 1
            ]
            ,$headers);

		#it should return then only two elements created
        $response = $this->json('GET','/students/all',[],$headers);
        $elements = json_decode($response->response->getContent());
        $this->assertEquals(2, count($elements));

        #delete course with existing students
        $response = $this->json('DELETE','/courses/1',[],$headers);
        $this->assertEquals(400, $response->response->status());

        #delete existing element
        $response = $this->json('DELETE','/students/1',[],$headers);
        $this->assertEquals(200, $response->response->status());

        #delete non-existing element
        $response = $this->json('DELETE','/students/1',[],$headers);
        $this->assertEquals(404, $response->response->status());

        #update non-existing element
        $response = $this->json('PUT','/students/1',[
        	"name" => "Juanito",
            "lastName"=> "Zapata",
            "rut" => "33.333.333-3",
            "age" => 19,
            "course"=> 1
        ],$headers);
        $this->assertEquals(404, $response->response->status());

        #update existing element
        $response = $this->json('PUT','/students/2',[
            "name" => "Juanito",
            "lastName"=> "Zapata",
            "rut" => "33.333.333-3",
            "age" => 19,
            "course"=> 1
        ],$headers);
        $this->assertEquals(200, $response->response->status());

        #update existing element with invalid parameter (age ander 19)
        $response = $this->json('PUT','/courses/2',[
            "name" => "Juanito",
            "lastName"=> "Zapata",
            "rut" => "33.333.333-3",
            "age" => 18,
            "course"=> 1
        ],$headers);
        $this->assertEquals(400, $response->response->status());

        #return non-existing element
        $response = $this->json('GET','/students/1',[],$headers);
        $this->assertEquals(404, $response->response->status());

        #return existing element
        $response = $this->json('GET','/students/2',[],$headers);
        $this->assertEquals(200, $response->response->status());

        #return existing element, delete and try returning again

            #return existing element
            $response = $this->json('GET','/students/2',[],$headers);
            $this->assertEquals(200, $response->response->status());

            #delete existing element
            $response = $this->json('DELETE','/students/2',[],$headers);
            $this->assertEquals(200, $response->response->status());

            #return element again
            $response = $this->json('GET','/students/2',[],$headers);
            $this->assertEquals(404, $response->response->status());

        #delete course without existing students
        $response = $this->json('DELETE','/courses/1',[],$headers);
        $this->assertEquals(200, $response->response->status());

    }

}
