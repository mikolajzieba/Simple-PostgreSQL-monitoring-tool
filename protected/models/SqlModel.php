<?php

class SqlModel
{
	private $connection;
	private $credentials;

	public function __construct($dbname, $dblogin, $dbpass) {
		$config = Yii::app()->getComponents(false);
		$this->connection = new CDbConnection($config['dbproduction']['connectionString'].'_'.addslashes($dblogin), addslashes($dblogin), addslashes($dbpass));
		$this->credentials = $dbname.':'.$dblogin;
		$this->connection->setActive(true);
	}

	public function killQuery($pid) {
		$sql = 'SELECT pg_terminate_backend('.addslashes($pid).')';
		$log = new LogAR();
		$log->type = 0;
		$log->credentials = $this->credentials;
		$log->sql = $sql;
		$log->ipaddr = $_SERVER['REMOTE_ADDR'];
		$log->save();

		$res = $this->connection->createCommand($sql)->query();
	}

	public function cancelQuery($pid) {
		$sql = 'SELECT pg_cancel_backend('.addslashes($pid).')';
		$log = new LogAR();
		$log->type = 0;
		$log->credentials = $this->credentials;
		$log->sql = $sql;
		$log->ipaddr = $_SERVER['REMOTE_ADDR'];
		$log->save();

		$res = $this->connection->createCommand($sql)->query();
	}
}