<?php
class AssemblyRevision {
    protected $id;
    protected $db;
    protected $assemblyID;
    protected $assemblyName;
    protected $accountID;
    protected $username;
    protected $listings;
    protected $price;
    protected $pointsAverage;
    protected $timeCreated;

    public function __construct($db, $id) {
        $this->id = $id;
        $this->db = $db;

        $assembly = $db->prepare("SELECT assembly.id, assembly.name, assembly.account_id, assembly.points_average, assembly_revision.time_created
            FROM assembly
            INNER JOIN assembly_revision ON assembly.id = assembly_revision.assembly_id
            WHERE assembly_revision.id = :id");
        $assembly->execute(['id' => $id]);
        $assembly = $assembly->fetch();
        $this->assemblyID = $assembly['id'];
        $this->assemblyName = $assembly['name'];
        $this->accountID = $assembly['account_id'];
        $this->pointsAverage = $assembly['points_average'];
        $this->timeCreated = $assembly['time_created'];
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

    public function getPointsAverage(){
        return $this->pointsAverage;
    }

    public function getTimeCreated(){
        return $this->timeCreated;
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
    public function getAccountRating($accountID){
        $rating = $this->db->prepare("SELECT points FROM assembly_rating WHERE account_id = :accountID AND assembly_id = :assemblyID");
        $rating->execute([':accountID' => $accountID, ':assemblyID' => $this->assemblyID]);
        if($rating->rowCount() < 1)
            return 0;
        return $rating->fetchColumn();
    }
    public function getPrice(){
        if(!isset($this->price)){
            $price = $this->db->prepare("
                SELECT sum(b.price)
                FROM assembly_listing a
                INNER JOIN listing b
                ON a.listing_id = b.id
                WHERE a.assembly_revision_id = :id");
            $price->execute([':id' => $this->id]);
            $this->price = $price->fetchColumn();
        }
        return $this->price;
    }
}