<?php

namespace OdmflixApi;

include_once __DIR__ . '/utils.php';
include_once __DIR__ . '/Results/Interface/IResult.php';

class Router
{
  private Routes $routes;

  public function __construct(private DiContainer &$di) {
    $this->routes = new Routes();
  }

  public function Add(Route $route): Router
  {
    $this->routes[] = $route;
    return $this;
  }

  public function Get(string $route): Route|null
  {
    return $this->routes->Get($route);
  }

}

//Strongly typed collection class for storing Route
class Routes implements \ArrayAccess
{
  private $routes;

  public function __construct(?Route...$routes)
  {
    $this->routes = $routes !== null ? $routes : [];
  }

  public function Add(Route $route)
  {
    $this->routes[] = $route;
  }

  public function Get(string $urlRoute): ?Route 
  {
    foreach($this->routes as $route) {
      if($route instanceof Route) {
        if(strtolower($route->route) === strtolower($urlRoute)){
          return $route;
        }
      }
    }

    return null;
  }

  //ArrayAccess interface implementations
  /**
   * Whether an offset exists
   * Whether or not an offset exists.
   *
   * @param int $offset An offset to check for.
   *
   * @return bool
   */
  function offsetExists($offset): bool
  {
    return array_key_exists($offset, $this->routes);
  }

  /**
   * Offset to retrieve
   * Returns the value at specified offset.
   *
   * @param int $offset The offset to retrieve.
   *
   * @return mixed
   */
  function offsetGet($offset): Route
  {
    return $this->routes[$offset];
  }

  /**
   * Assign a value to the specified offset
   * Assigns a value to the specified offset.
   *
   * @param int $offset The offset to assign the value to.
   * @param Route $value The value to set.
   */
  function offsetSet($offset, $value)
  {
    if($offset !== null) {
      $this->routes[$offset] = $value;
    } else {
      $this->routes[] = $value;
    }
  }

  /**
   * Unset an offset
   * Unsets an offset.
   *
   * @param int $offset The offset to unset.
   */
  function offsetUnset($offset)
  {
    unset($this->routes[$offset]);
  }
}

//Represents a route within the application
class Route
{

  public string $route;

  private \ReflectionClass $controllerClass;
  private array $SupportedMethods;

  public function __construct(private string $controllerName, private string $actionName, array $supportedMethods, )
  {
    $this->route =  ($controllerName != "Home" ? "/$controllerName" : "") . "/$actionName";
    $this->SupportedMethods = $supportedMethods;
    $this->controllerClass = $this->GetControllerClass($this->controllerName);
    $this->ValidateAction();
  }

  private function GetControllerClass(string $controllerName): \ReflectionClass
  {
    return new \ReflectionClass("SkylabDesignApi\\" . $controllerName . 'Controller');
  }

  private function ValidateAction()
  {
    $methods = $this->controllerClass->getMethods(\ReflectionMethod::IS_PUBLIC);
    foreach($methods as $method) {
      if($method instanceof \ReflectionMethod){
        if($method->name === $this->actionName) {
          return;
        }
      }
    }
    throw new \RuntimeException("Could not bind to $this->actionName on controller $this->controllerName");
  }

  /*
  Refactor to be better, this shouldn't all be here, maybe have a dedicated controller executor?
  Or at least move out the param extraction into its own method
  */
  public function Execute(DiContainer $di, string $method, ControllerContext $ctx): IResult
  {
    if(in_array($method, $this->SupportedMethods, true)) {
      $injectables = $di->getInjectablesForType($this->controllerClass->getName());
      $controller = $this->controllerClass->newInstanceArgs($injectables);

      $action = $this->actionName;
      $actionMethod = $this->controllerClass->getMethod($action);
      $actionArgs = $this->getArgValuesForAction($actionMethod, $ctx);
      if($actionArgs  === null) {
        return new BadRequestResult();
      }
      return $actionMethod->invokeArgs($controller, $actionArgs);
    
    } else {
      return new NotSupportedResult();
    }
  }

  private function getArgValuesForAction(\ReflectionMethod $method, ControllerContext $ctx): array | null
  {
    $actionParams = $method->getParameters();
    $actionArgs = [];
    foreach($actionParams as $param) 
    {
      /** @var \ReflectionNamedType */$paramType = $param->getType();
      $paramMapper = new Mapper($paramType->getName());
      if($method === 'GET') {
        if(isset($ctx->QueryParams[$param->getName()])) {
          $actionArgs[] = $ctx->QueryParams[$param->getName()];
        } else if ($paramType->allowsNull()) {
          $actionArgs[] = null;
        } else {
          return null;
        }
      } else {
        $paramVal = $paramMapper->map(json_decode($ctx->RequestBody));
        if(!$paramMapper->getValidator()->validate($paramVal)) {
          return null;
        }
        $actionArgs[] = $paramVal;
      }
    }

    return $actionArgs;
  }
}