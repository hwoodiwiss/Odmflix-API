<?php

require_once __DIR__ . '/../../src/OdmflixAPI.php';
require_once __DIR__ . '/../../src/Controllers/NetflixController.php';

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use OdmflixApi\NetflixController;
use OdmflixApi\AppConfig;
use OdmflixApi\ControllerContext;
use OdmflixApi\NotFoundResult;
use OdmflixApi\ShowRepository;
use OdmflixApi\OkResult;
use OdmflixApi\ShowModel;

class NetflixControllerTest extends TestCase
{
	private ControllerContext $ctx;
	private NetflixController $controller;
	private MockObject $mockShowsRepository;

	protected function setUp(): void
	{
		$this->ctx = new ControllerContext();
		$this->appConfig = new AppConfig();

		$this->mockShowsRepository = $this->createMock(ShowRepository::class);
		$this->controller = new NetflixController($this->mockShowsRepository, $this->ctx);
	}

	public function testContactController_CtorCanConstruct()
	{
		$this->assertInstanceOf(NetflixController::class , new NetflixController($this->mockShowsRepository, $this->ctx));
	}

	public function testContactController_GETShowReturns404IfShowIsNull()
	{
		$this->ctx->RequestMethod = 'POST';

		$this->mockShowsRepository->expects($this->once())->method('GetShowById')->willReturn(null);

		$result = $this->controller->Show(123);

		$this->assertInstanceOf(NotFoundResult::class , $result);
	}

	public function testContactController_GETShowReturnsOkayIfShowIsTruthy()
	{
		$this->ctx->RequestMethod = 'POST';

		$this->mockShowsRepository->expects($this->once())->method('GetShowById')->willReturn(new ShowModel);

		$result = $this->controller->Show(123);

		$this->assertInstanceOf(OkResult::class , $result);
	}

}