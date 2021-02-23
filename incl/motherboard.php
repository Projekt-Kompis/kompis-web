<?php
class Motherboard {
    protected $id;
    protected $db;

    public function __construct($db, $id) {
        $this->id = $id;
        $this->db = $db;
    }

    public function getID(){
    	return $this->id;
    }

    public function getFormFactor(){
    	$type = $this->db->prepare("SELECT part_motherboard.motherboard_form_factor
			FROM part_motherboard
			WHERE part_motherboard.part_id = :id");
		$type->execute(['id' => $this->id]);
		return $type->fetchColumn();
    }

    public function getCPUSocket(){
    	$type = $this->db->prepare("SELECT part_motherboard.cpu_socket
			FROM part_motherboard
			WHERE part_motherboard.part_id = :id");
		$type->execute(['id' => $this->id]);
		return $type->fetchColumn();
    }

    public function getDDRVersion(){
        $type = $this->db->prepare("SELECT part_motherboard.ddr_version
            FROM part_motherboard
            WHERE part_motherboard.part_id = :id");
        $type->execute(['id' => $this->id]);
        return $type->fetchColumn();
    }
}