<?php

namespace OdmflixApi;

require_once __DIR__ . '/../lib/includes.php';

class ShowController extends ControllerBase
{
	public function __construct(private ShowRepository $showRepo, ControllerContext $ctx) {

		parent::__construct($ctx);
	}

	#[HttpMethods(['GET'])]
	public function ById(int $id): IResult
	{
		if($show = $this->showRepo->GetShowById($id)) {
			return $this->Ok($show);
		}

		return $this->NotFound();
	}

	#[HttpMethods(['POST'])]
	public function ByIds(?array $ids): IResult
	{
		if($shows = $this->showRepo->GetShowsByIds($ids)) {
			return $this->Ok($shows);
		}

		return $this->NotFound();
	}

	#[HttpMethods(['GET'])]
	public function ByType(int $id): IResult
	{
		if($shows = $this->showRepo->GetShowsByType($id)) {
			return $this->Ok($shows);
		}

		return $this->NotFound();
	}

	#[HttpMethods(['GET'])]
	public function ByCountry(int $id): IResult
	{
		if($shows = $this->showRepo->GetShowsByCountry($id)) {
			return $this->Ok($shows);
		}

		return $this->NotFound();
	}
	#[HttpMethods(['GET'])]
	public function ByCountryByYearCount(?int $typeId): IResult
	{
		if($data = $this->showRepo->GetShowCountByCountryByYear($typeId)) {
			return $this->Ok($data);
		}

		return $this->NotFound();
	}

	#[HttpMethods(['GET'])]
	public function All(): IResult
	{
		if($shows = $this->showRepo->GetShows()) {
			return $this->Ok($shows);
		}

		return $this->NoData();
	}
	
}
