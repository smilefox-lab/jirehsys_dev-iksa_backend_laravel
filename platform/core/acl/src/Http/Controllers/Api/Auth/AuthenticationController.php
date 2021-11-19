<?php

namespace Botble\ACL\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Botble\ACL\Http\Requests\Api\LoginRequest;
use Botble\ACL\Repositories\Interfaces\UserInterface;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\ACL\Traits\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Token;

class AuthenticationController extends Controller
{
    use RegistersUsers;

    /**
     * @var UserInterface
     */
    protected $userRepository;

    /**
     * AuthenticationController constructor.
     *
     * @param UserInterface $userRepository
     */
    public function __construct(UserInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Login
     *
     * @bodyParam login string required The email/phone of the user.
     * @bodyParam password string required The password of user to create.
     *
     * @response {
     * "error": false,
     * "data": {
     *    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0xxx"
     * },
     * "message": null
     * }
     *
     * @group Authentication
     *
     * @param LoginRequest $request
     * @param BaseHttpResponse $response
     *
     * @return BaseHttpResponse
     */
    public function login(LoginRequest $request, BaseHttpResponse $response)
    {
        if (Auth::guard()->attempt([
            'email'    => $request->input('email'),
            'password' => $request->input('password'),
        ])) {
            $token = Auth::guard()->user()->createToken('Laravel Password Grant Client')->accessToken;

            return $response
                ->setData(['token' => $token]);
        }

        return $response
            ->setError()
            ->setCode(422)
            ->setMessage(__('El correo electrónico o la contraseña no son correctos!'));
    }

    /**
     * Logout
     *
     * @group Authentication
     * @authenticated
     *
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function logout(Request $request, BaseHttpResponse $response)
    {
        /**
         * @var Token $token
         */
        $token = $request->user()->token();
        $token->revoke();

        return $response
            ->setMessage(__('You have been successfully logged out!'));
    }
}
