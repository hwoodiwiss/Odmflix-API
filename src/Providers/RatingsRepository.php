<?php

namespace OdmflixApi;

class RatingsRepository
{
	public function __construct(private Db $db) {
	}

	public function GetRatingById(int $id): ?array
	{
		$stmt = $this->db->prepare('SELECT * FROM Ratings WHERE Id = :c0');
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

	public function GetRatingCounts()
	{
		$stmt = $this->db->prepare('SELECT `Name` AS `Rating`, COUNT(`Shows`.`Id`) AS `Count` FROM `Ratings` 
									LEFT JOIN `Shows` ON `Ratings`.`Id` = `Shows`.`RatingId` 
									GROUP BY `Ratings`.`Name`;');

		if (!$stmt->execute()) {
			throw new \Error("An error occured retrieving data from the database. Error info: " . $stmt->errorInfo()[2]);
		}

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}
}