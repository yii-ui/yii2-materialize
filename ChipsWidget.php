<?php

namespace yiiui\yii2materialize;

use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use yii\helpers\Json;
use yii\widgets\InputWidget;

/**
 * Class ChipsWidget
 * @package fafcms\fafcms\inputs
 */
class ChipsWidget extends InputWidget
{
    /**
     * @var array
     */
    public $clientOptions = [];

    /**
     * @var array
     */
    public $items = [];

    /**
     * @var string
     */
    public $delimiter = ',';

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init() {
        parent::init();

        Html::addCssClass($this->options, 'browser-default');

        if ($this->hasModel()) {
            $this->value = $this->options['value'] ?? Html::getAttributeValue($this->model, $this->attribute);
        }

        if (is_string($this->value)) {
            $this->value = explode($this->delimiter, $this->value);
        }

        $data = $this->value;
        $value = [];

        array_walk($data, static function (&$tag) use (&$value) {
            if (!is_array($tag)) {
                $tag = ['tag' => $tag];
            }

            $value[] = $tag['tag'];
        });

        $this->value = implode($this->delimiter, $value);

        if (empty($this->clientOptions['data'])) {
            $this->clientOptions['data'] = $data;
        }

        if (empty($this->clientOptions['autocompleteOptions']['data'])) {
            $this->clientOptions['autocompleteOptions']['data'] = $this->items;
        }

        if (empty($this->clientOptions['placeholder']) && !empty($this->options['placeholder'])) {
            $this->clientOptions['placeholder'] = ArrayHelper::remove($this->options, 'placeholder');
        }
    }

    /**
     * @return string
     */
    public function run() {
        $this->registerPlugin();

        if ($this->hasModel()) {
            $this->options['value'] = $this->value;

            $input = Html::activeHiddenInput($this->model, $this->attribute, $this->options);
        } else {
            $input = Html::hiddenInput($this->name, $this->value, $this->options);
        }

        return $input.Html::tag('div', '', ['class' => 'chips', 'id' => $this->options['id'].'-chips']);
    }

    protected function registerPlugin()
    {
        $inputId = $this->options['id'];
        $pluginId = $inputId.'-chips';
        $delimiter = $this->delimiter;

        $this->clientOptions['onChipAdd'] = new JsExpression('function (e) {chipFormHelper(e);'.(!empty($this->clientOptions['onChipAdd'])?'('.$this->clientOptions['onChipAdd'].')(e)':'').'}');
        $this->clientOptions['onChipDelete'] = new JsExpression('function (e) {chipFormHelper(e);'.(!empty($this->clientOptions['onChipDelete'])?'('.$this->clientOptions['onChipDelete'].')(e)':'').'}');

        $options = Json::htmlEncode($this->clientOptions);

        $js = <<<JS
(function($){
    var \$plugin = $('#$pluginId')
    var \$input = $('#$inputId')
    
    var chipFormHelper = function () {
        var currentChips = [];
        
        $.each(\$plugin.chips('getData'), function(index, data) {
            currentChips.push(data.tag)
        })
        
        \$input.val(currentChips.join('$delimiter'))
    }
    
    \$plugin.chips($options)
}(jQuery));
JS;

        $this->getView()->registerJs($js);
    }
}
