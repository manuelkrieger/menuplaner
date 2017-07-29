<?php

namespace AppBundle\Service;

use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectServiceItem;
use AppBundle\Entity\ServiceGroup;
use AppBundle\Entity\User;
use AppBundle\Repository\ProjectParamRepository;
use AppBundle\Repository\ProjectRepository;
use AppBundle\Repository\ServiceGroupRepository;
use AppBundle\Repository\ServiceItemRepository;
use AppBundle\Utils\NumberHelper;
use Doctrine\ORM\EntityManager;

class ProjectService
{
    protected $entityManager;
    protected $projectRepository;
    protected $projectParamRepository;
    protected $serviceGroupRepository;
    protected $serviceItemRepository;

    /**
     * ServiceCatalog constructor.
     * @param EntityManager $entityManager
     * @param ProjectRepository $projectRepository
     * @param ProjectParamRepository $projectParamRepository
     * @param ServiceGroupRepository $serviceGroupRepository
     * @param ServiceItemRepository $serviceItemRepository
     */
    public function __construct(EntityManager $entityManager,
                                ProjectRepository $projectRepository,
                                ProjectParamRepository $projectParamRepository,
                                ServiceGroupRepository $serviceGroupRepository,
                                ServiceItemRepository $serviceItemRepository)
    {
        $this->entityManager = $entityManager;
        $this->projectRepository = $projectRepository;
        $this->projectParamRepository = $projectParamRepository;
        $this->serviceGroupRepository = $serviceGroupRepository;
        $this->serviceItemRepository = $serviceItemRepository;
    }

    /**
     * @param Project $project
     * @return array
     */
    public function getServiceItemsPrice(Project $project)
    {
        return $this->projectRepository->getServiceItemsPrice($project);
    }

    /**
     * @param Project $project
     * @return array
     */
    public function getParams(Project $project)
    {
        return $this->projectParamRepository->findByProjectWithValue($project);
    }

    /**
     * @param Project $project
     * @return bool
     */
    public function hasParams(Project $project)
    {
        return count($this->projectParamRepository->findByProject($project)) > 0;
    }

    /**
     * @param Project $project
     * @param User $user
     * @param array $serviceItemIds
     */
    public function saveServiceItems(Project $project, User $user, array $serviceItemIds)
    {
        $serviceCatalogItems = $this->projectRepository->getServiceItems($project);
        foreach ($serviceItemIds as $itemId) {
            /** @var \AppBundle\Entity\ServiceItem $serviceItem */
            $serviceItem = $this->serviceItemRepository->find($itemId);
            if (isset($serviceCatalogItems[$serviceItem->getNr()])) {
                unset($serviceCatalogItems[$serviceItem->getNr()]);
            } else {
                $serviceCatalogItem = new ProjectServiceItem();
                $serviceCatalogItem->setProject($project);
                $serviceCatalogItem->setServiceItem($serviceItem);
                $serviceCatalogItem->setCreated($user);
                $this->entityManager->persist($serviceCatalogItem);
            }
        }
//        foreach ($serviceCatalogItems as $item) {
//            // TODO: prüfen ob Eintrag entfernt werden kann. Nur möglich so lange keine Daten dazu erfasst wurden
//            $this->entityManager->remove($item);
//        }
        $this->entityManager->flush();
    }

    /**
     * @param Project $project
     * @return array
     */
    public function getServiceItems(Project $project)
    {
        $serviceItems = [];
        foreach ($this->projectRepository->getServiceItems($project) as $projectServiceItem) {
            $serviceItems[$projectServiceItem->getServiceItem()->getNr()] = $projectServiceItem->getServiceItem();
        }

        return $serviceItems;
    }

    /**
     * @param ServiceGroup $group
     * @param \DateTime $validOn
     * @return array
     */
    private function getGroupRows(ServiceGroup $group, \DateTime $validOn)
    {
        $rows[$group->getNr()] = [
            'type' => 'group',
            'id' => $group->getId(),
            'nr' => $group->getPrefix(),
            'label' => $group->getName()
        ];
        $subGroups = $this->serviceGroupRepository->findActiveSubGroups($group->getPrefix(), $validOn);
        /** @var \AppBundle\Entity\ServiceGroup $subGroup */
        foreach ($subGroups as $subGroup) {
            $rows[$subGroup->getNr()] = [
                'type' => 'group',
                'id' => $subGroup->getId(),
                'nr' => $subGroup->getPrefix(),
                'label' => $subGroup->getName()
            ];
        }
        return $rows;
    }

    /**
     * @param Project $project
     * @param ServiceGroup $group
     * @param bool $onlyChecked
     * @return array
     */
    private function getServiceItemRows(Project $project, ServiceGroup $group, bool $onlyChecked)
    {
        $rows = [];
        $projectServiceItems = $this->projectRepository->getServiceItems($project);
        $serviceItems = $this->serviceItemRepository->findActiveByPrefixWithDate($group->getPrefix(), $project->getServiceCatalogValidOn());
        /** @var \AppBundle\Entity\ServiceItem $item */
        foreach ($serviceItems as $item) {
            if (array_key_exists($item->getNr(), $projectServiceItems)) {
                $serviceItem = $projectServiceItems[$item->getNr()]->getServiceItem();
                $itemExists = true;
            } else {
                $serviceItem = $item;
                $itemExists = false;
            }
            if (!$onlyChecked || ($onlyChecked && $itemExists)) {
                $rows[$item->getNr()] = [
                    'type' => 'item',
                    'id' => $serviceItem->getId(),
                    'nr' => $serviceItem->getNr(),
                    'label' => $serviceItem->getTitle(),
                    'checked' => $itemExists
                ];
            }
        }
        return $rows;
    }

    /**
     * @param Project $project
     * @return array
     */
    public function getServiceItemsCatalog(Project $project)
    {
        $rows = [];
        $groups = $this->serviceGroupRepository->getActiveRootGroups($project->getServiceCatalogValidOn());
        foreach ($groups as $group) {
            $rows = array_merge($rows, $this->getServiceItemRows($project, $group, true));
            ksort($rows);
        }

        return $rows;
    }

    /**
     * @param Project $project
     * @param ServiceGroup $group
     * @return array
     */
    public function getProjectServiceItemRows(Project $project, ServiceGroup $group)
    {
        $rows = $this->getGroupRows($group, $project->getServiceCatalogValidOn());
        $rows = array_merge($rows, $this->getServiceItemRows($project, $group, false));
        ksort($rows);
        return $rows;
    }
}
