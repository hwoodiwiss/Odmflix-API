<?php

namespace OdmflixApi;

require_once __DIR__ . '/../lib/includes.php';

class ActorController extends ControllerBase
{
	public function __construct(private ActorsRepository $repo, ControllerContext $ctx) {

		parent::__construct($ctx);
	}

	#[HttpMethods(['POST'])]
	public function GetActorsForShowsWithLimit(IdListWithLimit $model)
	{
		if($actorCounts = $this->repo->GetTopNActorsForShows($model->Limit, $model->IdList)) {
			return $this->Ok($actorCounts);
		}

		return $this->NoData();
	}
	

}
