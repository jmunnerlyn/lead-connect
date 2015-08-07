<?php
require_once("entity/Account.php");
require_once("entity/Vendor.php");
require_once("entity/Prospect.php");

interface LeadConnectService {
	public function saveAccount(Account $account);
	public function loadAccounts();
	public function loadAccount($id);
	public function loadAccountFromPhone($phone);
	public function loadAccountFromNumberSid($number_sid);
	public function deleteAccount($id);
	
	public function saveVendor(Vendor $vendor);
	public function loadVendor($id);
	public function loadAccountVendors($account_id);
	public function deleteVendor($id);
	
	public function saveProspect(Prospect $prospect);
	public function deleteProspect($id);
	public function loadProspect($id);
	public function loadProspectInquiries($prospect_id);
	public function loadAccountProspects($account_id);
	public function loadProspectByPhone($phone, $account_id);
	
	public function saveInquiry(Inquiry $inquiry);
	public function deleteInquiry($id);
	public function loadInquiry($id);
	
	public function saveEvent(Event $event);
	public function loadEvents($inquiry_id);
	
	public function loadProspectInquiry($inquiry_id);
}

?>
