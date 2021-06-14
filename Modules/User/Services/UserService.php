<?php

namespace Modules\User\Services;

use Exception;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Dto\RegistrationDto;
use Modules\User\Repositories\UserRepository;
use Modules\User\Factories\UserFactory;
use Modules\User\Models\User;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserService
{
    /**
     * @var UserFactory
     */
    private $factory;

    /**
     * @var UserRepository
     */
    private $repostory;

    public function __construct(
        UserFactory $factory,
        UserRepository $repostory
    )
    {
        $this->factory = $factory;
        $this->repostory = $repostory;
    }

    /**
     * @param string $email
     *
     * @return User
     * @throws Exception
     */
    public function getUserByEmail(string $email): User
    {
        $user = $this->repostory->getByEmail($email);

        if (!$user) {
            throw new NotFoundHttpException('User not found');
        }

        return $user;
    }

    /**
     * @param RegistrationDto $dto
     * @return User
     */
    public function create(RegistrationDto $dto): User
    {
        $user = $this->factory->create();
        $this->populate($user, $dto);
        $user->save();

        return $user;
    }

    /**
     * @param User $user
     * @param RegistrationDto $dto
     */
    public function populate(User $user, RegistrationDto $dto): void
    {
        $user->fill($dto->toArray());
        $user->password = Hash::make($dto->password);
    }
}
