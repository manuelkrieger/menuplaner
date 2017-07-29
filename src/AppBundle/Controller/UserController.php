<?php

namespace AppBundle\Controller;

use AppBundle\Form\PasswordsType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use AppBundle\Form\EditUserType;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class UserController extends Controller
{
    /**
     * Matches /users
     *
     * @Security("has_role('ROLE_USER_LIST')")
     * @Route("/users", name="users_list")
     */
    public function listAction(Request $request)
    {
        $users = $this->getDoctrine()->getRepository('AppBundle:User')->findAllActive();

        return $this->render(
            'user/list.html.twig', [
                'users' => $users
            ]
        );
    }

    /**
     * Matches /user/add
     *
     * @Security("has_role('ROLE_USER_ADD')")
     * @Route("/user/add", name="user_add")
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $userRoles = $this->getDoctrine()->getRepository('AppBundle:UserRole')->findAll();
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();

        $user = new User();
        $form = $this->createForm(UserType::class, $user, [
            'user_roles' => $userRoles
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $formUserRole = $form->get('userRoles')->getData();
            if (!in_array($formUserRole, $user->getRoles())) {
                $user->addUserRole($this->getDoctrine()->getRepository('AppBundle:UserRole')->findBy(['code' => $formUserRole])[0]);
            }

            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $form->get('plainPassword')->getData());
            $user->setPassword($password);
            $user->setCreated($currentUser);

            $em->persist($user);
            $em->flush();

            $this->addFlash(
                'success',
                $user->getName() . ' erfolgreich erfasst'
            );

            return $this->redirectToRoute('users_list');
        }

        return $this->render('form/base_form.html.twig', [
            'form' => $form->createView(),
            'title' => 'user.add',
            'icon' => 'user'
        ]);
    }


    /**
     * Matches /user/edit/{id}
     *
     * @Security("has_role('ROLE_USER_EDIT')")
     * @Route("/user/edit/{id}", name="user_edit")
     * @param Request $request
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, User $user = null)
    {
        $userRoles = $this->getDoctrine()->getRepository('AppBundle:UserRole')->findAll();

        if (empty($user)) {
            $this->addFlash('danger', 'user.user_not_found');
            return $this->redirectToRoute('homepage');
        }

        $form = $this->createForm(UserType::class, $user, [
            'user_roles' => $userRoles,
            'current_user_role' => $user->getRoles()[0]
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formUserRole = $form->get('userRoles')->getData();
            if (!in_array($formUserRole, $user->getRoles())) {
                $user->addUserRole($this->getDoctrine()->getRepository('AppBundle:UserRole')->findBy(['code' => $formUserRole])[0]);
            }

            foreach ($user->getRoles() as $role) {
                if ($role != $formUserRole) {
                    $user->removeUserRole($this->getDoctrine()->getRepository('AppBundle:UserRole')->findBy(['code' => $role])[0]);
                }
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash(
                'success',
                $user->getName() . ' erfolgreich bearbeitet'
            );

            return $this->redirectToRoute('users_list');
        }

        return $this->render('form/base_form.html.twig', [
            'form' => $form->createView(),
            'title' => 'user.change',
            'icon' => 'account'
        ]);
    }

    /**
     * Matches /user/change-password/{id}
     *
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/user/change-password/{id}", name="password_change")
     * @param Request $request
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function changePasswordAction(Request $request, User $user = null)
    {
        if (empty($user)) {
            $this->addFlash('danger', 'user.user_not_found');
            return $this->redirectToRoute('homepage');
        }

        if ($this->getUser()->getId() !== $user->getId()) {
            if (false === ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))) {
                $this->addFlash('danger', 'user.change_password_not_allowed');
                return $this->redirectToRoute('homepage');
            }
        }

        $form = $this->createForm(PasswordsType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $form->get('plainPassword')->getData());
            $user->setPassword($password);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash(
                'success',
                'Passwort für ' . $user->getName() . ' geändert'
            );

            return $this->redirectToRoute('users_list');
        }

        return $this->render('form/base_form.html.twig', [
            'form' => $form->createView(),
            'title' => 'user.change_password',
            'icon' => 'key'
        ]);
    }

    /**
     * Matches /user/delete/{id}
     *
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/user/delete/{id}", name="user_delete")
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(User $user = null)
    {
        if (empty($user)) {
            $this->addFlash('danger', 'user.user_not_found');
            return $this->redirectToRoute('homepage');
        }

        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $user->setDisabled($currentUser);

        $em->persist($user);
        $em->flush();

        $this->addFlash(
            'success',
            'User ' . $user->getName() . ' wurde deaktiviert'
        );

        return $this->redirectToRoute('users_list');
    }
}
