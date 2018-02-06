<?php
namespace yiiui\yii2materialize;

class Widget extends \yii\base\Widget
{
    use MaterializeWidgetTrait;

    /**
     * @var array the HTML attributes for the widget container tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = [];
}
