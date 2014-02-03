<?php
class SqlController extends Controller
{

	public function actionIndex() {

		if(Yii::app()->request->isPostRequest) {
			try {
				$sqlModel = new SqlModel($_POST['dbname'], $_POST['dblogin'], $_POST['dbpass']);
			}
			catch (Exception $e) {
				Yii::app()->user->setFlash('error', $e->getMessage());
				$this->render('index');
				return true;
			}

			Yii::app()->user->setFlash('success', 'Connected successful');
		}
		$this->render('index');
	}

	public function actionExecute() {
	}
}