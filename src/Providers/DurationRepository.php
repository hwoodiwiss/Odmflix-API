<?php

namespace OdmflixApi;

class DurationRepository
{
	public function __construct(private Db $db) {
	}

	public function GetTotalSeasons()
	{
		$stmt = $this->db->prepare('SELECT SUM(`DurationSeasons`) AS `Total` FROM `TvShowDurations`');

		if (!$stmt->execute()) {
			throw new \Error("An error occured retrieving data from the database. Error info: " . $stmt->errorInfo()[2]);
		}
		$data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

		return +$data[0]['Total'];
	}

	public function GetAverageSeasons()
	{
		$stmt = $this->db->prepare('SELECT AVG(`DurationSeasons`) AS `Average` FROM `TvShowDurations`');

		if (!$stmt->execute()) {
			throw new \Error("An error occured retrieving data from the database. Error info: " . $stmt->errorInfo()[2]);
		}
		$data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

		return +$data[0]['Average'];
	}

	public function GetTotalMinutes()
	{
		$stmt = $this->db->prepare('SELECT SUM(`DurationMins`) AS `Total` FROM `MovieDurations`');

		if (!$stmt->execute()) {
			throw new \Error("An error occured retrieving data from the database. Error info: " . $stmt->errorInfo()[2]);
		}
		$data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

		return +$data[0]['Total'];
	}

	public function GetAverageMinutes()
	{
		$stmt = $this->db->prepare('SELECT AVG(`DurationMins`) AS `Average` FROM `MovieDurations`');

		if (!$stmt->execute()) {
			throw new \Error("An error occured retrieving data from the database. Error info: " . $stmt->errorInfo()[2]);
		}
		$data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

		return +$data[0]['Average'];
	}
}