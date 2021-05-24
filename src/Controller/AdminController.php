<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ResearchUserForm;
use App\Form\UserForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends AbstractController
{
    /**
     * @Route("/{_locale}/admin", name="admin_index")
     */
    public function index(Request $request): Response
    {
        $users = $this->getDoctrine()->getManager()
            ->getRepository(User::class)
            ->findAll();

        $searchForm = $this->createForm(ResearchUserForm::class);
        $searchForm->handleRequest($request);

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $data = $searchForm->getData()['research'];
            $em = $this->getDoctrine()->getManager();

            $users = $em->getRepository(User::class)->searchUser($data);
        }

        return $this->render('Admin/index.html.twig', [
            'users' => $users,
            'researchForm' => $searchForm->createView(),
        ]);
    }

    /**
     * @Route("/{_locale}/admin/createUser", name="admin_create_user")
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $userPasswordEncoder
    ): Response {
        $user = new User();

        $registerForm = $this->createForm(UserForm::class, $user);
        $registerForm->handleRequest($request);

        if ($registerForm->isSubmitted() && $registerForm->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $user->setPassword(
                $userPasswordEncoder->encodePassword($user, $user->getPassword())
            );
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('admin_index');
        }

        return $this->render('Admin/addUser.html.twig', [
            'form' => $registerForm->createView(),
            'user' => $user
        ]);
    }

    /**
     * @Route("/{_locale}/admin/modifyUser/{id}", name="admin_modify_user")
     */
    public function modifyUser(User $user, Request $request, UserPasswordEncoderInterface $userPasswordEncoder): Response
    {
        $userForm = $this->createForm(UserForm::class, $user);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $user->setPassword(
                $userPasswordEncoder->encodePassword($user, $user->getPassword())
            );
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('admin_index');
        }

        return $this->render('Admin/modifyUser.html.twig', [
            'form' => $userForm->createView(),
            'user' => $user
        ]);
    }

    /**
     * @Route("/admin/deleteUser/{id}", name="admin_delete_user")
     */
    public function deleteUser(User $user): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('admin_index');
    }

    /**
     * @Route("/admin/setAdmin/{id}", name="admin_set_admin")
     */
    public function setAdmin(User $user): Response
    {
        $em = $this->getDoctrine()->getManager();
        $user->setRoles(['ROLE_ADMIN']);
        $em->flush();

        return $this->redirectToRoute('admin_index');
    }
}
