<?php
require_once("/home/ubuntu/workspace/include/service/LeadConnectService.php");
require_once("/home/ubuntu/workspace/include/entity/Account.php");
require_once("/home/ubuntu/workspace/include/entity/Vendor.php");
require_once("/home/ubuntu/workspace/include/entity/Prospect.php");
require_once("/home/ubuntu/workspace/include/entity/Inquiry.php");
require_once("/home/ubuntu/workspace/include/entity/ProspectInquiry.php");
require_once("/home/ubuntu/workspace/include/entity/Event.php");

class SimplePdoLeadConnectService implements LeadConnectService {
    
    private static $servername = "0.0.0.0";
    private static $username = "root";
    private static $password = "LeadConnect1!";
    private static $database = "leadconnect";
    private static $dbport = 3306;

	private function getConnection() {
		return new PDO(
		    "mysql:dbname=" . self::$database . ";host=" . self::$servername . ";port=" . self::$dbport,self::$username,self::$password,
			Array(
				PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			)
		);
	}
	
	public function loadAccounts(){
		if ($this->conn == null) $this->conn = $this->getConnection();
		$st = $this->conn->prepare("SELECT id, name, host, phone, api_key FROM account");
		$result = $st->execute();
		$rows = $st->fetchAll(PDO::FETCH_ASSOC);
		$result = Array();
		foreach ($rows as $row) {
			$result[] = $this->inflateAccount($row);
		}
		return $result;
	}
	
	public function loadAccount($id) {
		if ($this->conn == null) $this->conn = $this->getConnection();
		$st = $this->conn->prepare("SELECT id, name, host, phone, api_key FROM account WHERE id=:id");
		$result = $st->execute(Array("id"=> $id));
		if (!$result) return null;

		$row = $st->fetch(PDO::FETCH_ASSOC);
		if (!$row) return null;

		return $this->inflateAccount($row);
	}
	
	public function loadAccountFromPhone($phone) {
		if ($this->conn == null) $this->conn = $this->getConnection();
		$st = $this->conn->prepare("SELECT id, name, host, phone, api_key FROM account WHERE phone=:phone");
		$result = $st->execute(Array("phone"=> $phone));
		if (!$result) return null;

		$row = $st->fetch(PDO::FETCH_ASSOC);
		if (!$row) return null;

		return $this->inflateAccount($row);
	}
	
	public function loadAccountFromKey($api_key) {
		if ($this->conn == null) $this->conn = $this->getConnection();
		$st = $this->conn->prepare("SELECT id, name, host, phone, api_key FROM account WHERE api_key=:api_key");
		$result = $st->execute(Array("api_key"=> $api_key));
		if (!$result) return null;

		$row = $st->fetch(PDO::FETCH_ASSOC);
		if (!$row) return null;

		return $this->inflateAccount($row);
	}
	
	public function deleteAccount($id){
		if ($this->conn == null) $this->conn = $this->getConnection();
		$st = $this->conn->prepare("DELETE FROM account WHERE id = :id");
		$result = $st->execute(Array("id" => $id));
		return true;
	}
	
	public function saveAccount(Account $account){
		if ($account->id) {
			return $this->_updateAccount($account);
		}
		else {
			return $this->_createAccount($account);
		}
	}

	private function _createAccount(Account $account) {
		if ($this->conn == null) $this->conn = $this->getConnection();
		$st = $this->conn->prepare("INSERT INTO account (name, host, phone, api_key) VALUES (:name, :host, :phone, :api_key)");
		$result = $st->execute(Array(
			"name" => $account->name,
			"phone" => $account->phone,
			"host" => $account->host,
			"api_key" => $account->api_key,
			));
		return true;
	}

	private function _updateAccount(Account $account) {
		if ($this->conn == null) $this->conn = $this->getConnection();
		$st = $this->conn->prepare("UPDATE account SET name=:name, phone=:phone, host=:host, api_key=:api_key WHERE id=:id");
		$result = $st->execute(Array(
			"id" => $account->id,
			"name" => $account->name,
			"phone" => $account->phone,
			"host" => $account->host,
			"api_key" => $account->api_key
			));
		return true;
	}
	
	private function inflateAccount($row){
		$a = new Account();
		$a->id = $row['id'];
		$a->name = $row['name'];
		$a->host = $row['host'];
		$a->phone = $row['phone'];
		$a->api_key = $row['api_key'];
		return $a;
	}
	
	public function loadAccountVendors($account_id){
		if ($this->conn == null) $this->conn = $this->getConnection();
		$st = $this->conn->prepare("SELECT id, account_id, name, phone, email FROM vendor WHERE account_id=:account_id");
		$result = $st->execute(Array("account_id"=> $account_id));
		$rows = $st->fetchAll(PDO::FETCH_ASSOC);
		$result = Array();
		foreach ($rows as $row) {
			$result[] = $this->inflateVendor($row);
		}
		return $result;
	}
	
