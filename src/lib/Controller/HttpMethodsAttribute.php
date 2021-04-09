<?php

namespace OdmflixApi;

#[Attribute(\Attribute::TARGET_METHOD)]
class HttpMethods
{
	public function __construct(array $methods) {

	}
}