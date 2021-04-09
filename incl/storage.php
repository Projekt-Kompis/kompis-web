<?php
class Storage {
    protected $id;
    protected $db;

    public function __construct($db, $id) {
        $this->id = $id;
        $this->db = $db;
    }

    public function getID(){
    	return $this->id;
    }

    public function getRPM(){
    	$info = $this->db->prepare("SELECT part_storage.rpm, part_storage.storage_type
			FROM part_storage
			WHERE part_storage.part_id = :id");
		$info->execute(['id' => $this->id]);
		$info = $info->fetch();
        if($info['storage_type'] == 'ssd')
            return 0;
        return $info['rpm'];
    }

    public function getStorageType(){
        $info = $this->db->prepare("SELECT part_storage.storage_type
            FROM part_storage
            WHERE part_storage.part_id = :id");
        $info->execute(['id' => $this->id]);
        return $info->fetchColumn();
    }

    public function getConnector(){
        $info = $this->db->prepare("SELECT part_storage.connector
            FROM part_storage
            WHERE part_storage.part_id = :id");
        $info->execute(['id' => $this->id]);
        return $info->fetchColumn();
    }

    public function getSize(){
        $info = $this->db->prepare("SELECT part_storage.size
            FROM part_storage
            WHERE part_storage.part_id = :id");
        $info->execute(['id' => $this->id]);
        return $info->fetchColumn();
    }
}