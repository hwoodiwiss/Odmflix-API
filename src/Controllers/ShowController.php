<?php

namespace OdmflixApi;

require_once __DIR__ . '/../lib/includes.php';

class ShowController extends ControllerBase
{
	public function __construct(private ShowRepository $showRepo, ControllerContext $ctx) {

		parent::__construct($ctx);
	}

	public function ById(int $id): IResult
	{
		if($show = $this->showRepo->GetShowById($id)) {
			return $this->Ok($show);
		}

		return $this->NotFound();
	}

	public function ByTypeName(string $typeName): IResult
	{
		if($shows = $this->showRepo->GetShowsByTypeName($typeName)) {
			return $this->Ok($shows);
		}

		return $this->NotFound();
	}
}