	public function loadVendor($id) {
		if ($this->conn == null) $this->conn = $this->getConnection();
		$st = $this->conn->prepare("SELECT id, account_id, name, phone, email FROM vendor WHERE id=:id");
		$result = $st->execute(Array("id"=> $id));
		if (!$result) return null;

		$row = $st->fetch(PDO::FETCH_ASSOC);
		if (!$row) return null;

		return $this->inflateVendor($row);
	}
	
	public function deleteVendor($id){
		if ($this->conn == null) $this->conn = $this->getConnection();
		$st = $this->conn->prepare("DELETE FROM vendor WHERE id = :id");
		$result = $st->execute(Array("id" => $id));
		return true;
	}
	
	public function saveVendor(Vendor $vendor){
		if ($vendor->id) {
			return $this->_updateVendor($vendor);
		}
		else {
			return $this->_createVendor($vendor);
		}
	}

	private function _createVendor(Vendor $vendor) {
		if ($this->conn == null) $this->conn = $this->getConnection();
		$st = $this->conn->prepare("INSERT INTO vendor (name, account_id, phone, email) VALUES (:name, :account_id, :phone, :email)");
		$result = $st->execute(Array(
			"name" => $vendor->name,
			"account_id" => $vendor->account_id,
			"phone" => $vendor->phone,
			"email" => $vendor->email
			));
		return true;
	}

	private function _updateVendor(Vendor $vendor) {
		if ($this->conn == null) $this->conn = $this->getConnection();
		$st = $this->conn->prepare("UPDATE vendor SET name=:name, account_id=:account_id, phone=:phone, email=:email WHERE id=:id");
		$result = $st->execute(Array(
			"id" => $vendor->id,
			"name" => $vendor->name,
			"account_id" => $vendor->account_id,
			"phone" => $vendor->phone,
			"email" => $vendor->email,
			));
		return true;
	}
	
	private function inflateVendor($row){
		$v = new Vendor();
		$v->id = $row['id'];
		$v->name = $row['name'];
		$v->account_id = $row['account_id'];
		$v->phone = $row['phone'];
		$v->email = $row['email'];
		return $v;
	}
	
	public function deleteProspect($id){
		if ($this->conn == null) $this->conn = $this->getConnection();
		$st = $this->conn->prepare("DELETE FROM prospect WHERE id = :id");
		$result = $st->execute(Array("id" => $id));
		return true;
	}
	
	public function saveProspect(Prospect $prospect){
		if ($prospect->id) {
			return $this->_updateProspect($prospect);
		}
		else {
			return $this->_createProspect($prospect);
		}
	}

	private function _createProspect(Prospect $prospect) {
		if ($this->conn == null) $this->conn = $this->getConnection();
		$st = $this->conn->prepare("INSERT INTO prospect (name, email, phone, account_id) VALUES (:name, :email, :phone, :account_id)");
		$result = $st->execute(Array(
			"name" => $prospect->name,
			"email" => $prospect->email,
			"phone" => $prospect->phone,
			"account_id" => $prospect->account_id,
			));
		return true;
	}

	private function _updateProspect(Prospect $prospect) {
		if ($this->conn == null) $this->conn = $this->getConnection();
		$st = $this->conn->prepare("UPDATE prospect SET name=:name, email=:email, phone=:phone, account_id=:account_id WHERE id=:id");
		$result = $st->execute(Array(
			"id" => $prospect->id,
			));
		return true;
	}
	
	private function inflateProspect($row){
		$p = new Prospect();
		$p->id = $row['id'];
		$p->name = $row['name'];
		$p->email = $row['email'];
		$p->phone = $row['phone'];
		$p->account_id = $row['account_id'];
		return $p;
	}
	
	public function loadProspect($id) {
		if ($this->conn == null) $this->conn = $this->getConnection();
		$st = $this->conn->prepare("SELECT id, name, email, phone, account_id FROM prospect WHERE id=:id");
		$result = $st->execute(Array("id"=> $id));
		if (!$result) return null;

		$row = $st->fetch(PDO::FETCH_ASSOC);
		if (!$row) return null;

		return $this->inflateProspect($row);
	}
	
	public function loadProspectByPhone($phone, $account_id) {
		if ($this->conn == null) $this->conn = $this->getConnection();
		$st = $this->conn->prepare("SELECT id, name, email, phone, account_id FROM prospect WHERE phone=:phone AND account_id=:account_id");
		$result = $st->execute(Array("phone"=> $phone, "account_id" =>$account_id));
		if (!$result) return null;

		$row = $st->fetch(PDO::FETCH_ASSOC);
		if (!$row) return null;

		return $this->inflateProspect($row);
	}
	
	public function deleteInquiry($id){
		if ($this->conn == null) $this->conn = $this->getConnection();
		$st = $this->conn->prepare("DELETE FROM inquiry WHERE id = :id");
		$result = $st->execute(Array("id" => $id));
		return true;
	}
	
