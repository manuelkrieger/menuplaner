<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Ingredient;
use AppBundle\Entity\Meal;
use AppBundle\Form\MealType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class MealController extends Controller
{
    /**
     * @Route("/meal", name="meal_list")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function listAction(Request $request)
    {
        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_MEAL_LIST')) {
            $this->addFlash('danger', 'action_not_allowed');
            return $this->redirectToRoute('homepage');
        }
        $mealRepository = $this->getDoctrine()->getRepository('AppBundle:Meal');

        return $this->render('meal/list.html.twig', [
            'meals' => $mealRepository->findAll()
        ]);
    }

    /**
     * @Route("/meal/add", name="meal_add")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addAction(Request $request)
    {
        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_MEAL_ADD')) {
            $this->addFlash('danger', 'action_not_allowed');
            return $this->redirectToRoute('meal_list');
        }

        $foodRepository = $this->getDoctrine()->getRepository('AppBundle:Food');
        $foodsArray = $foodRepository->getAllAsArray();

        $meal = new Meal();
        $meal->setCreated($this->getUser());

        $form = $this->createForm(MealType::class, $meal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager = $this->getDoctrine()->getManager();

                $ingredientArray = $request->request->get('ingredient');
                $sort = 0;
                foreach ($ingredientArray as $ingredientData) {
                    $qty = current($ingredientData);
                    $ingredientId = key($ingredientData);
                    $ingredient = new Ingredient();
                    $ingredient->setFood($foodsArray[$ingredientId]);
                    $ingredient->setQty(floatval($qty));
                    $ingredient->setMeal($meal);
                    $ingredient->setSort($sort);
                    $ingredient->setCreated($this->getUser());
                    $entityManager->persist($ingredient);
                    $sort++;
                }

                $meal->setCreated($this->getUser());
                $entityManager->persist($meal);
                $entityManager->flush();

                $this->addFlash('success', 'meal.added_successfully');
                return $this->redirectToRoute('meal_list');
            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());
            }
        }

        return $this->render('meal/form.html.twig', [
            'form' => $form->createView(),
            'foods' => $foodsArray,
            'title' => 'meal.add'
        ]);
    }

    /**
     * @Route("/meal/{id}/edit", name="meal_edit", requirements={"id": "\d+"})
     *
     * @param Request $request
     * @param Meal $meal
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, Meal $meal)
    {
        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_MEAL_EDIT')) {
            $this->addFlash('danger', 'action_not_allowed');
            return $this->redirectToRoute('meal_list');
        }

        $form = $this->createForm(MealType::class, $meal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $meal->setUpdated($this->getUser());
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($meal);
                $entityManager->flush();

                $this->addFlash('success', 'meal.updated_successfully');
                return $this->redirectToRoute('meal_list');
            } catch (\Exception $e) {
                $this->addFlash('danger', $e->getMessage());
            }
        }

        return $this->render('meal/form.html.twig', [
            'form' => $form->createView(),
            'title' => 'meal.edit'
        ]);
    }

    /**
     * @Route("/meal/{id}/delete", name="meal_delete")
     *
     * @param Request $request
     * @param Meal $meal
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Meal $meal)
    {
        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_MEAL_DELETE')) {
            $this->addFlash('danger', 'action_not_allowed');
        } else {
            $meal->setDisabled($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($meal);
            $entityManager->flush();
            $this->addFlash('success', 'meal.deleted_successfully');
        }

        return $this->redirectToRoute('meal_list');
    }
}
