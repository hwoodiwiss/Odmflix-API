<?php

require_once __DIR__ . '/../../src/Providers/TypesRepository.php';

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use OdmflixApi\Db;
use OdmflixApi\TypeRepository;

class TypeRepositoryTest extends TestCase
{
	private TypeRepository $repository;
	private MockObject $mockDb;

	protected function setUp(): void
	{

		$this->mockDb = $this->createMock(Db::class);
		$this->repository = new TypeRepository($this->mockDb);
	}

	public function testTypesRepository_GetAverageMovieDurationShouldReturnAValidSubset()
	{
		$mockStmt = $this->createMock(\PDOStatement::class);
		$this->mockDb->method('prepare')->willReturn($mockStmt);
		$mockStmt->method('execute')->willReturn(true);
		$mockStmt->expects($this->once())->method('fetchAll')->willReturn([
			["AverageDuration" => 1234],
		]);

		$result = $this->repository->GetAverageMovieDuration();

		$this->assertContains(1234, $result);
	}
}