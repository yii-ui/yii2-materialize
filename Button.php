<?php
namespace yiiui\yii2materialize;

class Button extends Widget
{
    use WavesTrait;

    public $tagName = 'button';

    public $label = 'Button';

    public $encodeLabel = true;

    public $waves = false;

    public function init()
    {
        parent::init();
        $this->clientOptions = false;
        Html::addCssClass($this->options, ['widget' => 'btn']);
    }

    public function run()
    {
        $this->registerPlugin('button');
        return Html::tag($this->tagName, $this->encodeLabel ? Html::encode($this->label) : $this->label, $this->options);
    }
}
