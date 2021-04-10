<?php

namespace OdmflixApi;

require_once __DIR__ . '/../lib/includes.php';

class TypeController extends ControllerBase
{
	public function __construct(private TypeRepository $typeRepo, ControllerContext $ctx) {

		parent::__construct($ctx);
	}

	#[HttpMethods(['GET'])]
	public function All(): IResult
	{
		if($types = $this->typeRepo->GetTypes()) {
			return $this->Ok($types);
		}

		return $this->NoData();
	}

	#[HttpMethods(['GET'])]
	public function Counts(): IResult
	{
		if($counts = $this->typeRepo->GetTypeCounts()) {
			return $this->Ok($counts);
		}

		return $this->NoData();
	}

	#[HttpMethods(['GET'])]
	public function Count(int $id): IResult
	{
		if($counts = $this->typeRepo->GetTypeCount($id)) {
			return $this->Ok($counts);
		}

		return $this->NoData();
	}

}
