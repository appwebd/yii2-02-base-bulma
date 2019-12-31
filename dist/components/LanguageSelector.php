<?php
/**
 * Class LanguageSelector
 * PHP version 7.0
 *
 * @category  Components
 * @package   LanguageSelector
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2018-2019 Patricio Rojas
 * @license   BSD 3-clause Clear license
 * @version   GIT: <git_id>
 * @link      https://appwebd.github.io
 * @date      2018-10-19 14:15:15 pm
 */

namespace app\components;

use yii\base\BootstrapInterface;

/**
 * Class LanguageSelector
 *
 * @category  Components
 * @package   LanguageSelector
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2018-2019 Patricio Rojas
 * @license   BSD 3-clause Clear license
 * @version   Release: <release_id>
 * @link      https://appwebd.github.io
 */
class LanguageSelector implements BootstrapInterface
{


    /**
     * Language supported in this web application
     *
     * @var array Languages supported in this web application
     */
    public $supportedLang = ['es', 'en'];

    /**
     * Bootstrap language helpers
     *
     * @param object $app view
     *
     * @return void
     */
    public function bootstrap($app)
    {

        $preflang = $app->request->getPreferredLanguage($this->supportedLang);
        $app->language = $preflang;
    }
}
