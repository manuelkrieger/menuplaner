<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Foodgroup;
use AppBundle\Form\FoodgroupType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class FoodgroupController extends Controller
{
    /**
     * @Route("/foodgroup/add", name="foodgroup_add")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_FOODGROUP_ADD')) {
            $this->addFlash('danger', 'action_not_allowed');
            return $this->redirectToRoute('food_list');
        }
        $foodgroup = new Foodgroup();
        $foodgroup->setCreated($this->getUser());

        $form = $this->createForm(FoodgroupType::class, $foodgroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $foodgroup->setCreated($this->getUser());
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($foodgroup);
                $entityManager->flush();

                $this->addFlash('success', 'foodgroup.added_successfully');
                return $this->redirectToRoute('food_list');
            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());
            }
        }

        return $this->render('form/base_form.html.twig', [
            'form' => $form->createView(),
            'title' => 'foodgroup.add'
        ]);
    }

    /**
     * @Route("/foodgroup/{id}/edit", name="foodgroup_edit", requirements={"id": "\d+"})
     *
     * @param Request $request
     * @param Foodgroup $foodgroup
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Foodgroup $foodgroup)
    {
        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_FOODGROUP_EDIT')) {
            $this->addFlash('danger', 'action_not_allowed');
            return $this->redirectToRoute('foodgroup_list');
        }
        $form = $this->createForm(FoodgroupType::class, $foodgroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $foodgroup->setUpdated($this->getUser());
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($foodgroup);
                $entityManager->flush();

                $this->addFlash('success', 'foodgroup.updated_successfully');
                return $this->redirectToRoute('food_list');
            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());
            }
        }

        return $this->render('form/base_form.html.twig', [
            'form' => $form->createView(),
            'title' => 'foodgroup.edit'
        ]);
    }

    /**
     * @Route("/foodgroup/{id}/delete", name="foodgroup_delete")
     *
     * @param Request $request
     * @param Foodgroup $foodgroup
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Foodgroup $foodgroup)
    {
        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_FOODGROUP_DELETE')) {
            $this->addFlash('danger', 'action_not_allowed');
        } else {
            $foodgroup->setDisabled($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($foodgroup);
            $entityManager->flush();
            $this->addFlash('success', 'foodgroup.deleted_successfully');
        }

        return $this->redirectToRoute('food_list');
    }
}
