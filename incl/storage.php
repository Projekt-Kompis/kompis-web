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
    	$info = $this->db->prepare("SELECT part_storage.rpm, part_storage.type
			FROM part_storage
			WHERE part_storage.part_id = :id");
		$info->execute(['id' => $this->id]);
		$info = $info->fetch();
        if($info['type'] == 'ssd')
            return 0;
        return $info['rpm'];
    }
}