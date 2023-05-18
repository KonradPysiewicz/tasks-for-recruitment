<?php

namespace App\Service;

use App\DTO\UserDTO;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class UserServiceREST
{
    private Serializer $serializer;

    public function __construct(
        private readonly HttpClientInterface $client,
    ) {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ArrayDenormalizer(), new ObjectNormalizer()];
        $this->serializer = new Serializer($normalizers, $encoders);
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
        $response = $this->client->request(
            'GET',
            'https://gorest.co.in/public/v2/users'
        );

        if ($response->getStatusCode() !== 200){
            return [];
        }

        $users = $this->serializer->deserialize($response->getContent(), UserDTO::class.'[]', 'json');

        return $users;
    }

    public function editUser(UserDTO $userDTO): UserDTO|array
    {
        $response = $this->client->request(
            'PUT',
            'https://gorest.co.in/public/v2/users/'.$userDTO->getId(),
            [
                'headers' => ['Authorization' => 'Bearer 5ef9148c34d0ac6ab1e9b78ee95509c6c9bba09b4c6f66c9ff751c81766b4922'],
                'body' => [
                    'name' => $userDTO->getName(),
                    'email' => $userDTO->getEmail(),
                    'gender' => $userDTO->getGender(),
                    'status' => $userDTO->getStatus()
                ]
            ]
        );

        if ($response->getStatusCode() !== 200){
            return [];
        }

        $user = $this->serializer->deserialize($response->getContent(), UserDTO::class, 'json');

        return $user;
    }
}
