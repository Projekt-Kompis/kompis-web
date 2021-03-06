<?php
class AssemblyManager {
	public static function createAssembly($db, $name, $visibility, $listings){
		if(AccountManager::isAccountLoggedIn())
			$accountID = AccountManager::getLoggedinAccountID();
		else
			$accountID = null;
		$query = $db->prepare("INSERT INTO assembly (name, account_id, visibility)
		VALUES (:name, :accountID, :visibility)");
		$query->execute([':name' => $name, ':accountID' => $accountID, ':visibility' => $visibility]);
		$id = $db->lastInsertID();
		AssemblyManager::updateAssembly($db, $id, $listings);
		return $id; //success
	}
	public static function updateAssembly($db, $assemblyID, $listings){
		$query = $db->prepare("INSERT INTO assembly_revision (assembly_id)
			VALUES (:assemblyID)");
		$query->execute([':assemblyID' => $assemblyID]);
		$id = $db->lastInsertID();
		foreach($listings as &$listing){
			$query = $db->prepare("INSERT INTO assembly_listing (assembly_revision_id, listing_id)
			VALUES (:id, :listing)");
			$query->execute([':id' => $id,':listing' => $listing]);
		}
		return $id;
	}
	public static function getLatestRevisionID($db, $assemblyID){
		$username = $db->prepare("SELECT id FROM assembly_revision WHERE assembly_id = :assemblyID ORDER BY time_created DESC");
		$username->execute([':assemblyID' => $assemblyID]);
		if($username->rowCount() == 0)
			return false;
		return $username->fetchColumn();
	}
	public static function getAssemblyList($db){
		$assemblyArray = [];
		$listings = $db->prepare("SELECT assembly.id
			FROM assembly
			WHERE visibility = 'public'
			ORDER BY points_average_weighted DESC, points_average DESC");
		$listings->execute();
		foreach($listings->fetchAll() as &$assembly)
			$assemblyArray[] = $assembly['id'];
		return $assemblyArray;
	}
	public static function rateAssembly($db, $points, $assemblyID, $accountID){
		$rating = $db->prepare("INSERT INTO assembly_rating (points, account_id, assembly_id)
				VALUES (:points, :accountID, :assemblyID)
				ON DUPLICATE KEY UPDATE points = VALUES(points), id=LAST_INSERT_ID(id)");
		$rating->execute([':points' => $points, ':accountID' => $accountID, ':assemblyID' => $assemblyID]);
		AssemblyManager::recalculateRating($db, $assemblyID);
		return true;
	}
	public static function recalculateRating($db, $assemblyID){
		$rating = $db->prepare("
			UPDATE assembly a 
			INNER JOIN
			(
			   SELECT assembly_id, (SUM(points)/COUNT(*)) 'average'
			   FROM assembly_rating 
			   GROUP BY assembly_id
			) b ON a.id = b.assembly_id
			SET a.points_average = b.average
			WHERE a.id = :assemblyID
		");
		$rating->execute([':assemblyID' => $assemblyID]);
		$rating = $db->prepare("
			UPDATE assembly a 
			INNER JOIN
			(
			   SELECT assembly_id, (SUM(points)/(COUNT(*)*0.9)) 'average'
			   FROM assembly_rating 
			   GROUP BY assembly_id
			) b ON a.id = b.assembly_id
			SET a.points_average_weighted = b.average
			WHERE a.id = :assemblyID
		");
		$rating->execute([':assemblyID' => $assemblyID]);

	}
}