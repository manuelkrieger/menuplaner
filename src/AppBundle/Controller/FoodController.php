<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Food;
use AppBundle\Form\FoodType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class FoodController extends Controller
{
    /**
     * @Route("/food", name="food_list")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request)
    {
        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_FOOD_LIST')) {
            $this->addFlash('danger', 'action_not_allowed');
            return $this->redirectToRoute('homepage');
        }
        $foodRepository = $this->getDoctrine()->getRepository('AppBundle:Food');
        $foodgroupRepository = $this->getDoctrine()->getRepository('AppBundle:Foodgroup');

        return $this->render('food/list.html.twig', [
            'foods' => $foodRepository->findAll(),
            'foodgroups' => $foodgroupRepository->findAll()
        ]);
    }

    /**
     * @Route("/food/add", name="food_add")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_FOOD_ADD')) {
            $this->addFlash('danger', 'action_not_allowed');
            return $this->redirectToRoute('food_list');
        }

        $foodgroupRepository = $this->getDoctrine()->getRepository('AppBundle:Foodgroup');
        $unityRepository = $this->getDoctrine()->getRepository('AppBundle:Unity');

        $food = new Food();
        $food->setCreated($this->getUser());

        $form = $this->createForm(FoodType::class, $food, [
            'foodgroups' => $foodgroupRepository->findAll(),
            'unities' => $unityRepository->findAllActiveSortByShort()
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $food->setCreated($this->getUser());
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($food);
                $entityManager->flush();

                $this->addFlash('success', 'food.added_successfully');
                return $this->redirectToRoute('food_list');
            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());
            }
        }

        return $this->render('form/base_form.html.twig', [
            'form' => $form->createView(),
            'title' => 'food.add'
        ]);
    }

    /**
     * @Route("/food/{id}/edit", name="food_edit", requirements={"id": "\d+"})
     *
     * @param Request $request
     * @param Food $food
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Food $food)
    {
        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_FOOD_EDIT')) {
            $this->addFlash('danger', 'action_not_allowed');
            return $this->redirectToRoute('food_list');
        }

        $foodgroupRepository = $this->getDoctrine()->getRepository('AppBundle:Foodgroup');
        $unityRepository = $this->getDoctrine()->getRepository('AppBundle:Unity');

        $form = $this->createForm(FoodType::class, $food, [
            'foodgroups' => $foodgroupRepository->findAll(),
            'unities' => $unityRepository->findAllActiveSortByShort()
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $food->setUpdated($this->getUser());
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($food);
                $entityManager->flush();

                $this->addFlash('success', 'food.updated_successfully');
                return $this->redirectToRoute('food_list');
            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());
            }
        }

        return $this->render('form/base_form.html.twig', [
            'form' => $form->createView(),
            'title' => 'food.edit'
        ]);
    }

    /**
     * @Route("/food/{id}/delete", name="food_delete")
     *
     * @param Request $request
     * @param Food $food
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Food $food)
    {
        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_FOOD_DELETE')) {
            $this->addFlash('danger', 'action_not_allowed');
        } else {
            $food->setDisabled($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($food);
            $entityManager->flush();
            $this->addFlash('success', 'food.deleted_successfully');
        }

        return $this->redirectToRoute('food_list');
    }
}
