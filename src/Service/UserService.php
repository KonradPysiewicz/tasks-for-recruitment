<?php

namespace App\Service;

use App\DTO\UserDTO;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class UserService
{
    public function __construct(
        private readonly UserServiceREST $userServiceREST,
    ) {
    }

    /**
     * @return array
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getUsers(): array
    {
        return $this->userServiceREST->getUsers();
    }

    public function editUser(UserDTO $userDTO): UserDTO|array
    {
        return $this->userServiceREST->editUser($userDTO);
    }

    public function userFound(
       array $users,
       string $userName
    ): bool|UserDTO
    {
        foreach ($users as $user) {
            if (str_contains($user->getName(), $userName)) {
                return $user;
            }
        }
        return false;
    }
}
