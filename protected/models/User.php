<?php
class User {
	public function getIp() {
		return $_SERVER["REMOTE_ADDR"];
	}
	
	public function getHost() {
		return gethostbyaddr($_SERVER['REMOTE_ADDR']);
	}
	
	public function isBanned() {
		$isBanned = BanAR::model()->find('ipaddr = :addr', array(':addr' => $this->getIp()));
		if($isBanned == NULL)
			return false;
		
		return true;
	}
}