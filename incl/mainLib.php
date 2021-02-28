<?php
class MainLib {
	public static function getListingType($db, $id){
		$type = $db->prepare("SELECT part.type
			FROM listing
			INNER JOIN part ON listing.part_id = part.id
			WHERE listing.id = :id");
		$type->execute(['id' => $id]);
		return $type->fetchColumn();
	}
	public static function getPartIDFromListing($db, $id){
		$type = $db->prepare("SELECT listing.part_id
			FROM listing
			WHERE listing.id = :id");
		$type->execute(['id' => $id]);
		return $type->fetchColumn();
	}
	public static function getCurrentChoiceArray($type){
		$partarray = [];
		if(!isset($_SESSION["part"]) || empty($_SESSION["part"]))
			return [];
		foreach($_SESSION['part'] AS &$part)
			$partarray[] = preg_replace('[^0-9]', '', $part);
		return $partarray;
	}
	public static function getTotalTDP($db){
		$tdp = 0;
		$cpus = MainLib::getCurrentChoicesInfo($db, 'cpu');
		if($cpus)
			foreach($cpus as &$cpu){
				$cpu = new CPU($db, MainLib::getPartIDFromListing($db, $cpu['id']));
				$tdp += $cpu->getTDP();
			}

		$gpus = MainLib::getCurrentChoicesInfo($db, 'gpu');
		if($gpus)
			foreach($gpus as &$gpu){
				$gpu = new GPU($db, MainLib::getPartIDFromListing($db, $gpu['id']));
				$tdp += $gpu->getTDP();
			}

		$motherboards = MainLib::getCurrentChoicesInfo($db, 'motherboard');
		if($motherboards)
			foreach($motherboard as &$motherboards){
				$tdp += 70;
			}

		$rams = MainLib::getCurrentChoicesInfo($db, 'ram');
		if($rams)
			foreach($ram as &$rams){
				$tdp += 20;
			}

		$storages = MainLib::getCurrentChoicesInfo($db, 'storage');
		if($rams)
			foreach($storage as &$storages){
				$storage = new Storage($db, MainLib::getPartIDFromListing($db, $storage['id']));
				$tdp += 10 + round($storage->getRPM() / 1080);
			}
			
		return $tdp;
		
	}
	public static function getCurrentChoicesInfo($db, $type, $single = false){
		$choicesArray = MainLib::getCurrentChoiceArray($type);
		if(empty($choicesArray))
			return [];
		$choices = implode(',', $choicesArray);
		$listings = $db->prepare("SELECT part.model, listing.price, listing.store, listing.store_url, listing.id
			FROM listing
			INNER JOIN part ON listing.part_id = part.id
			WHERE listing.id IN ($choices) AND type = :type ");
		$listings->execute(['type' => $type]);
		return $listings->fetchAll();
	}
	public static function getCurrentChoiceID($db, $type){
		$choice = MainLib::getCurrentChoicesInfo($db, $type, true);
		if(empty($choice))
			return false;
		return $choice['id'];
	}
	public static function getListings($db, $type, $incompatible = false){
		$tables = ['case' => 'part_case', 'cpu' => 'part_cpu', 'gpu' => 'part_gpu', 'motherboard' => 'part_motherboard', 'optical' => 'part_optical', 'os' => 'part_os', 'psu' => 'part_psu', 'ram' => 'part_ram', 'storage' => 'part_storage'];
		if(!isset($tables[$type]))
			return false;

		$where = $typespecific = "";
		$pdoArray = [];
		switch($type){
			case 'case':
				$motherboard = MainLib::getCurrentChoiceID($db, 'motherboard');
				if($motherboard){
					$motherboard = new Motherboard($db, MainLib::getPartIDFromListing($db, $motherboard));
					$where = "AND part_case.motherboard_form_factor = :motherboardFormFactor";
					$pdoArray['motherboardFormFactor'] = $motherboard->getFormFactor();
				}
				$typespecific = ", part_case.motherboard_form_factor";
				break;
			case 'cpu':
				$motherboard = MainLib::getCurrentChoiceID($db, 'motherboard');
				if($motherboard){
					$motherboard = new Motherboard($db, MainLib::getPartIDFromListing($db, $motherboard));
					$where = "AND part_cpu.cpu_socket = :cpuSocket";
					$pdoArray['cpuSocket'] = $motherboard->getCPUSocket();
				}
				$typespecific = ", part_cpu.cpu_socket, part_cpu.frequency, part_cpu.core_count, part_cpu.tdp, part_cpu.userbenchmark_score";
				break;
			case 'gpu':
				$typespecific = ", part_gpu.vram, part_gpu.tdp, part_gpu.userbenchmark_score";
				break;
			case 'motherboard':
				$where = "";
				$cpu = MainLib::getCurrentChoiceID($db, 'cpu');
				if($cpu){
					$cpu = new CPU($db, MainLib::getPartIDFromListing($db, $cpu));
					$where .= "AND part_motherboard.cpu_socket = :cpuSocket ";
					$pdoArray['cpuSocket'] = $cpu->getCPUSocket();
				}
				$ram = MainLib::getCurrentChoiceID($db, 'ram');
				if($ram){
					$ram = new RAM($db, MainLib::getPartIDFromListing($db, $ram));
					$where .= "AND part_motherboard.ddr_version = :ddr_version ";
					$pdoArray['ddrVersion'] = $ram->getDDRVersion();
				}
				$typespecific = ", part_motherboard.motherboard_form_factor, part_motherboard.cpu_socket, part_motherboard.ddr_version";
				break;
			case 'optical':
				$typespecific = ", part_optical.optical_type";
				break;
			case 'os':
				$typespecific = ", part_os.invoice";
				break;
			case 'psu':
				$typespecific = ", part_psu.wattage";
				$where .= "AND part_psu.wattage = :wattage ";
				$pdoArray['wattage'] = MainLib::getTotalTDP($db);
				break;
			case 'ram':
				$motherboard = MainLib::getCurrentChoiceID($db, 'motherboard');
				if($motherboard){
					$motherboard = new Motherboard($db, MainLib::getPartIDFromListing($db, $motherboard));
					$where = "AND part_ram.ddr_version = :ddr_version ";
					$pdoArray['ddrVersion'] = $ram->getDDRVersion();
				}
				$typespecific = ", part_ram.size, part_ram.ddr_version, part_ram.speed";
				break;
			case 'storage':
				$typespecific = ", part_storage.storage_type, part_storage.size, part_storage.connector";
				break;
		}
		if($incompatible){
			$where = "";
			$pdoArray = [];
		}
		$listings = $db->prepare("SELECT part.model, part.type, listing.item_condition, listing.price, listing.store, listing.location, listing.id, listing.name {$typespecific}
			FROM listing
			INNER JOIN part ON listing.part_id = part.id
			INNER JOIN {$tables[$type]} ON {$tables[$type]}.part_id = part.id
			WHERE part.type = :type {$where}");
		$listings->execute(array_merge(['type' => $type], $pdoArray));
		return $listings->fetchAll();
	}
}