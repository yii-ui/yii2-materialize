<?php
namespace yiiui\yii2materialize;

use Yii;

trait BackgroundColorTrait
{
    public $backgroundColor = null;

    public $backgroundColorType = null;

    public function initTraitBackgroundColor()
    {
        if ($this->backgroundColor !== null) {
            Html::addCssClass($this->options, ['background-color' => $this->backgroundColor]);
        }

        if ($this->backgroundColorType !== null && !in_array($this->backgroundColor, self::COLOR_SHADES) && ($this->backgroundColor !== Badge::COLOR_MATERIALIZE_RED || strpos($this->backgroundColorType, 'accent-') === false)) {
            Html::addCssClass($this->options, ['background-color-type' => $this->backgroundColorType]);
        }
    }
}
