<?php

namespace App\Controller;


use App\Classe\Mail;
use App\Entity\User;
use App\Form\RegisterType;
use ContainerJloGXm5\getValidator_EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


class RegisterController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/register', name: 'app_register')]
    public function index(Request $request, UserPasswordHasherInterface $encoder): Response
    {
        $notification = null;

        $user = new User();

        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();
            $email = $user->getEmail();

            $password = $encoder->hashPassword($user, $user->getPassword());

            $user->setPassword($password);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $mail = new Mail();
            $content = "Bonjour" . $user->getFirstname() . "<br>Bienvenue dans la Villa du Miel.";
            $mail->send($email, $user->getFirstname(), 'Bienvenue sur la villa du miel', $content);

            $this->addFlash('success', "Votre inscription s'est correctement déroulée. Vous pouvez dès à present vous connecter à votre compte.");
            return $this->redirectToRoute('app_login');
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }
}
