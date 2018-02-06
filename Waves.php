<?php
namespace yiiui\yii2materialize;

class Waves extends Component
{
    const COLOR_DEFAULT = 'default';
    const COLOR_LIGHT = 'light';
    const COLOR_RED = 'red';
    const COLOR_YELLOW = 'yellow';
    const COLOR_ORANGE = 'orange';
    const COLOR_PURPLE = 'purple';
    const COLOR_GREEN = 'green';
    const COLOR_TEAL = 'teal';

    public $waves = false;

    public $circle  = false;

    public $color = self::COLOR_DEFAULT;

    public function getOptions()
    {
        $options = [];

        if ($this->waves) {
            Html::addCssClass($options, ['waves' => 'waves-effect']);
        }

        if ($this->circle) {
            Html::addCssClass($options, ['waves' => 'waves-circle']);
        }

        if ($this->color !== self::COLOR_DEFAULT) {
            Html::addCssClass($options, ['waves' => 'waves-'.$this->color]);
        }
    }
}
