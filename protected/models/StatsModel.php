<?php

class StatsModel {
	const APP_NAME = 'StatsApp';

	public function getProductionStats() {
		$res = Yii::app()->cache->get('stats');
		if(!empty($res)) {
			return $res;
		}

		$res = Yii::app()->dbproduction
			->createCommand("
SELECT
  array(SELECT
  bl.pid as blocked_pid
   FROM pg_catalog.pg_locks bl
     JOIN pg_catalog.pg_stat_activity a
       ON bl.pid = a.procpid
     JOIN pg_catalog.pg_locks kl
       ON bl.locktype = kl.locktype
          and bl.database is not distinct from kl.database
          and bl.relation is not distinct from kl.relation
          and bl.page is not distinct from kl.page
          and bl.tuple is not distinct from kl.tuple
          and bl.virtualxid is not distinct from kl.virtualxid
          and bl.transactionid is not distinct from kl.transactionid
          and bl.classid is not distinct from kl.classid
          and bl.objid is not distinct from kl.objid
          and bl.objsubid is not distinct from kl.objsubid
          and bl.pid <> kl.pid
     JOIN pg_catalog.pg_stat_activity ka
       ON kl.pid = ka.procpid
   WHERE kl.granted and not bl.granted AND kl.pid = psa.procpid
   GROUP BY bl.pid)" .
				" as locks,
  substring(current_query from 0 for 50) as query, now() - query_start as elapsed, datname, usename, substring(application_name from 0 for 15) as application_name, client_addr, backend_start, query_start, waiting, procpid, current_query
FROM pg_stat_activity psa
WHERE
  current_query <> '<IDLE>' AND application_name <> '".self::APP_NAME."'
ORDER BY elapsed DESC
			")
		->queryAll();
		Yii::app()->cache->set('stats', $res, Yii::app()->params->cacheLifetime);
		return $res;
	}
}