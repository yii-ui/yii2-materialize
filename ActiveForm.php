<?php
namespace yiiui\yii2materialize;

class ActiveForm extends \yii\widgets\ActiveForm
{
    public $fieldClass = ActiveField::class;

    public $errorCssClass = 'invalid';
    public $successCssClass = 'valid';
}
