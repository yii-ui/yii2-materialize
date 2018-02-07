<?php
namespace yiiui\yii2materialize;

use Yii;

trait TextColorTrait
{
    public $textColor = null;

    public $textColorType = null;

    public function initTraitTextColor()
    {
        if ($this->textColor !== null) {
            Html::addCssClass($this->options, ['text-color' => $this->textColor.'-text']);
        }

        if ($this->textColorType !== null && !in_array($this->textColor, self::COLOR_SHADES) && ($this->textColor !== Badge::COLOR_MATERIALIZE_RED || strpos($this->textColorType, 'accent-') === false)) {
            Html::addCssClass($this->options, ['text-color-type' => 'text-'.$this->textColorType]);
        }
    }
}
