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
}