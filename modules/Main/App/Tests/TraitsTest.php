<?php


namespace Main\App\Tests;


use Main\User\Model\User;

trait TraitsTest
{

    private $header = [];
    private $token ;
    private $autoId = 0;

    public function user($id)
    {
        return User::withTrashed()->find($id);
    }

    public function endUser()
    {
        return User::all()->last();
    }

    public function cUser($data)
    {
        $data['password'] = bcrypt($data['password']);
        return User::query()->create($data);
    }

    public function loginUser($username, $password, $password_confirmation = null)
    {
        $data = [
            "username" => $username,
            "password" => $password,
            "password_confirmation" => $password_confirmation ?? $password,
        ];

        $response = $this->postCustom(route('auth.login'), $data);

        $content = json_decode($response->content());
        isset($content->data) && $this->token = $content->data;
        return $response;
    }

    public function autoLoginUser()
    {
        $user = User::create(
            ['email' => "92@test.com" ,'password'=>bcrypt("123456") ,"verified_at" => now(), 'role' => 1]);
        $this->autoId = $user->id;
        return $this->loginUser($user->email, "123456");
    }

    /**
     * just run before start test
     * @return void
     */
    public function setUp(): void
    {

        parent::setUp();
        $this->artisan('migrate:reset');
        $this->artisan('migrate --seed');
    }


    /**
     * this is post method by header
     * @param $router
     * @param $data
     * @return mixed
     */
    public function postCustom($router, $data = [])
    {
        $this->setHeaderAuthorization();
        $this->setHeaderLocalization();
        $this->setHeaderAccess();
        return $this->post($router, $data, $this->header);
    }

    /**
     * this is get method by header
     * @param $router
     * @param $data
     * @return mixed
     */
    public function getCustom($router, $data = [])
    {
        $this->setHeaderAuthorization();
        $this->setHeaderLocalization();
        $this->setHeaderAccess();
        return $this->get($router, $this->header);
    }

    /**
     * this is delete method by header
     * @param $router
     * @param $data
     * @return mixed
     */
    public function deleteCustom($router, $data = [])
    {
        $this->setHeaderAuthorization();
        $this->setHeaderLocalization();
        $this->setHeaderAccess();
        return $this->delete($router, $data, $this->header);
    }

    /**
     * this is put method by header
     * @param $router
     * @param $data
     * @return mixed
     */
    public function putCustom($router, $data = [])
    {
        $this->setHeaderAuthorization();
        $this->setHeaderLocalization();
        $this->setHeaderAccess();
        return $this->put($router, $data, $this->header);
    }

    public function setHeaderAuthorization()
    {
        if ($this->token) {
            $this->header['Authorization'] = $this->token;
        }
    }

    public function setHeaderLocalization()
    {

        $this->header['X-localization'] = app()->getLocale();

    }

    public function setHeaderAccess()
    {
        $this->header['Accept'] = 'application/json';
    }


}
