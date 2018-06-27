<?php

namespace app\controllers;

use app\models\Transfer;
use app\models\TransferForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TransactionsController implements the CRUD actions for Transaction model.
 */
class TransfersController extends Controller
{
    const TYPE_INCOME = 'income';
    const TYPE_OUTCOME = 'outcome';
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'create'],
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    // everything else is denied
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @param string $type
     * @return string
     */
    public function actionIndex($type = self::TYPE_INCOME)
    {
        $searchModel = new Transfer();

        switch ($type) {
            case self::TYPE_INCOME:
                $searchModel->receiver = Yii::$app->getUser()->getId();
                break;
            case self::TYPE_OUTCOME:
                $searchModel->sender = Yii::$app->getUser()->getId();
                break;

        }
        return $this->render('index', [
            'dataProvider' => $searchModel->search(Yii::$app->request->get()),
            'searchModel' => $searchModel,
        ]);
    }


    /**
     * Creates a new Transaction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TransferForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            return $this->redirect(['index', 'type' => self::TYPE_OUTCOME]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
}
