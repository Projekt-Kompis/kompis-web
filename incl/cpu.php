<?php
class CPU {
    protected $id;
    protected $db;

    public function __construct($db, $id) {
        $this->id = $id;
        $this->db = $db;
    }

    public function getID(){
    	return $this->id;
    }

    public function getCPUSocket(){
    	$type = $this->db->prepare("SELECT part_cpu.cpu_socket
			FROM part_cpu
			WHERE part_cpu.part_id = :id");
		$type->execute(['id' => $this->id]);
		return $type->fetchColumn();
    }

    public function getTDP(){
        $type = $this->db->prepare("SELECT part_cpu.tdp
            FROM part_cpu
            WHERE part_cpu.part_id = :id");
        $type->execute(['id' => $this->id]);
        return $type->fetchColumn();
    }
}