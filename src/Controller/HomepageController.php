<?php

namespace App\Controller;

use App\Service\UserService;
use App\Service\CurrencyService;
use App\Form\RegistrationFormType;
use App\Form\LoginFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class HomepageController extends AbstractController
{
    private AuthenticationUtils $authenticationUtils;

    private UserService $userService;

    private CurrencyService $currencyService;

    private FormFactoryInterface $formFactory;

    public function __construct(
        AuthenticationUtils   $authenticationUtils,
        UserService           $userService,
        CurrencyService       $currencyService,
        FormFactoryInterface  $formFactory
    ) {
        $this->authenticationUtils = $authenticationUtils;
        $this->userService         = $userService;
        $this->currencyService     = $currencyService;
        $this->formFactory         = $formFactory;
    }

    #[Route('/', name: 'homepage')]
    public function homepage(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $currencyList = [];
        $date = $request->query->get('date');
        $dateToday = (new \DateTime())->format('Y-m-d');

        if ($date === null || $date === '') {
            $date = $dateToday;
        }

        $currencyList = $this->currencyService->getCurrencyListByDate($date);

        return $this->render('homepage.html.twig', [
            'currencyList' => $currencyList,
            'dateToday'    => $dateToday,
        ]);
    }

    #[Route('/login', name: 'login')]
    public function login(): Response
    {
        $error = $this->authenticationUtils->getLastAuthenticationError();

        if (! empty($error)) {
            $error = 'Неверная почта или пароль';
        }

        $form = $this->formFactory->createNamed('', LoginFormType::class);

        return $this->renderForm('user/user_login.html.twig', [
            'form'  => $form,
            'error' => $error
        ]);
    }

    #[Route('/register', name: 'register')]
    public function register(Request $request): Response
    {
        $form = $this->createForm(RegistrationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form
                ->get('email')
                ->getData();
            $password = $form
                ->get('plainPassword')
                ->getData();

            $this
                ->userService
                ->register($email, $password);

            return $this->redirectToRoute('login');
        }

        return $this->renderForm('user/user_register.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/logout', name: 'logout')]
    public function logout()
    {
        throw $this->createNotFoundException('Don\'t forget to activate logout');
    }

    #[Route('/populateTheDb', name: 'populateTheDb')]
    public function populateTheDb(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        try {
            if ($this->currencyService->populateTheDb()) {
                $this->addFlash('success', 'База данных обновлена');
            } else {
                $this->addFlash('success', 'База данных в актуальном состоянии');
            }
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('homepage');
    }
}