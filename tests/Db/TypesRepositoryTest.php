<?php

require_once __DIR__ . '/../../src/Db/TypesRepository.php';

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use OdmflixApi\Db;
use OdmflixApi\TypesRepository;

class TypesRepositoryTest extends TestCase
{
	private TypesRepository $repository;
	private MockObject $mockDb;

	protected function setUp(): void
	{

		$this->mockDb = $this->createMock(Db::class);
		$this->repository = new TypesRepository($this->mockDb);
	}

	public function testTypesRepository_GetAverageMovieDurationShouldReturnAValidSubset()
	{
		$mockStmt = $this->createMock(\PDOStatement::class);
		$this->mockDb->method('prepare')->willReturn($mockStmt);
		$mockStmt->method('execute')->willReturn(true);
		$mockStmt->expects($this->once())->method('fetchAll')->willReturn([
			["ShowType" => "Movie", "AverageDuration" => 1234, "AverageNumSeasons" => NULL],
			["ShowType" => "TV Show", "AverageDuration" => NULL, "AverageNumSeasons" => 1234],
		]);

		$result = $this->repository->GetAverageMovieDuration();

		$this->assertContains("Movie", $result);
		$this->assertContains(1234, $result);
	}
}