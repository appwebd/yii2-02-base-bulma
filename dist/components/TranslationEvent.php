<?php
/**
 * Event handler for missing translations
 * PHP version 7.0
 *
 * @category  Components
 * @package   TranslationEventHandler
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 (c) Copyright - Web Application development
 * @license   BSD 3-clause Clear license
 * @version   GIT: <git_id>
 * @link      https://appwebd.github.io
 * @date      7/1/18 11:37 AM
 */

namespace app\components;

use yii\i18n\MissingTranslationEvent;

/**
 * Class TranslationEvent
 *
 * @category  Components
 * @package   TranslationEventHandler
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 (c) Copyright - Web Application development
 * @license   BSD 3-clause Clear license
 * @version   Release: <release_id>
 * @link      https://appwebd.github.io
 */
class TranslationEvent
{


    /**
     * Show a message for every word not translated
     *
     * @param MissingTranslationEvent $event Message to search equivalent translated.
     *
     * @return void
     */
    public static function missingTrans(MissingTranslationEvent $event)
    {
        $event->translatedMessage = '@MISSING: FOR LANGUAGE '
            . "{$event->language} {$event->category}, {$event->message} @";
    }
}
