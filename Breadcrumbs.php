<?php
namespace yiiui\yii2materialize;

use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class Breadcrumbs extends \yii\widgets\Breadcrumbs
{
    public $tag = null;
    public $options = [];

    public $itemTemplate = '{link}';
    public $activeItemTemplate = '{link}';

    protected function renderItem($link, $template)
    {
       $encodeLabel = ArrayHelper::remove($link, 'encode', $this->encodeLabels);
       if (array_key_exists('label', $link)) {
           $label = $encodeLabel ? Html::encode($link['label']) : $link['label'];
       } else {
           throw new InvalidConfigException('The "label" element is required for each link.');
       }
       if (isset($link['template'])) {
           $template = $link['template'];
       }

       $options = $link;
       Html::addCssClass($options, ['breadcrumb' => 'breadcrumb']);

       if (isset($link['url'])) {
           $tag = 'a';

           if ($link['url'] !== null) {
               $options['href'] = Url::to($link['url']);
           }
       } else {
           $tag = 'span';
       }

       unset($options['template'], $options['label'], $options['url']);

       $link = Html::tag($tag, $label, $options);

       return strtr($template, ['{link}' => $link]);
    }
}
