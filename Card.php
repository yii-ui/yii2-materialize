<?php
namespace yiiui\yii2materialize;

use yii\helpers\ArrayHelper;

class Card extends Widget
{

    public $encodeLabel = true;

    public $horizontal = false;

    public $size;

    public $tag;

    public $image;
    public $imageOptions = [];
    public $imageContainerOptions = [];

    public $titleInImage = true;

    public $cardContentTitle;
    public $cardContentTitleOptions = [];

    public $cardRevealTitle;
    public $cardRevealTitleOptions = [];

    public $content;

    public $revealContent;

    public $icon;

    public $fab;
    public $fabOptions;

    public $link = '#';
    public $linkContent;
    public $linkOptions = [];
    public $linkContainerOptions = [];

    public function init()
    {
        parent::init();

        $this->clientOptions = false;

        Html::addCssClass($this->options, ['widget' => 'card']);

        if ($this->horizontal) Html::addCssClass($this->options, 'horizontal');

        if ($this->size) {
            Html::addCssClass($this->options, $this->size);
        }
    }

    public function run()
    {
        $tag = 'div';

        $cardContent = [];

        Html::addCssClass($this->cardContentTitleOptions, 'card-title');
        Html::addCssClass($this->cardRevealTitleOptions, 'card-title');

        Html::addCssClass($this->fabOptions, 'btn-floating halfway-fab');

        $moreVertIcon = '';

        if (array_key_exists('class', $this->cardRevealTitleOptions) && stristr($this->cardContentTitleOptions['class'], 'activator')) {
            $moreVertIcon = '<i class="material-icons right">more_vert</i>';
        }

        Html::addCssClass($this->imageContainerOptions, 'card-image');
        Html::addCssClass($this->linkContainerOptions, 'card-action');
        $this->linkOptions[] = ['href' => $this->link];

        if ($this->image) {
            $cardImageStr = Html::beginTag($tag, $this->imageContainerOptions);
            $cardImageStr .= Html::img($this->image, $this->imageOptions);
            if ($this->titleInImage) $cardImageStr .= Html::tag('span', $this->cardContentTitle, $this->cardContentTitleOptions);

            if ($this->fab) $cardImageStr .= Html::tag('a', $this->fab, $this->fabOptions);

            if ($this->icon) $cardImageStr .= Html::img($this->icon, ['style' => 'position:absolute;width:20%;top:10%;left:75%']);

            $cardImageStr .= Html::endTag($tag);
            $cardContent[] = $cardImageStr;
        }
        if($this->horizontal) $cardContent[] = Html::beginTag($tag, ['class' => 'card-stacked']);

        if ($this->content) {
            $cardContentStr = Html::beginTag($tag, ['class' => 'card-content']);
            if (!$this->titleInImage) $cardContentStr .= Html::tag('span', $this->cardContentTitle . $moreVertIcon, $this->cardContentTitleOptions);
            $cardContentStr .= Html::tag('p', Html::encode($this->content));
            $cardContentStr .= Html::endTag($tag);
            $cardContent[] = $cardContentStr;
        }
        if($this->cardRevealTitle) {
            $cardRevealContentStr = Html::beginTag($tag, ['class' => 'card-reveal']);
            $cardRevealContentStr .= Html::tag('span', $this->cardRevealTitle . Html::tag('i', 'close', ['class' => 'material-icons right']), $this->cardRevealTitleOptions);
            $cardRevealContentStr .= Html::tag('p', $this->revealContent);
            $cardRevealContentStr .= Html::endTag($tag);
            $cardContent[] = $cardRevealContentStr;
        }
        elseif ($this->linkContent) {
            $cardContent[] = Html::tag($tag, Html::tag('a', Html::encode($this->linkContent), $this->linkOptions), $this->linkContainerOptions);
        }

        if($this->horizontal) $cardContent[] = Html::endTag($tag);

        return Html::tag($tag, implode("\n", $cardContent) . "\n", $this->options);
    }
}
