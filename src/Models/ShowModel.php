<?php


namespace OdmflixApi;

class ShowModel
{
	public int $Id;
	public string $ShowType;
	public string $Title;
	public ?string $DateAdded;
	public string $ReleaseYear;
	public ?string $Rating;
	public string $Duration;
	public string $Description;
}