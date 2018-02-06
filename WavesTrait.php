<?php
namespace yiiui\yii2materialize;

use Yii;

trait WavesTrait
{
    public $waves = null;

    public $wavesCircle = false;

    public $wavesColor = null;

    public function init()
    {
        parent::init();

        if ($this->waves === null && isset($this->autoWaves)) {
            $this->waves = $this->autoWaves;
        }

        if ($this->waves) {
            Html::addCssClass($this->options, ['waves' => 'waves-effect']);
        }

        if ($this->wavesCircle) {
            Html::addCssClass($this->options, ['waves-circle' => 'waves-circle']);
        }

        if ($this->wavesColor !== null) {
            Html::addCssClass($this->options, ['waves-color' => 'waves-'.$this->wavesColor]);
        }
    }
}
