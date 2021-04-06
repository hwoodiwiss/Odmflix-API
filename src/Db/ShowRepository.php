<?php

namespace OdmflixApi;

class ShowRepository
{
	private Mapper $modelMapper;
	public function __construct(private Db $db) {
		$this->modelMapper = new Mapper(ShowModel::class);
	}

	public function GetShowById(int $id): ShowModel
	{
		$stmt = $this->db->prepare('SELECT * FROM vw_Shows WHERE Id = :c0');
		$stmt->bindValue(':c0', $id, \PDO::PARAM_INT);
		if (!$stmt->execute()) {
			throw new \Error("An error occured retrieving data from the database. Error info: " . $stmt->errorInfo()[2]);
		}

		$data = $stmt->fetchAll();
		if(count($data) > 0) {
			throw new \Error("Invalid reponse from a primary key search");
		}
		
		return $this->modelMapper->map($data);
	}

}