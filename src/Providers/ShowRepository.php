<?php

namespace OdmflixApi;

class ShowRepository
{
	public function __construct(private Db $db) {
	}

	public function GetShowById(int $id): ?array
	{
		$stmt = $this->db->prepare('SELECT * FROM vw_Shows WHERE Id = :c0');
		$stmt->bindValue(':c0', $id, \PDO::PARAM_INT);
		if (!$stmt->execute()) {
			throw new \Error("An error occured retrieving data from the database. Error info: " . $stmt->errorInfo()[2]);
		}

		$data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		if(count($data) !== 1) {
			return null;
		}
		
		return $data[0];
	}

	public function GetShowsByIds(?array $ids): ?array
	{
		if($ids === null) {
			return null;
		}
		$paramList = array_map(function($idx) {
			return ":c$idx";
		}, array_keys($ids));

		$stmt = $this->db->prepare('SELECT * FROM vw_Shows WHERE Id IN (' . implode(',', $paramList) . ')');
		foreach($paramList as $idx => $param) {
			$stmt->bindValue($param, $ids[$idx], \PDO::PARAM_INT);
		}
		if (!$stmt->execute()) {
			throw new \Error("An error occured retrieving data from the database. Error info: " . $stmt->errorInfo()[2]);
		}

		$data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		
		return $data;
	}

	public function GetShowsByType(int $typeId): ?array
	{
		$stmt = $this->db->prepare('SELECT * FROM vw_Shows WHERE ShowTypeId = :c0');
		$stmt->bindValue(':c0', $typeId, \PDO::PARAM_INT);
		if (!$stmt->execute()) {
			throw new \Error("An error occured retrieving data from the database. Error info: " . $stmt->errorInfo()[2]);
		}

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function GetShowsByTypeName(string $type): ?array
	{
		$stmt = $this->db->prepare('SELECT * FROM vw_Shows WHERE ShowType = :c0');
		$stmt->bindValue(':c0', $type);
		if (!$stmt->execute()) {
			throw new \Error("An error occured retrieving data from the database. Error info: " . $stmt->errorInfo()[2]);
		}

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function GetShowsByRating(int $ratingId): ?array
	{
		$stmt = $this->db->prepare('SELECT * FROM vw_Shows WHERE RatingId = :c0');
		$stmt->bindValue(':c0', $ratingId, \PDO::PARAM_INT);
		if (!$stmt->execute()) {
			throw new \Error("An error occured retrieving data from the database. Error info: " . $stmt->errorInfo()[2]);
		}

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function GetShowsByCountry(int $countryId): ?array
	{
		$stmt = $this->db->prepare('SELECT * FROM `Countries` `c` 
								JOIN `ShowCountries` `sc` ON `c`.`Id` = `sc`.`CountryId`
								LEFT JOIN `vw_Shows` `s` ON `sc`.`ShowId` = `s`.`Id`
								WHERE `c`.`Id` = :c0');
		$stmt->bindValue(':c0', $countryId, \PDO::PARAM_INT);
		if (!$stmt->execute()) {
			throw new \Error("An error occured retrieving data from the database. Error info: " . $stmt->errorInfo()[2]);
		}

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function GetShowCountByCountry(?int $typeId): ?array
	{
		$this->setGroupConcatMaxLength(65535);
		$stmt = $this->db->prepare('SELECT DISTINCT `Year`, `c`.`Name` AS Country, COUNT(`s`.`Id`) AS `Count`, GROUP_CONCAT(`s`.`Id`) AS `ShowIds` FROM `ReleaseYears` AS `r` 
									JOIN `Shows` AS `s` ON `r`.`Id` = `s`.`ReleaseYearId`
									JOIN `ShowCountries` AS `sc` ON `sc`.`ShowId` = `s`.`Id`
									JOIN `Countries` AS `c` ON `sc`.`CountryId` = `c`.`Id` ' .
			($typeId !== null ? 'WHERE `s`.`ShowTypeId` = :c0' : '') .
			' GROUP BY `Year`, `Country`
									ORDER BY `Year`');
		if ($typeId !== null) {
			$stmt->bindValue(':c0', $typeId, \PDO::PARAM_INT);
		}
		if (!$stmt->execute()) {
			throw new \Error("An error occured retrieving data from the database. Error info: " . $stmt->errorInfo()[2]);
		}

		$rawData = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		$groupedData = [];
		foreach ($rawData as $row) {
			if (!isset($groupedData[$row['Year']])) {
				$groupedData[$row['Year']] = [];
			}

			$groupedData[$row['Year']][] = ["Country" => $row['Country'], "Count" => $row['Count'], "ShowIds" => array_map(function (int $val) {
				return $val; //Type coercing through the param type
			}, explode(',', $row['ShowIds']))];
		}

		return $groupedData;
	}

	public function GetShowCountByCountryByYear(?int $typeId): ?array
	{
		$this->setGroupConcatMaxLength(65535);
		$stmt = $this->db->prepare('SELECT DISTINCT `Year`, `c`.`Name` AS Country, COUNT(`s`.`Id`) AS `Count`, GROUP_CONCAT(`s`.`Id`) AS `ShowIds` FROM `ReleaseYears` AS `r` 
									JOIN `Shows` AS `s` ON `r`.`Id` = `s`.`ReleaseYearId`
									JOIN `ShowCountries` AS `sc` ON `sc`.`ShowId` = `s`.`Id`
									JOIN `Countries` AS `c` ON `sc`.`CountryId` = `c`.`Id` ' .
									($typeId !== null ? 'WHERE `s`.`ShowTypeId` = :c0' : '') .
									' GROUP BY `Year`, `Country`
									ORDER BY `Year`');
		if($typeId !== null) {
			$stmt->bindValue(':c0', $typeId, \PDO::PARAM_INT);
		}
		if (!$stmt->execute()) {
			throw new \Error("An error occured retrieving data from the database. Error info: " . $stmt->errorInfo()[2]);
		}

		$rawData = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		$groupedData = [];
		foreach($rawData as $row) {
			if(!isset($groupedData[$row['Year']])) {
				$groupedData[$row['Year']] = [];
			}

			$groupedData[$row['Year']][] = ["Country" => $row['Country'], "Count" => $row['Count'], "ShowIds" => array_map(function (int $val) {
				return $val; //Type coercing through the param type
			},explode(',', $row['ShowIds'])) ];
		}

		return $groupedData;
	}

	public function GetTvShowsByYear(int $year): ?array
	{
		$stmt = $this->db->prepare('SELECT * FROM vw_TvShows WHERE ReleaseYear = :c0');
		$stmt->bindValue(':c0', $year, \PDO::PARAM_INT);
		if (!$stmt->execute()) {
			throw new \Error("An error occured retrieving data from the database. Error info: " . $stmt->errorInfo()[2]);
		}

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function GetMoviesByYear(int $year): ?array
	{
		$stmt = $this->db->prepare('SELECT * FROM vw_Movies WHERE ReleaseYear = :c0');
		$stmt->bindValue(':c0', $year, \PDO::PARAM_INT);
		if (!$stmt->execute()) {
			throw new \Error("An error occured retrieving data from the database. Error info: " . $stmt->errorInfo()[2]);
		}

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function GetShows(): ?array
	{
		$stmt = $this->db->prepare('SELECT * FROM vw_Shows');
		if (!$stmt->execute()) {
			throw new \Error("An error occured retrieving data from the database. Error info: " . $stmt->errorInfo()[2]);
		}

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	private function setGroupConcatMaxLength(int $length) 
	{
		$this->db->query("SET SESSION group_concat_max_len = $length;");
	}

}