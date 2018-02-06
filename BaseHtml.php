<?php
namespace yiiui\yii2materialize;

use yii\helpers\ArrayHelper;

class BaseHtml extends \yii\helpers\Html
{
    /*
    public static function icon($name, $options = [])
    {
        $tag = ArrayHelper::remove($options, 'tag', 'span');
        $classPrefix = ArrayHelper::remove($options, 'prefix', 'glyphicon glyphicon-');
        static::addCssClass($options, $classPrefix . $name);
        return static::tag($tag, '', $options);
    }

    public static function staticControl($value, $options = [])
    {
        static::addCssClass($options, 'form-control-static');
        $value = (string) $value;
        if (isset($options['encode'])) {
            $encode = $options['encode'];
            unset($options['encode']);
        } else {
            $encode = true;
        }
        return static::tag('p', $encode ? static::encode($value) : $value, $options);
    }

    public static function activeStaticControl($model, $attribute, $options = [])
    {
        if (isset($options['value'])) {
            $value = $options['value'];
            unset($options['value']);
        } else {
            $value = static::getAttributeValue($model, $attribute);
        }
        return static::staticControl($value, $options);
    }*/
}
