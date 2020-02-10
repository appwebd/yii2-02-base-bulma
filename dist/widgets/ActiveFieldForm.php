<?php

namespace app\widgets;

use yii\helpers\ArrayHelper;
use yii\widgets\ActiveField;

class ActiveFieldForm extends ActiveField
{

    public $labelOptions = [STR_CLASS =>  LABEL];
    public $errorOptions = [STR_CLASS => 'has-text-danger'];
    public $helpOptions  = [STR_CLASS => '<i class="fas fa-question-circle"></i>'];

    public function init()
    {
        $iconLeft  = ArrayHelper::getValue($this->options, ICON_LEFT ,'');
        $iconRight = ArrayHelper::getValue($this->options, ICON_RIGHT ,'');
        $helpText = ArrayHelper::getValue($this->options, ICON_HELP ,'');
        $hasIconLeft  = ' ';
        $hasIconRight = ' ';

        if (isset($iconLeft[3])) { // String is greather than 3 chars
            $hasIconLeft = ' has-icons-left ';
            $iconLeft = '<span class="icon is-small is-left">'
                    . '<i class="fas '.$iconLeft.'"></i>'
                    . '</span>';
        }
        if (isset($iconRight[3])) {
            $hasIconRight = ' has-icons-right ';
            $iconRight    = '<span class="icon is-small is-right">'
                            . '<i class="fas '.$iconRight.'"></i>'
                            . '</span>';
        }

        if (isset($helpText[2])) {

            $help = '<a class = "  has-tooltip-multiline is-static tooltip is-tooltip-multiline"
                    data-tooltip = "'.$helpText.'">
                        <i class = "fas fa-question-circle"></i>
                    </a>';
            $this->template = '
                    <div class = "field ">
                        {label}
                        <div class = "field has-addons">
                            <div class = "control '.$hasIconLeft .' ' .$hasIconRight .'">
                                {input}
                                '.$iconLeft.' '. $iconRight.'
                            </div>
                            <div class = "control help-tooltip">
                                ' . $help . '
                            </div>
                        </div>
                        {hint}
                        {error}
                        <br>
                    </div>';
        } else {
            $this->template = '
                    <div class="field ">
                        {label}
                        <div class="control '.$hasIconLeft .' ' .$hasIconRight .'">
                        {input}
                            '.$iconLeft.' '. $iconRight.'
                        </div>
                        {hint}
                        {error}
                        <br>
                    </div>
                    ';
        }
        parent::init();
    }
}
