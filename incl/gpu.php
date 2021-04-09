<?php
class GPU {
    protected $id;
    protected $db;

    public function __construct($db, $id) {
        $this->id = $id;
        $this->db = $db;
    }

    public function getID(){
    	return $this->id;
    }

    public function getTDP(){
        $type = $this->db->prepare("SELECT part_gpu.tdp
            FROM part_gpu
            WHERE part_gpu.part_id = :id");
        $type->execute(['id' => $this->id]);
        $tdp = $type->fetchColumn();
        if($tdp == 0)
            return 150;
        return $tdp;
    }
}