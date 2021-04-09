<?php
class RAM {
    protected $id;
    protected $db;

    public function __construct($db, $id) {
        $this->id = $id;
        $this->db = $db;
    }

    public function getID(){
    	return $this->id;
    }

    public function getDDRVersion(){
    	$type = $this->db->prepare("SELECT part_ram.ddr_version
			FROM part_ram
			WHERE part_ram.part_id = :id");
		$type->execute(['id' => $this->id]);
		return $type->fetchColumn();
    }

    public function getSpeed(){
        $type = $this->db->prepare("SELECT part_ram.speed
            FROM part_ram
            WHERE part_ram.part_id = :id");
        $type->execute(['id' => $this->id]);
        return $type->fetchColumn();
    }

    public function getSize(){
        $type = $this->db->prepare("SELECT part_ram.size
            FROM part_ram
            WHERE part_ram.part_id = :id");
        $type->execute(['id' => $this->id]);
        return $type->fetchColumn();
    }
}