<?php
class AssemblyRevision {
    protected $id;
    protected $db;
    protected $assemblyID;
    protected $assemblyName;
    protected $accountID;
    protected $username;
    protected $listings;

    public function __construct($db, $id) {
        $this->id = $id;
        $this->db = $db;

        $assembly = $db->prepare("SELECT assembly.id, assembly.name, assembly.account_id
            FROM assembly
            INNER JOIN assembly_revision ON assembly.id = assembly_revision.assembly_id
            WHERE assembly_revision.id = :id");
        $assembly->execute(['id' => $id]);
        $assembly = $assembly->fetch();
        $this->assemblyID = $assembly['id'];
        $this->assemblyName = $assembly['name'];
        $this->accountID = $assembly['account_id'];
        if($this->accountID != null)
            $this->username = AccountManager::getUsername($this->db, $this->accountID);
        else
            $this->username = "Neznámý"; //TODO: not have the Czech string hardcoded here
    }

    public function getID(){
    	return $this->id;
    }

    public function getAssemblyName(){
        return $this->assemblyName;
    }

    public function getAssemblyID(){
        return $this->assemblyID;
    }
    
    public function getUsername(){
        return $this->username;
    }

    public function getListingList(){
        if(!isset($this->listings)){
            $listings = $this->db->prepare("SELECT listing_id FROM assembly_listing WHERE assembly_revision_id = :id");
            $listings->execute([':id' => $this->id]);
            foreach($listings->fetchAll() as &$listing)
                $this->listings[] = $listing['listing_id'];
        }
		return $this->listings;
    }
}