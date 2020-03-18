<?php
namespace yiiui\yii2materialize;

use yii\helpers\ArrayHelper;

class Html extends \yii\helpers\Html
{
    protected static function activeBooleanInput($type, $model, $attribute, $options = [])
    {
        $name = isset($options['name']) ? $options['name'] : static::getInputName($model, $attribute);
        $value = static::getAttributeValue($model, $attribute);

        if (!array_key_exists('value', $options)) {
            $options['value'] = '1';
        }
        if (!array_key_exists('uncheck', $options)) {
            $options['uncheck'] = '0';
        } elseif ($options['uncheck'] === false) {
            unset($options['uncheck']);
        }
        if (!array_key_exists('label', $options)) {
            $options['label'] = static::encode($model->getAttributeLabel(static::getAttributeName($attribute)));
        } elseif ($options['label'] === false) {
            unset($options['label']);
        }

        $wrapLabel = ArrayHelper::remove($options, 'active-wrap-label', true);

        if ($wrapLabel) {
            $options['label'] = '<span>'.$options['label'].'</span>';
        }

        $checked = "$value" === "{$options['value']}";

        if (!array_key_exists('id', $options)) {
            $options['id'] = static::getInputId($model, $attribute);
        }

        return static::$type($name, $checked, $options);
    }

    protected static function booleanInput($type, $name, $checked = false, $options = [])
    {
        $options['checked'] = (bool) $checked;
        $value = array_key_exists('value', $options) ? $options['value'] : '1';
        if (isset($options['uncheck'])) {
            // add a hidden field so that if the checkbox is not selected, it still submits a value
            $hiddenOptions = [];
            if (isset($options['form'])) {
                $hiddenOptions['form'] = $options['form'];
            }
            // make sure disabled input is not sending any value
            if (!empty($options['disabled'])) {
                $hiddenOptions['disabled'] = $options['disabled'];
            }
            $hidden = static::hiddenInput($name, $options['uncheck'], $hiddenOptions);
            unset($options['uncheck']);
        } else {
            $hidden = '';
        }

        $label = $options['label']??'';
        $labelOptions = isset($options['labelOptions']) ? $options['labelOptions'] : [];
        unset($options['label'], $options['labelOptions']);

        $wrapLabel = ArrayHelper::remove($options, 'wrap-label', true);

        if ($wrapLabel) {
            $label = '<span>'.$label.'</span>';
        }

        $wrapperClass = ArrayHelper::remove($options, 'wrapper-class', $type.'-wrapper');

        $content = static::label(static::input($type, $name, $value, $options) . ' ' . $label, null, $labelOptions);
        return Html::tag('p', $hidden . $content, ['class' => $wrapperClass]);
    }

    public static function textarea($name, $value = '', $options = [])
    {
        Html::addCssClass($options, ['textarea' => 'materialize-textarea']);
        return parent::textarea($name, $value, $options);
    }
}
