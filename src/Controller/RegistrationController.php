<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Account;
use App\Entity\Person;
use App\Entity\Phone;
use App\Form\RegistrationFormType;
use App\Form\PersonFormType;
use App\Form\PhoneFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em): Response
    {
        $accountRepository = $em->getRepository(Account::class);
        $user = new User();
        $person = new Person();
        $phone = new Phone();
        $registerForm = $this->createForm(RegistrationFormType::class, $user);
        $personForm = $this->createForm(PersonFormType::class, $person);
        $phoneForm = $this->createForm(PhoneFormType::class, $phone);
        $registerForm->handleRequest($request);
        $personForm->handleRequest($request);
        $phoneForm->handleRequest($request);

        if ($registerForm->isSubmitted() && $registerForm->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $registerForm->get('password')->getData()
                )
            );

            // Always register users as Tutors. Other types of accounts are going to be created by
            // an administrator.
            $account = $accountRepository->getAccountByName("tutor");
            $user->setAccount($account);
            $person->setPhone($phone);
            $user->setPerson($person);
            $user->setRoles(['ROLE_TUTOR']);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email
            $this->addFlash('success', 'Registro exitoso, ahora podra ingresar a su portal!');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $registerForm->createView(),
            'personForm' => $personForm->createView(),
            'phoneForm' => $phoneForm->createView()
        ]);
    }
}
