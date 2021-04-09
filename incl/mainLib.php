<?php
class MainLib {
	public static function getPartTypes(){
		return ['case', 'cpu', 'gpu', 'motherboard', 'optical', 'os', 'psu', 'ram', 'storage'];
	}
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
	public static function getTotalTDP($db){ //TODO: default values when 0, add optical
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
			foreach($motherboards as &$motherboard){
				$tdp += 70;
			}

		$rams = MainLib::getCurrentChoicesInfo($db, 'ram');
		if($rams)
			foreach($rams as &$ram){
				$tdp += 20;
			}

		$storages = MainLib::getCurrentChoicesInfo($db, 'storage');
		if($storages)
			foreach($storages as &$storage){
				$storage = new Storage($db, MainLib::getPartIDFromListing($db, $storage['id']));
				$tdp += 10 + round($storage->getRPM() / 1080);
			}
			
		return $tdp;
		
	}
	public static function getCurrentChoicesInfo($db, $type, $single = false){
		$choicesArray = MainLib::getCurrentChoiceArray($type);
		return MainLib::getChoicesInfo($db, $type, $choicesArray, $single);
	}
	public static function getReplacementChoice($db, $listingID){
		$listing = $db->prepare("SELECT part.type, part.id
			FROM listing
			INNER JOIN part ON listing.part_id = part.id
			WHERE listing.id = :listing");
		$listing->execute(['listing' => $listingID]);
		$listing = $listing->fetch();
		switch($listing['type']){
			case "cpu":
			case "gpu":
				$newListing = $db->prepare("SELECT listing.id
					FROM listing
					INNER JOIN part ON listing.part_id = part.id
					WHERE part.id = :partID
					AND listing.is_invalid = 0
					AND price > 10
					ORDER BY price ASC
					LIMIT 1");
				$newListing->execute(['partID' => $listing['id']]);
				if($newListing->rowCount() < 1)
					return false;
				return $newListing->fetchColumn();
				break;
			case "os":
				$os = new OS($db, $listing['id']);
				$newListing = $db->prepare("SELECT listing.id
					FROM listing
					INNER JOIN part ON listing.part_id = part.id
					INNER JOIN part_os ON part_os.part_id = part.id
					WHERE part_os.invoice = :invoice
					AND listing.is_invalid = 0
					AND price > 10
					AND name LIKE '%windows 10%'
					ORDER BY price ASC
					LIMIT 1");
				$newListing->execute(['invoice' => $os->getInvoice()]);
				if($newListing->rowCount() < 1)
					return false;
				return $newListing->fetchColumn();
			case "ram":
				$ram = new RAM($db, $listing['id']);
				$newListing = $db->prepare("SELECT listing.id
					FROM listing
					INNER JOIN part ON listing.part_id = part.id
					INNER JOIN part_ram ON part_ram.part_id = part.id
					WHERE part_ram.ddr_version = :ddr_version
					AND part_ram.speed = :speed
					AND part_ram.size = :size
					AND listing.is_invalid = 0
					AND price > 10
					ORDER BY price ASC
					LIMIT 1");
				$newListing->execute(['ddr_version' => $ram->getDDRVersion(), 'speed' => $ram->getSpeed(), 'size' => $ram->getSize()]);
				if($newListing->rowCount() < 1){
					return false;
				}
				return $newListing->fetchColumn();
				break;
			case "storage":
				$storage = new storage($db, $listing['id']);
				$newListing = $db->prepare("SELECT listing.id
					FROM listing
					INNER JOIN part ON listing.part_id = part.id
					INNER JOIN part_storage ON part_storage.part_id = part.id
					WHERE part_storage.storage_type = :storage_type
					AND part_storage.connector = :connector
					AND part_storage.rpm = :rpm
					AND part_storage.size = :size
					AND listing.is_invalid = 0
					AND price > 10
					ORDER BY price ASC
					LIMIT 1");
				$newListing->execute(['storage_type' => $storage->getStorageType(), 'connector' => $storage->getConnector(), 'rpm' => $storage->getRPM(), 'size' => $storage->getSize()]);
				if($newListing->rowCount() < 1){
					return false;
				}
				return $newListing->fetchColumn();
				break;
			
		}
		return false;
	}
	public static function getChoicesInfo($db, $type, $choicesArray, $single = false){
		if(empty($choicesArray))
			return [];
		$choices = implode(',', $choicesArray);
		$listings = $db->prepare("SELECT part.model, listing.price, listing.store, listing.store_url, listing.id, listing.is_invalid
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
		return $choice[0]['id'];
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
					$where .= "AND part_motherboard.ddr_version = :ddrVersion ";
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
				$where .= "AND part_psu.wattage >= :wattage ";
				$pdoArray['wattage'] = MainLib::getTotalTDP($db);
				break;
			case 'ram':
				$motherboard = MainLib::getCurrentChoiceID($db, 'motherboard');
				if($motherboard){
					$motherboard = new Motherboard($db, MainLib::getPartIDFromListing($db, $motherboard));
					$where = "AND part_ram.ddr_version = :ddrVersion ";
					$pdoArray['ddrVersion'] = $motherboard->getDDRVersion();
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
		$listings = $db->prepare("SELECT part.model, part.type, listing.item_condition, listing.price, listing.store, listing.location, listing.id, listing.name, listing.store_url, listing.listing_score {$typespecific}
			FROM listing
			INNER JOIN part ON listing.part_id = part.id
			INNER JOIN {$tables[$type]} ON {$tables[$type]}.part_id = part.id
			WHERE part.type = :type {$where}
			AND is_invalid = 0
			ORDER BY listing_score DESC");
		$listings->execute(array_merge(['type' => $type], $pdoArray));
		return $listings->fetchAll();
	}
}