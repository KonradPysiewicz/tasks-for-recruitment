<?php

namespace App\Controller;

use App\DTO\UserDTO;
use App\Service\UserService;
use PHPUnit\Util\Json;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class Zad3Controller extends AbstractController
{
    public function __construct(
        private readonly UserService $userService,
    ) {
    }

    #[Route('/', name: 'app_zad3')]
    public function index(): Response
    {
        return $this->render('zad3/index.html.twig');
    }

    /**
     * @return JsonResponse
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    #[Route('/zad3/users', name: 'zad_3_users')]
    public function getUsersAjaxRequest(): JsonResponse
    {
        $users = $this->userService->getUsers();

        return new JsonResponse($users);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    #[Route('/zad3/user', name: 'zad_3_user')]
    public function getUserAjaxRequest(Request $request): JsonResponse
    {
        $userName = $request->query->get('userName');
        $users = $this->userService->getUsers();

        if ($userName === ''){
            return new JsonResponse([]);
        }

        $userFound = $this->userService->userFound($users, $userName);

        if ($userFound === false){
            return new JsonResponse([]);
        }

        return new JsonResponse($userFound);
    }

    #[Route('/zad3/editUser', name: 'zad_3_edit_user')]
    public function editUserAjaxRequest(Request $request): JsonResponse|array
    {
        $userDTO = new UserDTO();
        $userDTO->setId($request->query->get('inputId'));
        $userDTO->setName($request->query->get('inputName'));
        $userDTO->setEmail($request->query->get('inputEmail'));
        $userDTO->setGender($request->query->get('inputGender'));
        $userDTO->setStatus($request->query->get('inputStatus'));

        return new JsonResponse($this->userService->editUser($userDTO));
    }
}
