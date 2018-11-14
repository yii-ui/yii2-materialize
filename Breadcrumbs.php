<?php
namespace yiiui\yii2materialize;

use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use Yii;

class Breadcrumbs extends \yii\widgets\Breadcrumbs
{
    public $tag = null;
    public $options = [];

    public $itemTemplate = '{link}';
    public $activeItemTemplate = '{link}';
    public $allowEmpty = true;

    public function run()
    {
        if (!$this->allowEmpty && empty($this->links)) {
            return;
        }
        $links = [];
        if ($this->homeLink === null) {
            $links[] = $this->renderItem([
                'label' => Yii::t('yii', 'Home'),
                'url' => Yii::$app->homeUrl,
            ], $this->itemTemplate);
        } elseif ($this->homeLink !== false) {
            $links[] = $this->renderItem($this->homeLink, $this->itemTemplate);
        }
        foreach ($this->links as $link) {
            if (!is_array($link)) {
                $link = ['label' => $link];
            }
            $links[] = $this->renderItem($link, isset($link['url']) ? $this->itemTemplate : $this->activeItemTemplate);
        }
        echo Html::tag($this->tag, implode('', $links), $this->options);
    }

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
