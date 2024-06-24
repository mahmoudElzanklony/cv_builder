<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test($name,$age,$sal){
        echo 'name====>'.$name.'<br>';
        echo 'age====>'.$age;
        echo 'sal====>'.$sal;
    }
    public function test_data(){
        $this->test(name:'ali',sal:4000,age:30);
    }
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
