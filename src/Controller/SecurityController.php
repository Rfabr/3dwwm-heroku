<?php

namespace App\Controller;

use App\Class\Mail;
use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/inscription", name="app_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $crypter): Response
    {
        $notification = null;

        $form = $this->createForm(RegisterType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $user = $form->getData();

            $search_email = $this->entityManager->getRepository(User::class)
                                                ->findOneByEmail($user->getEmail());

            if(!$search_email)
            {
                $user->setPassword($crypter->hashPassword($user, $user->getPassword()));

                $manager = $this->getDoctrine()
                                ->getManager();

                $manager->persist($user);

                $manager->flush();

                $mail = new Mail();

                $content = "Bonjour".$user->getFirstname()."<br>Bienvenue sur 3DWWM, le spécialiste de l'impression 3D pour tous !<br><br>Votre inscription a bien été prise en compte, vous pouvez désormais vous connecter à votre compte.<br><br>À bientôt !";

                $mail->send($user->getEmail(), $user->getFirstname(), 'Bienvenue sur 3DWWM', $content);

                return $this->redirectToRoute('app_login');
            }
            else
            {
                $notification = "Erreur : l'email renseigné existe déjà.";
            }
        }
        
        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }

    /**
     * @Route("/connexion", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser())
        {
            return $this->redirectToRoute('app_frontend_account_index');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/déconnexion", name="app_logout")
     * @IsGranted("ROLE_USER")
     */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
