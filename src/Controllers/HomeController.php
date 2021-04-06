<?php

namespace OdmflixApi;

require_once __DIR__ . '/../lib/includes.php';

class HomeController extends ControllerBase
{
	public function Echo(): IResult
	{
		return $this->Ok((new \DateTime('now', new \DateTimeZone('UTC'))));
	}
}
