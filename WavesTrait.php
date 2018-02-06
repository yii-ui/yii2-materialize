<?php
namespace yiiui\yii2materialize;

use Yii;

trait WavesTrait
{
    public $wavesOptions = [];

    public function init()
    {
        parent::init();

        $waves = new Waves();
        $options = $waves->getOptions();

        if (isset($options['class'])) {
            Html::addCssClass($this->options, $options['class']);
        }
    }
}
