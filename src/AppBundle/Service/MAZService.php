<?php

namespace AppBundle\Service;

use AppBundle\Entity\MAZ;
use AppBundle\Entity\PreExtentValue;
use AppBundle\Entity\User;
use AppBundle\Utils\ServiceFormulaResolver;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class MAZService
{
    protected $entityManager;
    protected $projectService;
    protected $preExtentValueRepository;

    /**
     * MAZService constructor.
     * @param EntityManager $entityManager
     * @param ProjectService $projectService
     * @param EntityRepository $preExtentValueRepository
     */
    public function __construct(EntityManager $entityManager,
                                ProjectService $projectService,
                                EntityRepository $preExtentValueRepository)
    {
        $this->entityManager = $entityManager;
        $this->projectService = $projectService;
        $this->preExtentValueRepository = $preExtentValueRepository;
    }

    /**
     * @param MAZ $maz
     * @return array
     */
    public function getPreExtentValues(MAZ $maz)
    {
        $preExtentValues = $this->preExtentValueRepository->findBy(['maz' => $maz]);
        $values = [];
        /** @var \AppBundle\Entity\PreExtentValue $value */
        foreach ($preExtentValues as $value) {
            $values[$value->getServiceItemNr()] = $value;
        }
        return $values;
    }

    /**
     * @param MAZ $maz
     * @param User $user
     * @param array $preExtendValues
     */
    public function savePreExtendValues(MAZ $maz, User $user, array $preExtendValues)
    {
        $currentPreExtentValues = $this->getPreExtentValues($maz);

        $serviceItems = $this->projectService->getServiceItems($maz->getProject());
        /** @var \AppBundle\Entity\ServiceItem $serviceItem */
        foreach ($serviceItems as $serviceItem) {
            $preExtentValue = new PreExtentValue();
            if (isset($currentPreExtentValues[$serviceItem->getNr()])) {
                $preExtentValue = $currentPreExtentValues[$serviceItem->getNr()];
                $preExtentValue->setUpdated($user);
            } else {
                $preExtentValue->setCreated($user);
                $preExtentValue->setMaz($maz);
                $preExtentValue->setServiceItemNr($serviceItem->getNr());
            }
            $preExtentValue->setValue($preExtendValues[$serviceItem->getNr()]['value']);
            $preExtentValue->setIsPer($preExtendValues[$serviceItem->getNr()]['isPer']);

            $this->entityManager->persist($preExtentValue);
        }
        $this->entityManager->flush();
    }

    /**
     * @param MAZ $maz
     * @return array
     */
    public function getCalculatedValues(MAZ $maz)
    {
        $formulaResolver = new ServiceFormulaResolver();
        $projectParams = $this->projectService->getParams($maz->getProject());
        $params = [];
        /** @var \AppBundle\Entity\ProjectParam $param */
        foreach ($projectParams as $param) {
            $params[$param->getParam()->getCode()] = $param->getValue();
        }
        $formulaResolver->setParams($params);
        // TODO: $formulaResolver->setMAZValues()
        return $formulaResolver->calculateValues($this->projectService->getServiceItems($maz->getProject()));
    }

    /**
     * @param MAZ $maz
     * @return array
     */
    public function getCosts(MAZ $maz)
    {
        $result = [];

        $serviceItems = $this->projectService->getServiceItems($maz->getProject());
        $preExtentValues = $this->getPreExtentValues($maz);
        $serviceItemsPrice = $this->projectService->getServiceItemsPrice($maz->getProject());

        /** @var \AppBundle\Entity\ServiceItem $serviceItem */
        foreach ($serviceItems as $serviceItem) {
            $serviceItemNr = $serviceItem->getNr();
            $isPer = false;
            $amount = 0;
            $price = 0;
            if (array_key_exists($serviceItemNr, $preExtentValues)) {
                /** @var \AppBundle\Entity\PreExtentValue $preExtentValue */
                $preExtentValue = $preExtentValues[$serviceItemNr];
                $amount = $preExtentValue->getValue();
                $isPer = $preExtentValue->getIsPer();
            }
            if (array_key_exists($serviceItemNr, $serviceItemsPrice)) {
                /** @var \AppBundle\Entity\ProjectServiceItemPrice $serviceItemPrice */
                $serviceItemPrice = $serviceItemsPrice[$serviceItemNr];
                $price = $serviceItemPrice->getPrice();
            }
            $result[$serviceItemNr] = [
                'service_item' => $serviceItem,
                'is_per' => $isPer,
                'amount' => $amount,
                'price' => $price,
                'total' => ($amount * $price)
            ];
        }

        return $result;
    }
}
