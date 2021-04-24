<?php

namespace OdmflixApi;

class ActorsRepository
{
	public function __construct(private Db $db) {
	}

	public function GetTypes(): array {
		$stmt = $this->db->prepare('SELECT * FROM ShowTypes');

		if (!$stmt->execute()) {
			throw new \Error("An error occured retrieving data from the database. Error info: " . $stmt->errorInfo()[2]);
		}
		$data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

		return $data;
	}

	public function GetTopNActorsForShows(int $n, array $showIds)
	{

		$paramList = array_map(function($idx) {
			return ":c$idx";
		}, array_keys($showIds));
		$paramListStr = implode(',', $paramList);
		$stmt = $this->db->prepare("SELECT `a`.`Name` AS `Actor`, COUNT(`s`.`Id`) AS `Count` FROM `Actors` `a`
									JOIN `ShowActors` `sa` ON `sa`.`ActorId` = `a`.`Id`
									JOIN `Shows` `s` ON `sa`.`ShowId` = `s`.`Id`
									WHERE `s`.`Id` IN ($paramListStr)
									GROUP BY `Actor`
									ORDER BY `Count` DESC
									LIMIT :n0");
		foreach($paramList as $idx => $param) {
			$stmt->bindValue($param, $showIds[$idx], \PDO::PARAM_INT);
		}
		$stmt->bindValue(':n0', $n, \PDO::PARAM_INT);

		if (!$stmt->execute()) {
			throw new \Error("An error occured retrieving data from the database. Error info: " . $stmt->errorInfo()[2]);
		}

		$data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
		
		return $data;
	}


	private function setGroupConcatMaxLength(int $length) 
	{
		$this->db->query("SET SESSION group_concat_max_len = $length;");
	}
}