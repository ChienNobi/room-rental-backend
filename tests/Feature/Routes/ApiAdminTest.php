<?php

namespace Tests\Feature\Routes;

use App\Models\Admin;
use Database\Seeders\AdminSeeder;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tests\Traits\InteractsWithUsers;
use Tests\Traits\InteractWithDomain;

class ApiAdminTest extends TestCase
{
    use InteractWithDomain, InteractsWithUsers, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('passport:install', ['-vvv' => true]);
        Artisan::call('passport:client', ['--password' => true, '--provider' => 'admins', '--name' => 'admins']);
        $this->seed(PermissionSeeder::class);
        $this->seed(AdminSeeder::class);

        $this->setUpDomain(env('BACKEND_LOCAL_DOMAIN'));
    }

    public function test_health_check_route()
    {
        $response = $this->get($this->getUrl('/'));

        $response
            ->assertOk()
            ->assertExactJson([
                'status' => 'OK'
            ]);
    }

    public function test_admin_register_route()
    {
        $user = Admin::factory()->preRegister()->make();
        $userData = $user->getAttributes();

        $response = $this->post($this->getUrl('/register'), $userData);

        $response
            ->assertOk()
            ->assertJson(Arr::except($user->toArray(), 'email_verified_at'));
    }

    public function test_admin_register_route_duplicated()
    {
        $userData = Admin::factory()->preRegister()->create()->getAttributes();

        $response = $this->post($this->getUrl('/register'), $userData);

        $response
            ->assertUnprocessable()
            ->assertJson([
                'code' => 'InvalidParametersException',
                'errors' => [
                    'messages' => [
                        'username' => [__('validation.unique', ['attribute' => 'username'])],
                        'email' => [__('validation.unique', ['attribute' => 'email'])],
                    ]
                ]
            ])
            ->assertJsonStructure(['code', 'message', 'errors']);
    }

    public function test_admin_login_route()
    {
        $password = '123456';
        $user = Admin::factory()->withPassword($password)->withDeleted()->create();

        $response = $this->post($this->getUrl('/login'), [
            'username' => $user->username,
            'password' => $password
        ]);

        $response
            ->assertOk()
            ->assertJsonStructure([
                'token' => ['token_type', 'expires_in', 'access_token', 'refresh_token'],
                'user' => []
            ])
            ->assertJson([
                'token' => ['token_type' => 'Bearer']
            ]);
        $this->assertLessThanOrEqual($response['token']['expires_in'], 31536000);
    }

    public function test_admin_login_route_wrong_info()
    {
        $password = '123456';
        $user = Admin::factory()->withPassword($password)->withDeleted()->create();

        $response = $this->post($this->getUrl('/login'), [
            'username' => $user->username,
            'password' => $password . 7
        ]);

        $response
            ->assertForbidden()
            ->assertJson([
                'code' => 'UnauthorizedException',
                'message' => __('api.exception.invalid_credentials')
            ])
            ->assertJsonStructure(['code', 'message']);
    }

    public function test_admin_logout_route()
    {
        $this->setUpUser(Admin::class);
        $this->login();
        $response = $this->post($this->getUrl('/logout'));

        $response
            ->assertOk()
            ->assertJson([
                'message' => 'OK'
            ]);
    }

    public function test_admin_logout_route_unauthenticated()
    {
        $response = $this->post($this->getUrl('/logout'));

        $response
            ->assertUnauthorized()
            ->assertJson([
                'code' => 'UnauthenticatedException',
                'message' => 'You are not authorized to perform this action!'
            ])
            ->assertJsonStructure(['code', 'message']);
    }

    public function test_admin_refresh_token_route()
    {
        $password = '123456';
        $this->setUpUser(Admin::class, ['password' => Hash::make($password)]);

        $loginResponse = $this->post($this->getUrl('/login'), [
            'username' => $this->user->username,
            'password' => $password
        ]);
        $refreshToken = $loginResponse['token']['refresh_token'];

        $refreshResponse = $this->post($this->getUrl('/refresh_token'), [
            'refresh_token' => $refreshToken,
        ]);

        $refreshResponse
            ->assertOk()
            ->assertJsonStructure(['token_type', 'expires_in', 'access_token', 'refresh_token'])
            ->assertJson([
                'token_type' => 'Bearer'
            ]);
        $this->assertLessThanOrEqual($refreshResponse['expires_in'], 31536000);
    }

    public function test_admin_profile_route()
    {
        $this->setUpUser(Admin::class);

        $response = $this->get($this->getUrl('/profile'));

        $response
            ->assertOk()
            ->assertJson([
                'user' => $this->user->toArray()
            ]);
    }

    public function test_admin_profile_route_unauthenticated()
    {
        $response = $this->get($this->getUrl('/profile'));

        $response
            ->assertUnauthorized()
            ->assertJson([
                'code' => 'UnauthenticatedException',
                'message' => 'You are not authorized to perform this action!'
            ])
            ->assertJsonStructure(['code', 'message']);
    }
}