	public function saveInquiry(Inquiry $inquiry){
		if ($inquiry->id) {
			return $this->_updateInquiry($inquiry);
		}
		else {
			return $this->_createInquiry($inquiry);
		}
	}

	private function _createInquiry(Inquiry $inquiry) {
		if ($this->conn == null) $this->conn = $this->getConnection();
		$st = $this->conn->prepare("INSERT INTO inquiry (prospect_id, status, note, inquiry_key) VALUES (:prospect_id, :status, :note, :inquiry_key)");
		$result = $st->execute(Array(
			"prospect_id" => $inquiry->prospect_id,
			"status" => $inquiry->status,
			"note" => $inquiry->note,
			"inquiry_key" => $inquiry->inquiry_key
			));
		return true;
	}

	private function _updateInquiry(Inquiry $id) {
		if ($this->conn == null) $this->conn = $this->getConnection();
		$st = $this->conn->prepare("UPDATE inquiry SET prospect_id, status, note, inquiry_key WHERE id=:id");
		$result = $st->execute(Array(
			"id" => $id,
			));
		return true;
	}
	
	private function inflateInquiry($row){
		$i = new Inquiry();
		$i->id = $row['id'];
		$i->prospect_id = $row['prospect_id'];
		$i->status = $row['status'];
		$i->note = $row['note'];
		$i->inquiry_key = $row['inquiry_key'];
		return $i;
	}
	
	public function loadInquiry($inquiry_key) {
		if ($this->conn == null) $this->conn = $this->getConnection();
		$st = $this->conn->prepare("SELECT id, prospect_id, status, note, inquiry_key FROM inquiry WHERE inquiry_key=:inquiry_key");
		$result = $st->execute(Array("inquiry_key"=> $inquiry_key));
		if (!$result) return null;

		$row = $st->fetch(PDO::FETCH_ASSOC);
		if (!$row) return null;

		return $this->inflateInquiry($row);
	}
	
	public function saveEvent(Event $event){
		if ($event->id) {
			return $this->_updateEvent($event);
		}
		else {
			return $this->_createEvent($event);
		}
	}

	private function _createEvent(Event $event) {
		if ($this->conn == null) $this->conn = $this->getConnection();
		$st = $this->conn->prepare("INSERT INTO event (inquiry_id, vendor_id, timestamp, event) VALUES (:inquiry_id, :vendor_id, :timestamp, :event)");
		$result = $st->execute(Array(
			"vendor_id" => $event->vendor_id,
			"inquiry_id" => $event->inquiry_id,
			"timestamp" => $event->timestamp,
			"event" => $event->event
			));
		return true;
	}

	private function _updateEvent(Event $id) {
		if ($this->conn == null) $this->conn = $this->getConnection();
		$st = $this->conn->prepare("UPDATE event SET inquiry_id, vendor_id, timestamp, event WHERE id=:id");
		$result = $st->execute(Array(
			"id" => $id,
			));
		return true;
	}
	
	public function loadEvents($inquiry_id){
		if ($this->conn == null) $this->conn = $this->getConnection();
		$st = $this->conn->prepare("SELECT id, vendor_id, inquiry_id, timestamp, event FROM event WHERE inquiry_id=:inquiry_id");
		$result = $st->execute(Array("inquiry_id"=> $inquiry_id));
		$rows = $st->fetchAll(PDO::FETCH_ASSOC);
		$result = Array();
		foreach ($rows as $row) {
			$result[] = $this->inflateEvent($row);
		}
		return $result;
	}
	
	private function inflateEvent($row){
		$e = new Event();
		$e->id = $row['id'];
		$e->inquiry_id = $row['inquiry_id'];
		$e->vendor_id = $row['vendor_id'];
		$e->timestamp = $row['timestamp'];
		$e->event = $row['event'];

		return $e;
	}
	
	public function loadProspectInquiry($inquiry_id){
		if ($this->conn == null) $this->conn = $this->getConnection();
		$st = $this->conn->prepare("SELECT * FROM `inquiry` i LEFT JOIN `prospect` p ON i.prospect_id=p.id WHERE i.id=:inquiry_id");
		$result = $st->execute(Array("inquiry_id"=> $inquiry_id));
		if (!$result) return null;

		$row = $st->fetch(PDO::FETCH_ASSOC);
		if (!$row) return null;

		return $this->inflateProspectInquiry($row);
	}
	
		private function inflateProspectInquiry($row){
		$pi = new ProspectInquiry();
		$pi->prospect_id = $row['prospect_id'];
		$pi->status = $row['status'];
		$pi->note = $row['note'];
		$pi->inquiry_key = $row['inquiry_key'];
		$pi->name = $row['name'];
		$pi->email = $row['email'];
		$pi->phone = $row['phone'];
		$pi->account_id = $row['account_id'];

		return $pi;
	}
	
	
}
?>
