<?php
namespace yiiui\yii2materialize;

class Badge extends Widget implements ColorInterface
{
    use WavesTrait;
    use BackgroundColorTrait;
    use TextColorTrait;

    /***
    * At the moment Materialize only supports span
    */
    public $tagName = 'span';

    public $label = 'Badge';

    public $encodeLabel = true;

    public $new = false;

    public $caption = null;

    public function init()
    {
        parent::init();

        $this->clientOptions = false;

        if ($this->new) {
            Html::addCssClass($this->options, ['type' => 'new']);
        }

        Html::addCssClass($this->options, ['widget' => 'badge']);

        if ($this->caption !== null) {
            $this->options['data-badge-caption'] = $this->caption;
        }
    }

    public function run()
    {
        $this->registerPlugin('button');
        return Html::tag($this->tagName, $this->encodeLabel ? Html::encode($this->label) : $this->label, $this->options);
    }
}
