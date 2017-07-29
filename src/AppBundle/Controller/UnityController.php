<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Unity;
use AppBundle\Form\UnityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class UnityController extends Controller
{
    /**
     * @Route("/unity", name="unity_list")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request)
    {
        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_UNITY_LIST')) {
            $this->addFlash('danger', 'unity.list_not_allowed');
            return $this->redirectToRoute('homepage');
        }
        $unityRepository = $this->getDoctrine()->getRepository('AppBundle:Unity');

        return $this->render('unity/list.html.twig', [
            'unities' => $unityRepository->findAllActive()
        ]);
    }

    /**
     * @Route("/project/add", name="unity_add")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_UNITY_ADD')) {
            $this->addFlash('danger', 'unity.add_not_allowed');
            return $this->redirectToRoute('unity_list');
        }
        $unity = new Unity();
        $unity->setCreated($this->getUser());

        $form = $this->createForm(UnityType::class, $unity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $unity->setCreated($this->getUser());
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($unity);
                $entityManager->flush();

                $this->addFlash('success', 'unity.added_successfully');
                return $this->redirectToRoute('unity_list');
            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());
            }
        }

        return $this->render('form/base_form.html.twig', [
            'form' => $form->createView(),
            'title' => 'unity.add'
        ]);
    }

    /**
     * @Route("/unity/{id}/edit", name="unity_edit", requirements={"id": "\d+"})
     *
     * @param Request $request
     * @param Unity $unity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Unity $unity)
    {
        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_UNITY_EDIT')) {
            $this->addFlash('danger', 'unity.edit_not_allowed');
            return $this->redirectToRoute('unity_list');
        }
        $form = $this->createForm(UnityType::class, $unity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $unity->setUpdated($this->getUser());
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($unity);
                $entityManager->flush();

                $this->addFlash('success', 'unity.updated_successfully');
                return $this->redirectToRoute('unity_list');
            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());
            }
        }

        return $this->render('form/base_form.html.twig', [
            'form' => $form->createView(),
            'title' => 'unity.edit'
        ]);
    }

    /**
     * @Route("/unity/{id}/delete", name="unity_delete")
     *
     * @param Request $request
     * @param Unity $unity
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Unity $unity)
    {
        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_UNITY_DELETE')) {
            $this->addFlash('danger', 'unity.delete_not_allowed');
        } else {
            $unity->setDisabled($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($unity);
            $entityManager->flush();
            $this->addFlash('success', 'unity.deleted_successfully');
        }

        return $this->redirectToRoute('unity_list');
    }
}
