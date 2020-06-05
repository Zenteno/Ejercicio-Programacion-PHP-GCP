<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class CourseTest extends TestCase
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

    public function testExample()
    {
       	$response = $this->call('GET', '/');
		$this->assertEquals(404, $response->status());
    }

    public function testToken(){
		$response = $this->call('GET', '/token');
		$this->assertEquals(200, $response->status());
		$this->assertArrayHasKey("token",$response);
    }

    public function testValidatorsCourse(){
        $headers = $this->headers;
        #add normal course
        $response = $this->json('POST','/courses',
            [
                "name" => "course 1",
                "code" => "1234"
            ]
            ,$headers);
        $this->assertEquals(201, $response->response->status());

        #course without name
        $response = $this->json('POST','/courses',
            [
                "code" => "1234"
            ]
            ,$headers);
        $this->assertEquals(400, $response->response->status());

        #course without code
        $response = $this->json('POST','/courses',
            [
                "name" => "course 1",
            ]
            ,$headers);
        $this->assertEquals(400, $response->response->status());

        #course without code or name
        $response = $this->json('POST','/courses',[]
            ,$headers);
        $this->assertEquals(400, $response->response->status());

        #course without code or name and parameter not required
        $response = $this->json('POST','/courses',[
            "param" => "i'm a param"
        ]
            ,$headers);
        $this->assertEquals(400, $response->response->status());

        #course with code and name and parameter not required
        $response = $this->json('POST','/courses',[
            "name" => "Mathematics",
            "code" => "ALPH",
            "param" => "I'm a param"
        ],$headers);
        $this->assertEquals(201, $response->response->status());

        #course with code of more than 4 digits
        $response = $this->json('POST','/courses',[
            "name" => "Mathematics",
            "code" => "ALPH1"
        ],$headers);
        $this->assertEquals(400, $response->response->status());

    }

    public function testListCourseWithToken(){
        $headers = $this->headers;
        $response = $this->json("GET",'/courses/all',[],$headers);
        $this->assertEquals(200, $response->response->status());

        $response = $this->json("GET",'/courses/all',[],$headers);
        $this->assertEquals(200, $response->response->status());
    }

    public function testListCourseWithOutToken(){
        $response = $this->json("GET",'/courses/all');
        $this->assertEquals(401, $response->response->status());
    }

    public function testCRUD(){
        # I create 2 courses
        $headers = $this->headers;
        
        $response = $this->json('POST','/courses',[
            "name" => "Mathematics",
            "code" => "ALPH",
        ],$headers);
        
        $response = $this->json('POST','/courses',[
            "name" => "Mathematics",
            "code" => "ALPH"
        ],$headers);

        #it should return then only two elements created
        $response = $this->json('GET','/courses/all',[],$headers);
        $elements = json_decode($response->response->getContent());
        $this->assertEquals(2, count($elements));

        #delete existing element
        $response = $this->json('DELETE','/courses/1',[],$headers);
        $this->assertEquals(200, $response->response->status());

        #delete non-existing element
        $response = $this->json('DELETE','/courses/1',[],$headers);
        $this->assertEquals(404, $response->response->status());

        #update non-existing element
        $response = $this->json('PUT','/courses/1',[
            "name"=>"New Name",
            "code"=>"NewC"
        ],$headers);
        $this->assertEquals(404, $response->response->status());

        #update existing element
        $response = $this->json('PUT','/courses/2',[
            "name"=>"New Name",
            "code"=>"NewC"
        ],$headers);
        $this->assertEquals(200, $response->response->status());

        #update existing element with invalid parameter (code lenght = 5)
        $response = $this->json('PUT','/courses/2',[
            "name"=>"New Name",
            "code"=>"NewCL"
        ],$headers);
        $this->assertEquals(400, $response->response->status());

        #return non-existing element
        $response = $this->json('GET','/courses/1',[],$headers);
        $this->assertEquals(404, $response->response->status());

        #return existing element
        $response = $this->json('GET','/courses/2',[],$headers);
        $this->assertEquals(200, $response->response->status());

        #return existing element, delete and try returning again

            #return existing element
            $response = $this->json('GET','/courses/2',[],$headers);
            $this->assertEquals(200, $response->response->status());

            #delete existing element
            $response = $this->json('DELETE','/courses/2',[],$headers);
            $this->assertEquals(200, $response->response->status());

            #return element again
            $response = $this->json('GET','/courses/2',[],$headers);
            $this->assertEquals(404, $response->response->status());

    }

}
