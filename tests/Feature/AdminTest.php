<?php

namespace Tests\Feature;

use App\Models\Users;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use JWTAuth;
use DB;
@define('EMAIL',rand(10,10000).'@news.com');
class AdminTest extends TestCase
{
    public function test_admin_add()
    {
        $response1 = $this->post('/api/news/admin', [
            'name'     => 'Admin1',
            'email'    => EMAIL,
            'password' => 123456,
        ])->assertStatus(201)->getOriginalContent();
    }

	public function test_admin_add_validation_error()
    {
        $response = $this->post('/api/news/admin', [
            'name'     => 'Admin1',
            'password' => 123456,
        ])->assertStatus(400);

        $this->assertEquals($response['errMsg'], 'email or name or password missing');
    }

    public function test_admin_login_invalid()
    {
        $response1 = $this->post('/api/news/admin/login', [
			'email'    => EMAIL,
			'password' => '123456',
        ]);

       $token = auth()->attempt(['email' => EMAIL, 'password' => '123456']);

       if(!$token)
	   {
        	$this->assertEquals($response['errMsg'], 'incorrect email or password');
       }
       @define('TOKEN', $token);
       $response1->assertStatus(200)->getOriginalContent();
    }

	public function test_admin_login()
    {
        $response1 = $this->post('/api/news/admin/login', [
			'email'    => EMAIL,
			'password' => 123456,
        ])->assertStatus(200)->getOriginalContent();

		$headers = [
			'Authorization' => 'Bearer ' . $response1
		];

		$response1 = $this->post('/api/news/add', [
			'publisher'   => 'Demo Publisher',
			'description' => 'news in detail',
			'title' 	  => 'News Headline',
			'clicks' 	  => 123,
		],$headers)->assertStatus(201)->getOriginalContent();
    }

    public function test_add_news()
    {
        $headers = [
        'Authorization' => 'Bearer ' . TOKEN
        ];

        $response1 = $this->post('/api/news/add', [
            'publisher'   => 'Demo Publisher',
            'description' => 'news in detail',
            'title' 	  => 'News Headline',
            'clicks' 	  => 123,
        ],$headers)->assertStatus(201)->getOriginalContent();

        $this->assertDatabaseHas('news', [
            'publisher'   => $response1->publisher,
            'description' => $response1->description,
            'title'       => $response1->title,
            'clicks'      => $response1->clicks,
        ]);

        $response2 = $this->post('/api/news/add', [
            'publisher'   => 'Demo Publisher2',
            'description' => 'news in detail',
            'title'       => 'News Headline',
            'clicks'      => 123,
        ],$headers)->assertStatus(201)->getOriginalContent();

        $this->assertDatabaseHas('news', [
           'publisher'    => $response2->publisher,
            'description' => $response2->description,
            'title'       => $response2->title,
            'clicks'      => $response2->clicks,
        ]);

        $this->assertEquals($response1->id, $response2->id - 1);
    }

	public function test_add_news_validation_error()
    {
        $headers = [
        'Authorization' => 'Bearer ' . TOKEN
        ];

        $response1 = $this->post('/api/news/add', [
            'publisher'   => 'Demo Publisher',
            'description' => 'news in detail',
            'title' 	  => 'News Headline'
        ],$headers)->assertStatus(400)->getOriginalContent();

		$this->assertEquals($response1['errMsg'], 'MandatoryFieldsNotComplete');
    }
}
