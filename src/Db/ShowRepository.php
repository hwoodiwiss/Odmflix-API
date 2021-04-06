<?php

namespace OdmflixApi;

class ShowRepository
{
	private Mapper $modelMapper;
	public function __construct(private Db $db) {
		$this->modelMapper = new Mapper(ShowModel::class);
	}

	public function GetShowById(int $id): ?ShowModel
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
		
		return $this->modelMapper->map($data[0]);
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

	public function GetShows(): ?array
	{
		$stmt = $this->db->prepare('SELECT * FROM vw_Shows');
		if (!$stmt->execute()) {
			throw new \Error("An error occured retrieving data from the database. Error info: " . $stmt->errorInfo()[2]);
		}

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

}