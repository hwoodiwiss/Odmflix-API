<?php

namespace OdmflixApi;

class DirectorsRepository
{
	public function __construct(private Db $db) {
	}

	public function GetAll(): array {
		$stmt = $this->db->prepare('SELECT * FROM `Directors`
									ORDER BY `Name`');

		if (!$stmt->execute()) {
			throw new \Error("An error occured retrieving data from the database. Error info: " . $stmt->errorInfo()[2]);
		}
		$data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

		return $data;
	}

	public function GetShowsForDirector(int $directorId): array
	{
		$stmt = $this->db->prepare('SELECT `s`.* FROM `Directors`  AS `d`
									JOIN `ShowDirectors` AS `sd` ON `sd`.`DirectorId` = `d`.`Id`
									JOIN `vw_Shows` AS `s` ON `sd`.`ShowId` = `s`.`Id`
									WHERE `d`.`Id` = :c0;');
		
		$stmt->bindValue(':c0', $directorId, \PDO::PARAM_INT);
		if (!$stmt->execute()) {
			throw new \Error("An error occured retrieving data from the database. Error info: " . $stmt->errorInfo()[2]);
		}
		$data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

		return $data;
	}
}