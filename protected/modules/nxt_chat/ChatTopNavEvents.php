<?php

/**
 * ExTopNavEvents is responsible to handle events defined by autostart.php
 *
 * @author luke
 */
class ChatTopNavEvents
{

    /**
     * On build of the TopMenu
     *
     * @param CEvent $event
     */
    public static function onTopMenuInit($event)
    {
        $event->sender->addItem(array(
            'label' => Yii::t('ChatTopNavModule.base', 'Chat'),
            'url' => Yii::app()->createUrl('/nxt_chat/main/index', array()),
            'icon' => '<i class="fa fa-wechat"></i>',
            'isActive' => (Yii::app()->controller->module && Yii::app()->controller->module->id == 'nxt_chat'),
        ));
    }

}
