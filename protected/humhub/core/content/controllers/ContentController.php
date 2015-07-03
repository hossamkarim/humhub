<?php

namespace humhub\core\content\controllers;

use Yii;
use humhub\components\Controller;
use humhub\core\content\models\Content;

/**
 * ContentController is responsible for basic content objects.
 *
 * @package humhub.modules_core.wall.controllers
 * @since 0.5
 * @author Luke
 */
class ContentController extends Controller
{

    /**
     * Deletes a content object
     *
     * Returns a JSON list of affected wallEntryIds.
     */
    public function actionDelete()
    {
        Yii::$app->response->format = 'json';

        $this->forcePostRequest();
        $json = [
            'success' => 'false'
        ];

        $model = Yii::$app->request->get('model');
        $id = (int) Yii::$app->request->get('id');

        $contentObj = Content::get($model, $id);

        if ($contentObj !== null && $contentObj->content->canDelete() && $contentObj->delete()) {
            $json = [
                'success' => true,
                'uniqueId' => $contentObj->getUniqueId(),
                'model' => $model,
                'pk' => $id
            ];
        }

        return $json;
    }

    /**
     * Archives an wall entry & corresponding content object.
     *
     * Returns JSON Output.
     */
    public function actionArchive()
    {
        Yii::$app->response->format = 'json';
        $this->forcePostRequest();

        $json = array();
        $json['success'] = false;

        $id = (int) Yii::$app->request->get('id', "");

        $content = Content::findOne(['id' => $id]);
        if ($content !== null && $content->canArchive()) {
            $content->archive();

            $json['success'] = true;
            $json['wallEntryIds'] = $content->getWallEntryIds();
        }

        return $json;
    }

    /**
     * UnArchives an wall entry & corresponding content object.
     *
     * Returns JSON Output.
     */
    public function actionUnarchive()
    {
        Yii::$app->response->format = 'json';
        $this->forcePostRequest();

        $json = array();
        $json['success'] = false;   // default

        $id = (int) Yii::$app->request->getParam('id', "");

        $content = Content::findOne(['id' => $id]);
        if ($content !== null && $content->canArchive()) {
            $content->unarchive();

            $json['success'] = true;
            $json['wallEntryIds'] = $content->getWallEntryIds();
        }

        return $json;
    }

    /**
     * Sticks an wall entry & corresponding content object.
     *
     * Returns JSON Output.
     */
    public function actionStick()
    {

        $this->forcePostRequest();

        $json = array();
        $json['success'] = false;   // default

        $id = (int) Yii::$app->request->getParam('id', "");
        $className = Yii::$app->request->getParam('className', "");

        $object = Content::Get($className, $id);
        if ($object != null && $object->content->canStick()) {

            if ($object->content->countStickedItems() < 2) {
                $object->content->stick();

                $json['success'] = true;
                $json['wallEntryIds'] = $object->content->getWallEntryIds();
            } else {
                $json['errorMessage'] = Yii::t('ContentModule.controllers_ContentController', "Maximum number of sticked items reached!\n\nYou can stick only two items at once.\nTo however stick this item, unstick another before!");
            }
        } else {
            $json['errorMessage'] = Yii::t('ContentModule.controllers_ContentController', "Could not load requested object!");
        }
        // returns JSON
        echo CJSON::encode($json);
        Yii::$app->end();
    }

    /**
     * Sticks an wall entry & corresponding content object.
     *
     * Returns JSON Output.
     */
    public function actionUnStick()
    {

        $this->forcePostRequest();

        $json = array();
        $json['success'] = false;   // default

        $id = (int) Yii::$app->request->getParam('id', "");
        $className = Yii::$app->request->getParam('className', "");

        $object = Content::Get($className, $id);

        if ($object != null && $object->content->canStick()) {
            $object->content->unstick();

            $json['success'] = true;
            $json['wallEntryIds'] = $object->content->getWallEntryIds();
        }

        // returns JSON
        echo CJSON::encode($json);
        Yii::$app->end();
    }

    public function actionNotificationSwitch()
    {
        $this->forcePostRequest();

        $json = array();
        $json['success'] = false;   // default

        $id = (int) Yii::$app->request->getParam('id', "");
        $className = Yii::$app->request->getParam('className', "");
        $switch = Yii::$app->request->getParam('switch', true);

        $object = Content::Get($className, $id);

        if ($object != null) {
            $object->follow(Yii::$app->user->id, ($switch == 1) ? true : false );
            $json['success'] = true;
        }

        // returns JSON
        echo CJSON::encode($json);
        Yii::$app->end();
    }

}

?>
