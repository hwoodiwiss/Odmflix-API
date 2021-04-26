<?php

namespace OdmflixApi;

require_once __DIR__ . '/../lib/includes.php';

class DurationController extends ControllerBase
{
	public function __construct(private DurationRepository $durations, ControllerContext $ctx) {

		parent::__construct($ctx);
	}


	#[HttpMethods(['GET'])]
	public function GetTvTotal(): IResult
	{
		if($total = $this->durations->GetTotalSeasons()) {
			return $this->Ok($total);
		}

		return $this->NoData();
	}

	#[HttpMethods(['GET'])]
	public function GetTvAverage(): IResult
	{
		if($average = $this->durations->GetAverageSeasons()) {
			return $this->Ok($average);
		}

		return $this->NoData();
	}

	#[HttpMethods(['GET'])]
	public function GetMovieTotal(): IResult
	{
		if($total = $this->durations->GetTotalMinutes()) {
			return $this->Ok($total);
		}

		return $this->NoData();
	}

	#[HttpMethods(['GET'])]
	public function GetMovieAverage(): IResult
	{
		if($average = $this->durations->GetAverageMinutes()) {
			return $this->Ok($average);
		}

		return $this->NoData();
	}
}