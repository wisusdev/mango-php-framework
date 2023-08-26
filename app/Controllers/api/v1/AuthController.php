<?php

namespace App\Controllers\api\v1;

use App\Controllers\BaseController;
use App\Models\User;
use App\Requests\LoginRequest;
use App\Requests\RegisterRequest;
use App\Requests\RestPasswordRequest;
use App\Resources\UserResource;
use App\Services\Mail;
use App\Traits\JWT;
use App\Traits\HttpResponse;

class AuthController extends BaseController
{
    use JWT, HttpResponse;

    private User $user;
    private RestPasswordRequest $restPasswordRequest;
    private LoginRequest $loginRequest;
    private RegisterRequest $registerRequest;

    public function __construct()
    {
        $this->user = new User();
        $this->loginRequest = new LoginRequest();
        $this->registerRequest = new RegisterRequest();
        $this->restPasswordRequest = new RestPasswordRequest();
    }

    public function login(): bool|string
	{
        $this->validateRequest('loginRequest');
        $request = $this->loginRequest->getBody();

        $user = $this->user->find(['email' => $request['email']]);

        if (!password_verify($request['password'], $user->password) || !$user) {
            return $this->sendError('User does not exist with this email address or password is incorrect');
        }

        $token = $this->generateToken($user->name);
        $this->user->update(['id' => $user->id], ['token' => $token]);

        return $this->sendSuccess([
            'user' => (new UserResource())->resource($user),
            'token' => $token
        ]);
    }

    public function register(): bool|string
	{
        $this->validateRequest('registerRequest');
        $request = $this->registerRequest->modifiedData();
        $user = $this->user->find(['email' => $request['email']]);

        if(!empty($user)) {
            return $this->sendError('This Email is already exits');
        }

        $request['token'] = $this->generateToken( $request['name'] );

        $insertedUser = $this->user->create($request);

        return $this->sendSuccess([
            'token' => $request['token'],
            'user' => (new UserResource())->resource($insertedUser)
        ]);
    }

    public function resetPassword(): bool|string
	{
        $this->validateRequest('restPasswordRequest');
        $request = $this->restPasswordRequest->getBody();
        $user = $this->user->find(['email' => $request['email']]);

        if(empty($user)) {
            return $this->sendError('This Email is not exits');
        }

		$token = $this->generateToken($user->name);
        (new Mail())->send($user->email, $token);
		$this->user->update(['id' => $user->id], ['token' => $token]);

        return $this->sendSuccess([],
			'The new token has been sent to your email address'
		);
    }

    private function validateRequest($request): void
	{
        $validation = $this->{$request}->validation();

        if(!empty($validation)) {
            $this->sendValidationError($validation);
            die();
        }
    }

    private function generateToken($name): string
    {
        return $this->generateJwt(['username'=> $name, 'exp'=>(time() + (24*60*60))]);
    }

}