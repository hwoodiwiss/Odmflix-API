<?php

namespace OdmflixApi;

require_once __DIR__ . '/../lib/includes.php';

class ShowController extends ControllerBase
{
	public function __construct(private ShowRepository $showRepo, private ControllerContext $ctx) {

		parent::__construct($ctx);
	}

	public function Show(int $id): IResult
	{
		$show = $this->showRepo->GetShowById($id);
		return $this->Ok($show);
	}
}
