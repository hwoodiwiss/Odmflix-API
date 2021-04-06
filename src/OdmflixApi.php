<?php

namespace OdmflixApi;

require_once __DIR__ . '/Controllers/Controllers.php';
require_once __DIR__ . '/lib/includes.php';
require_once __DIR__ . '/Providers/includes.php';
require_once __DIR__ . '/Services/includes.php';
require_once __DIR__ . '/Db/includes.php';
require_once __DIR__ . '/Models/includes.php';
require_once __DIR__ . '/AppConfig.php';

class OdmflixApi
{

	private Router $router;
	private DiContainer $diContainer;
	private AppConfig $config;
	private ControllerContext $controllerCtx;
	private string $route;
	private string $method;
	private ?Route $resolvedRoute;

	public function __construct()
	{
		$this->diContainer = new DiContainer();
		$this->router = new Router($this->diContainer);
	}

	public function main()
	{
		$this->route = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
		$this->method = $_SERVER['REQUEST_METHOD'];
		$localSettingsFile = __DIR__ . '/../settings.secrets.ini';
		$settingsFile = __DIR__ . '/../settings.ini';
		if(file_exists($localSettingsFile)) {
			$this->config = AppConfig::from($localSettingsFile);
		}
		else if(file_exists($settingsFile)) {
			$this->config = AppConfig::from($settingsFile);
		} else {
			throw new \RuntimeException('No Configuration could be found!');
		}
		$this->Startup();

		if($this->method !== 'OPTIONS')
		{
			$this->resolvedRoute = $this->router->Get($this->route);
		} else {
			$this->resolvedRoute = $this->router->Get('/Options/HandleOptions');
		}

		$result = null;
		try
		{
			$result = $this->executeRoute();
		} catch(\Error $e) {
			$result = new CustomResult(500, 'Server Error Occured');
		}

		foreach($this->controllerCtx->responseHeaders as $name => $value) {
			if(is_array($value)) {
				$implodeHeader = implode(', ', $value);
				header("$name: $implodeHeader");
			} else {
				header("$name: $value");
			}
		}

		http_response_code($result->StatusCode());
		if(($content = $result->Body()) !== null) {
			echo $content;
		}
	}

	private function Startup() {
		Session::StartSession();
		$this->router
		->Add((new Route('Home', 'Echo', ['GET'])))
		->Add((new Route('Netflix', 'Show', ['GET'])))
		->Add((new Route('Options', 'HandleOptions', ['OPTIONS'])));

		$this->controllerCtx = (new ControllerContextBuilder)
			->AddRequestHeaders(getallheaders())
			->AddRequestMethod($_SERVER['REQUEST_METHOD'])
			->AddQuery($_SERVER['QUERY_STRING'] ?? '')
			->AddRequestBody(file_get_contents('php://input'))
			->AddCorsHeaders($this->config->cors->allowedorigins)
			->AddAllowCredentials()
			->Build();

		$this->diContainer->addInjectable(AppConfig::class , $this->config);
		$this->diContainer->addInjectable(ControllerContext::class , $this->controllerCtx);
		$this->diContainer->addInjectable(Session::class);
		$this->diContainer->addInjectable(Db::class);
		$this->diContainer->addInjectable(ShowRepository::class);


	}

	private function executeRoute(): IResult 
	{
		if($this->resolvedRoute !== null) {
			return $this->resolvedRoute->Execute($this->diContainer, $this->method, $this->controllerCtx);
		} else {
			return new NotFoundResult();
		}

	}
}