<?php

namespace OdmflixApi;

class TypesRepository
{
	public function __construct(private Db $db) {
	}

	public function GetTypeById(int $id): ?array
	{
		$stmt = $this->db->prepare('SELECT * FROM Types WHERE Id = :c0');
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

	public function GetAverageDurations()
	{
		$stmt = $this->db->prepare('SELECT Name AS ShowType, AVG(vw_Shows.Duration) AS AverageDuration,
									AVG(vw_Shows.NumSeasons) AS AverageNumSeasons FROM ShowTypes
									LEFT JOIN vw_Shows ON ShowTypes.Id = vw_Shows.ShowTypeId GROUP BY ShowTypes.Name;');

		if (!$stmt->execute()) {
			throw new \Error("An error occured retrieving data from the database. Error info: " . $stmt->errorInfo()[2]);
		}

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function GetAverageMovieDuration()
	{
		$overallAvgs = $this->GetAverageDurations();
		$key = array_search('Movie', $overallAvgs);
		return [$overallAvgs[$key]['ShowType'], $overallAvgs[$key]['AverageDuration']];
	}

	public function GetAverageShowSeasons()
	{
		$overallAvgs = $this->GetAverageDurations();
		$key = array_search('Tv Show', $overallAvgs);
		return [$overallAvgs[$key]['ShowType'], $overallAvgs[$key]['AverageNumSeasons']];
	}
}