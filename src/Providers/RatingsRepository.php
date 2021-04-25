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

	public function GetRatingsByYear()
	{
		$stmt = $this->db->prepare('SELECT DISTINCT `r`.`Name` AS `Rating`, `Year`, COUNT(`r`.`Id`) AS `Count` FROM `ReleaseYears` AS `ry`
									JOIN `Shows` AS `s` ON `s`.`ReleaseYearId` = `ry`.`Id`
									JOIN `Ratings` AS `r` ON `s`.`RatingId` = `r`.`Id`
									GROUP BY `Rating`, `Year`
									ORDER BY `Rating`;');

		if (!$stmt->execute()) {
			throw new \Error("An error occured retrieving data from the database. Error info: " . $stmt->errorInfo()[2]);
		}

		$rawData = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		$groupedData = [];
		foreach ($rawData as $row) {
			$groupedData[$row['Rating']][] = ["Year" => +$row['Year'], "Count" => +$row['Count']];
		}

		return $groupedData;
	}

	public function GetRatingsTotalsByYear()
	{
		$stmt = $this->db->prepare('SELECT DISTINCT `Year`, COUNT(`r`.`Id`) AS `Total` FROM `ReleaseYears` AS `ry`
									JOIN `Shows` AS `s` ON `s`.`ReleaseYearId` = `ry`.`Id`
									JOIN `Ratings` AS `r` ON `s`.`RatingId` = `r`.`Id`
									GROUP BY `Year`
									ORDER BY `Year`;');

		if (!$stmt->execute()) {
			throw new \Error("An error occured retrieving data from the database. Error info: " . $stmt->errorInfo()[2]);
		}

		$data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		return $data;
	}

	public function GetRatingsByYearForShows(?array $showIds)
	{
		$paramList = array_map(function ($idx) {
			return ":c$idx";
		}, array_keys($showIds));
		$paramListStr = implode(',', $paramList);
		$stmt = $this->db->prepare("SELECT DISTINCT `r`.`Name` AS `Rating`, `Year`, COUNT(`r`.`Id`) AS `Count` FROM `ReleaseYears` AS `ry`
									JOIN `Shows` AS `s` ON `s`.`ReleaseYearId` = `ry`.`Id`
									JOIN `Ratings` AS `r` ON `s`.`RatingId` = `r`.`Id`
									WHERE `s`.`Id` IN ($paramListStr)
									GROUP BY `Rating`, `Year`
									ORDER BY `Rating`;");

		foreach ($paramList as $idx => $param) {
			$stmt->bindValue($param, $showIds[$idx], \PDO::PARAM_INT);
		}
		if (!$stmt->execute()) {
			throw new \Error("An error occured retrieving data from the database. Error info: " . $stmt->errorInfo()[2]);
		}

		$rawData = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		$groupedData = [];
		foreach ($rawData as $row) {
			$groupedData[$row['Rating']][] = ["Year" => +$row['Year'], "Count" => +$row['Count']];
		}

		return $groupedData;
	}

	public function GetRatingsTotalsByYearForShows(?array $showIds)
	{
		$paramList = array_map(function ($idx) {
			return ":c$idx";
		}, array_keys($showIds));
		$paramListStr = implode(',', $paramList);
		$stmt = $this->db->prepare("SELECT DISTINCT `Year`, COUNT(`r`.`Id`) AS `Total` FROM `ReleaseYears` AS `ry`
									JOIN `Shows` AS `s` ON `s`.`ReleaseYearId` = `ry`.`Id`
									JOIN `Ratings` AS `r` ON `s`.`RatingId` = `r`.`Id`
									WHERE `s`.`Id` IN ($paramListStr)
									GROUP BY `Year`
									ORDER BY `Year`;");

		foreach ($paramList as $idx => $param) {
			$stmt->bindValue($param, $showIds[$idx], \PDO::PARAM_INT);
		}
		if (!$stmt->execute()) {
			throw new \Error("An error occured retrieving data from the database. Error info: " . $stmt->errorInfo()[2]);
		}

		$data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		return $data;
	}
}