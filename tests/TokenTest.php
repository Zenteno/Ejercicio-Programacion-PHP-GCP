<?php

class TokenTest extends TestCase
{
    public function testToken(){
		$response = $this->call('GET', '/token');
		$this->assertEquals(200, $response->status());
		$this->assertArrayHasKey("token",$response);
    }
}
