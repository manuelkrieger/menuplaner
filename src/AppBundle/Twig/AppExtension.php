<?php

namespace AppBundle\Twig;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;

class AppExtension extends \Twig_Extension
{
    protected $em;
    /** @var RequestStack */
    protected $requestStack;

    public function __construct(EntityManager $em, RequestStack $requestStack)
    {
        $this->em = $em;
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('getControllerName', [$this, 'getControllerName']),
            new \Twig_SimpleFunction('getActionName', [$this, 'getActionName']),
            new \Twig_SimpleFunction('isActive', [$this, 'isActive']),
            new \Twig_SimpleFunction('uriStartsWith', [$this, 'uriStartsWith']),
            new \Twig_SimpleFunction('isSameRoute', [$this, 'isSameRoute']),
        ];
    }

    /**
     * Get current controller name
     *
     * @return string
     */
    public function getControllerName()
    {
        $request = $this->requestStack->getCurrentRequest();

        if (null !== $request) {
            $pattern = "#Controller\\\([a-zA-Z]*)Controller#";
            $matches = [];
            preg_match($pattern, $request->get('_controller'), $matches);

            return strtolower($matches[1]);
        }
    }

    /**
     * Get current action name
     *
     * @return string
     */
    public function getActionName()
    {
        $request = $this->requestStack->getCurrentRequest();

        if (null !== $request) {
            $pattern = "#::([a-zA-Z]*)Action#";
            $matches = [];
            preg_match($pattern, $request->get('_controller'), $matches);

            return $matches[1];
        }
    }

    /**
     * @param string|array $controllers
     * @param null|string|array $actions
     * @return bool
     */
    public function isActive($controllers, $actions = null)
    {
        if (!is_array($controllers)) {
            $controllers = [$controllers];
        }
        if (!in_array($this->getControllerName(), $controllers)) {
            return false;
        }
        if ($actions) {
            if (!is_array($actions)) {
                $actions = [$actions];
            }
            return in_array($this->getActionName(), $actions);
        }
        return true;
    }

    /**
     * @param string $text
     * @return bool
     */
    public function uriStartsWith(string $text)
    {
        $uri = $this->requestStack->getCurrentRequest()->getRequestUri();
        return strpos($uri, $text) === 0;
    }

    /**
     * @param string $text
     * @return bool
     */
    public function isSameRoute(string $route, $params = [])
    {
        $attributes = $this->requestStack->getCurrentRequest()->attributes;
        if ($route !== $attributes->get('_route')) {
            return false;
        }
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                if ($attributes->has($key)) {
                    if ($attributes->get($key) != $value) {
                        return false;
                    }
                } else {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'app.extension';
    }
}
