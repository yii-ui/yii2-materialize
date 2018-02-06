<?php
namespace yiiui\yii2materialize;

class Button extends Component
{

    public $waves = false;

    public $circle  = false;

    public $color = 'a';

    public function init()
    {
        parent::init();

        if ($this->waves) {
            Html::addCssClass($this->options, ['waves' => 'waves-effect']);
        }

        if ($this->circle) {
            Html::addCssClass($this->options, ['waves' => 'waves-circle']);
        }

        if ($this->circle) {
            Html::addCssClass($this->options, ['waves' => 'waves-circle']);
        }
    }
}
