<?php

namespace OdmflixApi;

require_once __DIR__ . '/../lib/includes.php';

class RatingController extends ControllerBase
{
	public function __construct(private RatingsRepository $repo, ControllerContext $ctx) {

		parent::__construct($ctx);
	}

	#[HttpMethods(['GET'])]
	public function Counts()
	{
		if($actorCounts = $this->repo->GetRatingCounts()) {
			return $this->Ok($actorCounts);
		}

		return $this->NoData();
	}



}
