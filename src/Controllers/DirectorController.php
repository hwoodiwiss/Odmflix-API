<?php

namespace OdmflixApi;

require_once __DIR__ . '/../lib/includes.php';

class DirectorController extends ControllerBase
{
	public function __construct(private DirectorsRepository $directors, ControllerContext $ctx) {

		parent::__construct($ctx);
	}

	#[HttpMethods(['GET'])]
	public function All(): IResult
	{
		if ($directors = $this->directors->GetAll()) {
			return $this->Ok($directors);
		}

		return $this->NoData();
	}

		#[HttpMethods(['GET'])]
	public function GetShows(int $id): IResult
	{
		if($shows = $this->directors->GetShowsForDirector($id)) {
			return $this->Ok($shows);
		}

		return $this->NotFound();
	}
}