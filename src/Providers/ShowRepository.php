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

	public function GetShowsByCountry(int $showId): ?array
	{
		$stmt = $this->db->prepare('SELECT * FROM `Countries` `c` 
								JOIN `ShowCountries` `sc` ON `c`.`Id` = `sc`.`CountryId`
								LEFT JOIN `vw_Shows` `s` ON `sc`.`ShowId` = `s`.`Id`
								WHERE `c`.`Id` = :c0');
		$stmt->bindValue(':c0', $showId, \PDO::PARAM_INT);
		if (!$stmt->execute()) {
			throw new \Error("An error occured retrieving data from the database. Error info: " . $stmt->errorInfo()[2]);
		}

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
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

}