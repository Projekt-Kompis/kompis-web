<?php
class AccountManager {
	public static function isAccountLoggedIn(){
		return isset($_SESSION['accountID']) && $_SESSION['accountID'] > 0;
	}
	public static function createAccount($db, $username, $email, $password){
		$existingRegistraitons = $db->prepare("SELECT count(*) FROM account WHERE username LIKE :username");
		$existingRegistraitons->execute([':username' => $username]);
		$existingRegistraitons = $existingRegistraitons->fetchColumn();
		if ($existingRegistraitons > 0) 
			return -2; //username taken
		$existingRegistraitons = $db->prepare("SELECT count(*) FROM account WHERE email LIKE :email");
		$existingRegistraitons->execute([':email' => $email]);
		$existingRegistraitons = $existingRegistraitons->fetchColumn();
		if ($existingRegistraitons > 0) 
			return -3; //email taken
		$password = password_hash($password, PASSWORD_DEFAULT);
		$query = $db->prepare("INSERT INTO account (username, password, email)
		VALUES (:userName, :password, :email)");
		$query->execute([':userName' => $username, ':password' => $password, ':email' => $email]);
		return 0; //success
	}
	public static function verifyPassword($db, $username, $password){
		$userinfo = $db->prepare("SELECT id, password FROM account WHERE username LIKE :username");
		$userinfo->execute([':username' => $username]);
		if($userinfo->rowCount() == 0)
			return -2; //username not registered
		$userinfo = $userinfo->fetch();
		if(password_verify($password, $userinfo['password']))
			return $userinfo['id'];
		else
			return -3; //invalid password
		return -1; //fallback
	}
	public static function getLoggedinUsername($db){
		return AccountManager::getUsername($db, $_SESSION['accountID']);
	}
	public static function getUsername($db, $id){
		$username = $db->prepare("SELECT username FROM account WHERE id = :id");
		$username->execute([':id' => $id]);
		if($username->rowCount() == 0)
			return false;
		return $username->fetchColumn();
	}
}