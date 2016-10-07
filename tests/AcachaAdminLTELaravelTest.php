<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;

/**
 * Class AcachaAdminLTELaravelTest.
 */
class AcachaAdminLTELaravelTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Test Landing Page.
     *
     * @return void
     */
    public function testLandingPage()
    {
        $this->visit('/admin')
             ->see('AdminLTE')
             ->see('Log in');
    }

    /**
     * Test Landing Page.
     *
     * @return void
     */
    public function testLandingPageWithUserLogged()
    {
        $user = factory(\App\Models\Admin::class)->create();

        $this->actingAs($user, 'admin')
            ->visit('/admin')
            ->see('Acacha')
            ->see('adminlte-laravel')
            ->see($user->username);
    }

    /**
     * Test Login Page.
     *
     * @return void
     */
    public function testLoginPage()
    {
        $this->visit('/admin/login')
            ->see('Sign in to start your session');
    }

    /**
     * Test Login.
     *
     * @return void
     */
    public function testLogin()
    {
        $user = factory(\App\Models\Admin::class)->create(['password' => Hash::make('passw0RD')]);

        $this->visit('/admin/login')
            ->type($user->username, 'username')
            ->type('passw0RD', 'password')
            ->press('Sign In')
            ->seePageIs('/admin')
            ->see($user->username);
    }

    /**
     * Test Login.
     *
     * @return void
     */
    public function testLoginRequiredFields()
    {
        $this->visit('/admin/login')
            ->type('', 'username')
            ->type('', 'password')
            ->press('Sign In')
            ->see('Log in');
    }

    /**
     * Test Register Page.
     *
     * @return void
     */
    public function testRegisterPage()
    {
        $this->visit('/admin/register')
            ->see('Register a new membership');
    }

    /**
     * Test home page is only for authorized Users.
     *
     * @return void
     */
    public function testHomePageForUnauthenticatedUsers()
    {
        $this->visit('/admin')
            ->seePageIs('/admin/login');
    }

    /**
     * Test home page works with Authenticated Users.
     *
     * @return void
     */
    public function testHomePageForAuthenticatedUsers()
    {
        $user = factory(\App\Models\Admin::class)->create();

        $this->actingAs($user, 'admin')
            ->visit('/admin')
            ->see($user->username);
    }

    /**
     * Test log out.
     *
     * @return void
     */
    public function testLogout()
    {
        $user = factory(\App\Models\Admin::class)->create();

        $this->actingAs($user, 'admin')
            ->visit('/admin/logout')
            ->seePageIs('/admin/login');
    }

    /**
     * Test user registration.
     *
     * @return void
     */
    public function testNewUserRegistration()
    {
        $this->visit('/admin/register')
            ->type('fortest', 'username')
            ->type('sergiturbadenas@gmail.com', 'email')
            ->check('terms')
            ->type('passw0RD', 'password')
            ->type('passw0RD', 'password_confirmation')
            ->press('Register')
            ->seePageIs('/admin')
            ->seeInDatabase('admin', ['email' => 'sergiturbadenas@gmail.com',
                                      'username'  => 'fortest', ]);
    }

    /**
     * Test required fields on registration page.
     *
     * @return void
     */
    public function testRequiredFieldsOnRegistrationPage()
    {
        $this->visit('/admin/register')
            ->press('Register')
            ->see('The username field is required')
            ->see('The email field is required')
            ->see('The password field is required');
    }
}
