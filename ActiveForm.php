<?php
namespace yiiui\yii2materialize;

class ActiveForm extends \yii\widgets\ActiveForm
{
    public $fieldClass = ActiveField::class;

    public $errorCssClass = 'invalid';
    public $successCssClass = 'valid';
    public $disableSubmitOnEnter = false;

    public function run()
    {
        if (!empty($this->_fields)) {
            throw new InvalidCallException('Each beginField() should have a matching endField() call.');
        }

        $content = ob_get_clean();
        echo Html::beginForm($this->action, $this->method, $this->options);

        if ($this->disableSubmitOnEnter) {
            echo Html::submitInput('', ['style' => 'display:none', 'disabled' => true, 'aria-hidden' => true]);
        }

        echo $content;

        if ($this->enableClientScript) {
            $this->registerClientScript();
        }

        echo Html::endForm();
    }
}
