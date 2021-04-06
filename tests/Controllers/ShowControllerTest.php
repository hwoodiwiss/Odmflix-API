<?php

require_once __DIR__ . '/../../src/Db/ShowRepository.php';
require_once __DIR__ . '/../../src/Controllers/ShowController.php';

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use OdmflixApi\ShowController;
use OdmflixApi\AppConfig;
use OdmflixApi\ControllerContext;
use OdmflixApi\NotFoundResult;
use OdmflixApi\ShowRepository;
use OdmflixApi\OkResult;

class ShowControllerTest extends TestCase
{
	private ControllerContext $ctx;
	private ShowController $controller;
	private MockObject $mockShowsRepository;

	protected function setUp(): void
	{
		$this->ctx = new ControllerContext();
		$this->appConfig = new AppConfig();

		$this->mockShowsRepository = $this->createMock(ShowRepository::class);
		$this->controller = new ShowController($this->mockShowsRepository, $this->ctx);
	}

	public function testShowController_CtorCanConstruct()
	{
		$this->assertInstanceOf(ShowController::class , new ShowController($this->mockShowsRepository, $this->ctx));
	}

	public function testShowController_GETShowReturns404IfShowIsNull()
	{
		$this->ctx->RequestMethod = 'POST';

		$this->mockShowsRepository->expects($this->once())->method('GetShowById')->willReturn(null);

		$result = $this->controller->ById(123);

		$this->assertInstanceOf(NotFoundResult::class , $result);
	}

	public function testShowController_GETShowReturnsOkayIfShowIsTruthy()
	{
		$this->ctx->RequestMethod = 'POST';

		$this->mockShowsRepository->expects($this->once())->method('GetShowById')->willReturn(["things" => "stuff"]);

		$result = $this->controller->ById(123);

		$this->assertInstanceOf(OkResult::class , $result);
	}

}