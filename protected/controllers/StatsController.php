<?php

class StatsController extends Controller
{
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		$this->render('index', array(
		));
	}

	public function actionTablestats() {
		$model = new StatsModel();
		$data = $model->getProductionStats();
		$this->renderPartial('tablestats', array(
			'dataProvider' => $data
		));
	}

	public function actionKill() {
		$sqlModel = new SqlModel($_POST['dbname'], $_POST['dblogin'], $_POST['dbpass']);
		$sqlModel->killQuery($_POST['pid']);
	}

	public function actionCancel() {
		$sqlModel = new SqlModel($_POST['dbname'], $_POST['dblogin'], $_POST['dbpass']);
		$sqlModel->cancelQuery($_POST['pid']);
	}
}