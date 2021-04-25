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
		if($ratingCounts = $this->repo->GetRatingCounts()) {
			return $this->Ok($ratingCounts);
		}

		return $this->NoData();
	}

	#[HttpMethods(['GET'])]
	public function CountsByYear()
	{
		if($ratingCounts = $this->repo->GetRatingsByYear()) {
			return $this->Ok($ratingCounts);
		}

		return $this->NoData();
	}

	#[HttpMethods(['GET'])]
	public function TotalsByYear()
	{
		if($ratingTotals = $this->repo->GetRatingsTotalsByYear()) {
			return $this->Ok($ratingTotals);
		}

		return $this->NoData();
	}

	#[HttpMethods(['POST'])]
	public function CountsByYearForShows(array $showIds)
	{
		if($ratingCounts = $this->repo->GetRatingsByYearForShows($showIds)) {
			return $this->Ok($ratingCounts);
		}

		return $this->NoData();
	}

	#[HttpMethods(['POST'])]
	public function TotalsByYearForShows(array $showIds)
	{
		if($ratingTotals = $this->repo->GetRatingsTotalsByYearForShows($showIds)) {
			return $this->Ok($ratingTotals);
		}

		return $this->NoData();
	}



}
