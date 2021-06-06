<?php

namespace Modules\Api\Http\Controllers;

use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\Dto\LoginDto;
use Modules\Auth\Dto\RegistrationDto;
use Modules\Auth\Requests\LoginUserRequest;
use Modules\Auth\Requests\RegisterUserRequest;
use Modules\Auth\Services\AuthService;
use Modules\User\Services\UserService;

class AuthController extends Controller
{
    /**
     * @var AuthService
     */
    private $service;

    /**
     * @var UserService
     */
    private $userService;

    public function __construct(
        AuthService $service,
        UserService $userService
    )
    {
        $this->service = $service;
        $this->userService = $userService;
    }

    /**
     * @OA\Post(
     *     path="/auth/register",
     *     operationId="authRegister",
     *     summary="Регистраци пользователя и получение токена",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       type="object",
     *       required={"login","email","password"},
     *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *       @OA\Property(property="login", type="string", example="user1"),
     *       @OA\Property(property="password", type="string", example="qwer1234")
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Регистрация прошла успешно"
     * ))
     */

    /**
     * @param RegisterUserRequest $request
     * @return mixed
     */
    public function register(RegisterUserRequest $request)
    {
        $dto = RegistrationDto::populateByArray($request->all());
        $user = $this->userService->create($dto);
        $token = $this->service->createToken($user);

        return response()->success('Registration was successful!',
            [
                'user'  => $user,
                'token' => $token
            ]);
    }

    /**
     * @param LoginUserRequest $request
     * @return mixed
     * @throws Exception
     */
    public function login(LoginUserRequest $request)
    {
        $dto = LoginDto::populateByArray($request->all());
        $user = $this->userService->getUserByEmail($dto->email);
        $token = $this->service->authenticate($user, $dto);

        return response()->success('Login was successful!',
            [
                'user'  => $user,
                'token' => $token
            ]);
    }

    /**
     * @return mixed
     */
    public function logout()
    {
        $user = Auth::user();
        $this->service->dropUserTokens($user);

        return response()->success('Logout was successful!', []);
    }
}
