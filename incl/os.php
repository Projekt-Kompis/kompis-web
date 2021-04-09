<?php
class OS {
    protected $id;
    protected $db;

    public function __construct($db, $id) {
        $this->id = $id;
        $this->db = $db;
    }

    public function getID(){
    	return $this->id;
    }

    public function getInvoice(){
    	$type = $this->db->prepare("SELECT part_os.invoice
			FROM part_os
			WHERE part_os.part_id = :id");
		$type->execute(['id' => $this->id]);
		return $type->fetchColumn();
    }
}