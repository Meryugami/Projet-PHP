<?php
        //Fonction et procédures liée a l'utilisateur
namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Event;
use App\Entity\User;
use App\Form\UserForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/{_locale}/profile", name="profile")
     */

    //Affiche tout les utilisateurs (pour l'admin)
    public function index(): Response
    {
        $user = $this->getUser();

        $events = $this->getDoctrine()->getManager()
            ->getRepository(Event::class)
            ->findBy(['user' => $user], ['dateStart' => 'ASC']);

        $comments = $this->getDoctrine()->getManager()
            ->getRepository(Comment::class)
            ->findBy(['user' => $user], [], 5, 0);

        return $this->render('Profile/index.html.twig', [
            'events' => $events,
            'comments' => $comments
        ]);
    }

    /**
     * @Route("/user/modifyInformations/{id}", name="user_modify_informations")
     */

        //Modification des informations d'un utilsateur
    public function modifyInformations(User $user, Request $request, UserPasswordEncoderInterface $userPasswordEncoder): Response
    {
        $userForm = $this->createForm(UserForm::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $user->setPassword(
              $userPasswordEncoder->encodePassword($user, $user->getPassword())
            );

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('Profile/modifyInformations.html.twig', [
            'userForm' => $userForm->createView()
        ]);
    }


}
