<?php

namespace OdmflixApi;

class TypesRepository
{
	public function __construct(private Db $db) {
	}

	public function GetTypeById(int $id): ?array
	{
		$stmt = $this->db->prepare('SELECT * FROM ShowTypes WHERE Id = :c0');
		$stmt->bindValue(':c0', $id, \PDO::PARAM_INT);
		if (!$stmt->execute()) {
			throw new \Error("An error occured retrieving data from the database. Error info: " . $stmt->errorInfo()[2]);
		}

		$data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		if (count($data) !== 1) {
			return null;
		}

		return $data[0];
	}

	public function GetAverageMovieDuration()
	{
		$stmt = $this->db->prepare('SELECT AVG(MovieDurations.DurationMins) AS AverageDuration FROM MovieDurations;');
		if (!$stmt->execute()) {
			throw new \Error("An error occured retrieving data from the database. Error info: " . $stmt->errorInfo()[2]);
		}

		$data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		return $data[0];
	}

	public function GetAverageShowSeasons()
	{
		$stmt = $this->db->prepare('SELECT AVG(TvShowDurations.DurationSeasons) AS AverageDuration FROM TvShowDurations;');
		if (!$stmt->execute()) {
			throw new \Error("An error occured retrieving data from the database. Error info: " . $stmt->errorInfo()[2]);
		}

		$data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		return $data[0];
	}
}