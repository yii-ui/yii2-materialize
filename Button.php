<?php
namespace yiiui\yii2materialize;

class Button extends Widget
{
    const TYPE_DEFAULT = 'default';
    const TYPE_FLOATING = 'floating';
    const TYPE_FLAT = 'flat';

    const SIZE_DEFAULT = 'btn';
    const SIZE_LARGE = 'btn-large';
    const SIZE_SMALL = 'btn-small';

    use WavesTrait {
        WavesTrait::init as private initWaves;
    }

    public $autoWaves = true;

    public $tagName = 'button';

    public $label = 'Button';

    public $encodeLabel = true;

    public $type = self::TYPE_DEFAULT;

    public $size = self::SIZE_DEFAULT;

    public $disabled = false;

    public function init()
    {
        parent::init();

        $this->clientOptions = false;

        if ($this->type !== self::TYPE_DEFAULT) {
            Html::addCssClass($this->options, ['type' => 'btn-'.$this->type]);
        }

        Html::addCssClass($this->options, ['widget' => $this->size]);

        if ($this->disabled || (isset($this->options['disabled']) && $this->options['disabled'] !== false)) {
            Html::addCssClass($this->options, ['disabled' => 'disabled']);
        }

        $this->initWaves();
    }

    public function run()
    {
        $this->registerPlugin('button');
        return Html::tag($this->tagName, $this->encodeLabel ? Html::encode($this->label) : $this->label, $this->options);
    }
}
