<?php
namespace yiiui\yii2materialize;

use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

class Dropdown extends Widget
{
    public $items = [];
    public $encodeLabels = true;
    public $submenuOptions;
    public $dropdownTrigger = true;
    public $dropdownTriggerOptions = [];
    public $dropdownTriggerLabel = '';

    public function init()
    {
        parent::init();

        if ($this->submenuOptions === null) {
            $this->submenuOptions = $this->options;
            unset($this->submenuOptions['id']);
        }

        Html::addCssClass($this->options, ['widget' => 'dropdown-content']);
    }

    public function run()
    {
        $content = '';
        $contentId = $this->id;

        if ($this->dropdownTrigger) {
            $contentId .= 'c';
            $this->dropdownTriggerOptions['id'] = $this->id;
            $this->dropdownTriggerOptions['data-target'] = $contentId;

            $content = Html::a($this->dropdownTriggerLabel, '#', $this->dropdownTriggerOptions);
        }

        $this->registerPlugin('dropdown');

        $this->options['id'] = $contentId;

        return $content.$this->renderItems($this->items, $this->options);
    }


    protected function renderItems($items, $options = [])
    {
        $lines = [];
        foreach ($items as $item) {
            if (isset($item['visible']) && !$item['visible']) {
                continue;
            }
            if (is_string($item)) {
                $lines[] = $item;
                continue;
            }
            if (!array_key_exists('label', $item)) {
                throw new InvalidConfigException("The 'label' option is required.");
            }
            $encodeLabel = isset($item['encode']) ? $item['encode'] : $this->encodeLabels;
            $label = $encodeLabel ? Html::encode($item['label']) : $item['label'];
            $itemOptions = ArrayHelper::getValue($item, 'options', []);
            $linkOptions = ArrayHelper::getValue($item, 'linkOptions', []);
            $linkOptions['tabindex'] = '-1';
            $url = array_key_exists('url', $item) ? $item['url'] : null;
            if (empty($item['items'])) {
                if ($url === null) {
                    $content = $label;
                    Html::addCssClass($itemOptions, ['widget' => 'dropdown-header']);
                } else {
                    $content = Html::a($label, $url, $linkOptions);
                }
            } else {
                $submenuOptions = $this->submenuOptions;
                if (isset($item['submenuOptions'])) {
                    $submenuOptions = array_merge($submenuOptions, $item['submenuOptions']);
                }
                $content = Html::a($label, $url === null ? '#' : $url, $linkOptions)
                    . $this->renderItems($item['items'], $submenuOptions);
                Html::addCssClass($itemOptions, ['widget' => 'dropdown-submenu']);
            }

            $lines[] = Html::tag('li', $content, $itemOptions);
        }

        return Html::tag('ul', implode("\n", $lines), $options);
    }
}
