<?php
/**
 * Created by PhpStorm.
 * User: v.konovalov
 * Date: 19.04.2018
 * Time: 9:26
 */

namespace System\Controller;

use Exception\ControllerException;
use Http\Request\Request;
use Http\Request\RequestInterface;
use Http\Response\Response;
use System\EventListener\EventManager;
use System\Kernel\GETParam;
use System\EventListener\EventTypes;
use System\Kernel\TypesApp\AbstractApplication;
use System\Kernel\TypesApp\WebApp;
use System\Render;
use System\Router\RouteData;
use System\Router\Router;
use System\Router\Routing;

class LauncherController
{
    const PREFIX_ACTION = 'Action';

    /**
     * @var AbstractController
     */
    private $controller;

    private $className;

    private $action;

    /**
     * @var Render|Response
     */
    private $resultAction;

    /**
     * @var EventManager
     */
    private $eventManager;

    /**
     * @var Response
     */
    private $response;

    /**
     * @var Router
     */
    private $router;

    public function execute()
    {
        $this->prepare();
        $this->runController();

        return $this->resultAction;
    }

    public function __construct(WebApp $webApp, Router $router, RequestInterface $request, Response $response)
    {
        $this->eventManager = $webApp->getEventApp();
        $this->response     = $response;
        $this->router       = $router;
    }

    private function prepare(): void
    {
        $this->className = 'App\\' . str_replace(':', '\\', $this->router->getController());
        $this->action    = $this->router->getAction() . self::PREFIX_ACTION;
    }

    private function runController(): void
    {
        $routeData  = $this->setRouteData($this->action, $this->router);

        $this->eventManager->runEvent(EventTypes::BEFORE_CONTROLLER, [
            'controller' => $routeData->getControllerName(),
            'action'     => $routeData->getActionName(),
        ]);

        /** @var AbstractController $controller */
        $this->controller = new $this->className($this->eventManager, $this->response);

        if (!$this->controller->__before($routeData)) {
            return;
        }

        $this->runAction($this->controller, $this->action);

        $this->controller->__after($routeData);
        $this->eventManager->runEvent(EventTypes::AFTER_CONTROLLER);
    }

    private function runAction(ControllerInterface $controller, string $action)
    {
        if (!method_exists($controller, $action)) {
            throw ControllerException::notFoundController([$action]);
        }

        $this->eventManager->runEvent(EventTypes::BEFORE_ACTION);
        $this->resultAction = call_user_func_array([$controller, $action], array_values(GETParam::getParamForController()));
        $this->eventManager->runEvent(EventTypes::AFTER_ACTION);
    }

    private function setRouteData(string $action, Router $router): RouteData
    {
        $routeData  = (new RouteData())
            ->setData('action', $action)
            ->setData('controller', substr($router->getController(), strrpos($router->getController(), ':') + 1));

        return $routeData;
    }
}