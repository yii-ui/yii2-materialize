<?php
namespace yiiui\yii2materialize;

use yiiui\yii2materialize\ChipsWidget;
use yiiui\yii2materialize\Html;
use yiiui\yii2materialize\MaterializePluginAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class ActiveField extends \yii\widgets\ActiveField
{
    public $options = ['class' => 'input-field'];
    public $template = "{icon}\n{input}\n{label}\n{hint}\n{error}";
    public $inputOptions = [];
    public $errorOptions = ['class' => 'help-block'];
    public $labelOptions = [];
    public $hintOptions = ['class' => 'hint-block'];
    public $icon;
    public $showCharacterCounter = false;
    /**
     * @var bool if "for" field label attribute should be skipped.
     */
    private $_skipLabelFor = false;

    public function init()
    {
        if ($this->form->enableClientScript === true && $this->form->enableClientValidation === true) {
            Html::addCssClass($this->inputOptions, ['inputValidation' => 'validate']);
        }

        if ($this->showCharacterCounter === true) {
            $this->inputOptions['showCharacterCounter'] = true;
        }
    }

    protected function initAutoComplete(&$options = [])
    {
        $autocomplete = ArrayHelper::getValue($options, 'autocomplete', []);

        // not Materialize autocomplete structure
        if (!is_array($autocomplete) || empty($autocomplete)) {
            return;
        }

        ArrayHelper::remove($options, 'autocomplete');

        $view = $this->form->getView();
        Html::addCssClass($options, ['autocomplete' => 'has-autocomplete']);

        MaterializePluginAsset::register($view);
        $autocompleteData['data'] = $autocomplete;

        $pluginOptions = Json::htmlEncode($autocompleteData);
        $js = "$('input.has-autocomplete').autocomplete($pluginOptions);";

        $view->registerJs($js);
    }

    public function render($content = null)
    {
        if ($content === null) {
            if (!isset($this->parts['{icon}'])) {
                $this->icon();
            }
            if (!isset($this->parts['{input}'])) {
                $this->textInput();
            }
            if (!isset($this->parts['{label}'])) {
                $this->label();
            }
            if (!isset($this->parts['{error}'])) {
                $this->error();
            }
            if (!isset($this->parts['{hint}'])) {
                $this->hint(null);
            }
            $content = strtr($this->template, $this->parts);
        } elseif (!is_string($content)) {
            $content = call_user_func($content, $this);
        }

        return $this->begin() . "\n" . $content . "\n" . $this->end();
    }

    /**
     * Renders an icon.
     * @return ActiveField the field itself.
     * @throws \Exception
     */
    public function icon()
    {
        if ($this->icon === null) {
            $this->parts['{icon}'] = '';
            return $this;
        }

        $iconOptions = ArrayHelper::getValue($this->icon, 'options', []);
        Html::addCssClass($iconOptions, 'material-icons prefix');
        $this->parts['{icon}'] = Html::tag('i', ArrayHelper::getValue($this->icon, 'name', null), $iconOptions);

        return $this;
    }

    /**
     * Renders a checkbox.
     * @param array $options the tag options in terms of name-value pairs. See parent class for more details.
     * @param bool $enclosedByLabel whether to enclose the checkbox within the label. This defaults to `false` as it is
     * Materialize standard to not wrap the checkboxes in labels.
     * @return $this
     */
    public function checkbox($options = [], $enclosedByLabel = false)
    {
        Html::removeCssClass($this->options, 'input-field');
        Html::addCssClass($this->options, 'checkbox-field');

        $this->parts['{input}'] = Html::activeCheckbox($this->model, $this->attribute, $options);
        $this->parts['{label}'] = '';

        if ($this->form->validationStateOn === ActiveForm::VALIDATION_STATE_ON_INPUT) {
            $this->addErrorClassIfNeeded($options);
        }

        $this->addAriaAttributes($options);
        $this->adjustLabelFor($options);

        return $this;
    }

    public function switch($options = [], $offLabel = 'Off', $onLabel = 'On')
    {
        $options['label'] = $offLabel.'<span class="lever"></span>'.$onLabel;
        $options['wrapper-class'] = 'switch';
        $options['active-wrap-label'] = false;
        $options['wrap-label'] = false;

        $this->template = "{icon}\n{label}\n{input}\n{hint}\n{error}";
        $this->parts['{input}'] = Html::activeCheckbox($this->model, $this->attribute, $options);

        $this->label(null, ['class' => 'active']);

        if ($this->form->validationStateOn === ActiveForm::VALIDATION_STATE_ON_INPUT) {
            $this->addErrorClassIfNeeded($options);
        }

        $this->addAriaAttributes($options);
        $this->adjustLabelFor($options);

        return $this;
    }

    public function checkboxList($items, $options = [])
    {
        Html::removeCssClass($this->options, 'input-field');
        Html::addCssClass($this->options, 'checkbox-field');

        $this->template = "{icon}\n{label}\n{input}\n{hint}\n{error}";

        if ($this->form->validationStateOn === ActiveForm::VALIDATION_STATE_ON_INPUT) {
            $this->addErrorClassIfNeeded($options);
        }

        $this->addAriaAttributes($options);
        $this->adjustLabelFor($options);
        $this->_skipLabelFor = true;
        $this->parts['{input}'] = Html::activeCheckboxList($this->model, $this->attribute, $items, $options);

        return $this;
    }

    /**
     * Renders a drop-down list.
     *
     * @param array $items the option data items
     * @param array $options the tag options in terms of name-value pairs.
     *
     * @return $this the field object itself.
     *
     * @see http://www.yiiframework.com/doc-2.0/yii-widgets-activefield.html#dropDownList()-detail
     */
    public function dropDownList($items, $options = [])
    {
        $view = $this->form->view;
        MaterializePluginAsset::register($view);
        $id = $this->getInputId();

        $js = "$('#$id').formSelect();";
        $view->registerJs($js);

        return parent::dropDownList($items, $options);
    }

    public function radio($options = [], $enclosedByLabel = true)
    {
        Html::removeCssClass($this->options, 'input-field');
        Html::addCssClass($this->options, 'radio-field');

        if ($enclosedByLabel) {
            $this->parts['{input}'] = Html::activeRadio($this->model, $this->attribute, $options);
            $this->parts['{label}'] = '';
        } else {
            if (isset($options['label']) && !isset($this->parts['{label}'])) {
                $this->parts['{label}'] = $options['label'];
                if (!empty($options['labelOptions'])) {
                    $this->labelOptions = $options['labelOptions'];
                }
            }
            unset($options['labelOptions']);
            $options['label'] = null;
            $this->parts['{input}'] = Html::activeRadio($this->model, $this->attribute, $options);
        }

        if ($this->form->validationStateOn === ActiveForm::VALIDATION_STATE_ON_INPUT) {
            $this->addErrorClassIfNeeded($options);
        }

        $this->addAriaAttributes($options);
        $this->adjustLabelFor($options);

        return $this;
    }

    public function radioList($items, $options = [])
    {
        Html::removeCssClass($this->options, 'input-field');
        Html::addCssClass($this->options, 'radio-field');
        $this->template = "{icon}\n{label}\n{input}\n{hint}\n{error}";

        if ($this->form->validationStateOn === ActiveForm::VALIDATION_STATE_ON_INPUT) {
            $this->addErrorClassIfNeeded($options);
        }

        $this->addRoleAttributes($options, 'radiogroup');
        $this->addAriaAttributes($options);
        $this->adjustLabelFor($options);
        $this->_skipLabelFor = true;
        $this->parts['{input}'] = Html::activeRadioList($this->model, $this->attribute, $items, $options);

        return $this;
    }

    public function chipsInput($options = [])
    {
        Html::removeCssClass($this->options, 'input-field');
        Html::addCssClass($this->options, 'chips-container');

        $this->template = "{icon}\n{label}\n{input}\n{hint}\n{error}";

        return self::widget(ChipsWidget::class, $options);
    }
    /**
     * Renders a color input.
     * @param array $options the tag options in terms of name-value pairs. These will be rendered as
     * the attributes of the resulting tag. The values will be HTML-encoded using [\yii\helpers\BaseHtml::encode()](http://www.yiiframework.com/doc-2.0/yii-helpers-basehtml.html#encode()-detail).
     *
     * The following special options are recognized:
     *
     * - autocomplete: string|array, see [[initAutoComplete()]] for details.
     * @return ActiveField the field itself.
     */
    public function colorInput($options = [])
    {
        Html::addCssClass($options, ['input' => 'color']);
        $this->initAutoComplete($options);

        return parent::input('color', $options);
    }

    /**
     * Renders a date input.
     * @param array $options the tag options in terms of name-value pairs. These will be rendered as
     * the attributes of the resulting tag. The values will be HTML-encoded using [\yii\helpers\BaseHtml::encode()](http://www.yiiframework.com/doc-2.0/yii-helpers-basehtml.html#encode()-detail).
     *
     * The following special options are recognized:
     *
     * - autocomplete: string|array, see [[initAutoComplete()]] for details.
     * @return ActiveField the field itself.
     */
    public function dateInput($options = [])
    {
        Html::addCssClass($options, ['input' => 'datepicker']);
        $this->initAutoComplete($options);

        return parent::input('text', $options);
    }

    public function emailInput($options = [])
    {
        Html::addCssClass($options, ['input' => 'email']);
        $this->initAutoComplete($options);

        return parent::input('email', $options);
    }

    /**
     * Renders a number input.
     * @param array $options the tag options in terms of name-value pairs. These will be rendered as
     * the attributes of the resulting tag. The values will be HTML-encoded using [\yii\helpers\BaseHtml::encode()](http://www.yiiframework.com/doc-2.0/yii-helpers-basehtml.html#encode()-detail).
     *
     * The following special options are recognized:
     *
     * - autocomplete: string|array, see [[initAutoComplete()]] for details.
     * @return ActiveField the field itself.
     */
    public function numberInput($options = [])
    {
        Html::addCssClass($options, ['input' => 'number']);
        $this->initAutoComplete($options);

        return parent::input('number', $options);
    }

    /**
     * Renders a password input.
     * @param array $options the tag options in terms of name-value pairs. These will be rendered as
     * the attributes of the resulting tag. The values will be HTML-encoded using [\yii\helpers\BaseHtml::encode()](http://www.yiiframework.com/doc-2.0/yii-helpers-basehtml.html#encode()-detail).
     *
     * The following special options are recognized:
     *
     * - `maxlength`: integer|boolean, when `maxlength` is set `true` and the model attribute is validated
     *   by a string validator, the `maxlength` and `length` option both option will take the value of
     *   [\yii\validators\StringValidator::max](http://www.yiiframework.com/doc-2.0/yii-validators-stringvalidator.html#$max-detail).
     * - `showCharacterCounter`: boolean, when this option is set `true` and the `maxlength` option is set accordingly
     *   the Materialize character counter JS plugin is initialized for this field.
     *
     * @return $this the field object itself.
     * @see http://materializecss.com/forms.html#character-counter
     */
    public function passwordInput($options = [])
    {
        $options = array_merge($this->inputOptions, $options);
        $this->adjustLabelFor($options);
        $this->parts['{input}'] = Html::activePasswordInput($this->model, $this->attribute, $options);

        return $this;
    }

    /**
     * Renders a range input.
     * @param array $options the tag options in terms of name-value pairs. These will be rendered as
     * the attributes of the resulting tag. The values will be HTML-encoded using [\yii\helpers\BaseHtml::encode()](http://www.yiiframework.com/doc-2.0/yii-helpers-basehtml.html#encode()-detail).
     *
     * @return ActiveField the field itself.
     */
    public function rangeInput($options = [])
    {
        Html::addCssClass($options, ['input' => 'range']);
        return parent::input('range', $options);
    }

    /**
     * Renders a search input.
     * @param array $options the tag options in terms of name-value pairs. These will be rendered as
     * the attributes of the resulting tag. The values will be HTML-encoded using [\yii\helpers\BaseHtml::encode()](http://www.yiiframework.com/doc-2.0/yii-helpers-basehtml.html#encode()-detail).
     *
     * The following special options are recognized:
     *
     * - autocomplete: string|array, see [[initAutoComplete()]] for details.
     * @return ActiveField the field itself.
     */
    public function searchInput($options = [])
    {
        Html::addCssClass($options, ['input' => 'search']);
        $this->initAutoComplete($options);

        return parent::input('search', $options);
    }

    /**
     * Renders a phone input.
     * @param array $options the tag options in terms of name-value pairs. These will be rendered as
     * the attributes of the resulting tag. The values will be HTML-encoded using [\yii\helpers\BaseHtml::encode()](http://www.yiiframework.com/doc-2.0/yii-helpers-basehtml.html#encode()-detail).
     *
     * The following special options are recognized:
     *
     * - autocomplete: string|array, see [[initAutoComplete()]] for details.
     * @return ActiveField the field itself.
     */
    public function telInput($options = [])
    {
        Html::addCssClass($options, ['input' => 'tel']);
        $this->initAutoComplete($options);

        return parent::input('tel', $options);
    }

    /**
     * Renders a text input.
     * @param array $options the tag options in terms of name-value pairs. These will be rendered as
     * the attributes of the resulting tag. The values will be HTML-encoded using [\yii\helpers\BaseHtml::encode()](http://www.yiiframework.com/doc-2.0/yii-helpers-basehtml.html#encode()-detail).
     *
     * The following special options are recognized:
     *
     * - `maxlength`: integer|boolean, when `maxlength` is set `true` and the model attribute is validated
     *   by a string validator, the `maxlength` and `length` option both option will take the value of
     *   [\yii\validators\StringValidator::max](http://www.yiiframework.com/doc-2.0/yii-validators-stringvalidator.html#$max-detail).
     * - `showCharacterCounter`: boolean, when this option is set `true` and the `maxlength` option is set accordingly
     *   the Materialize character counter JS plugin is initialized for this field.
     * - autocomplete: string|array, see [[initAutoComplete()]] for details
     *
     * @return $this the field object itself.
     * @see http://materializecss.com/forms.html#character-counter
     * @see http://materializecss.com/forms.html#autocomplete
     * @see https://www.w3.org/TR/html5/forms.html#attr-fe-autocomplete
     */
    public function textInput($options = [])
    {
        $this->initAutoComplete($options);
        $options = array_merge($this->inputOptions, $options);
        $this->adjustLabelFor($options);
        $this->parts['{input}'] = Html::activeTextInput($this->model, $this->attribute, $options);

        return $this;
    }

    /**
     * Renders a textarea.
     * @param array $options the tag options in terms of name-value pairs. These will be rendered as
     * the attributes of the resulting tag. The values will be HTML-encoded using [\yii\helpers\BaseHtml::encode()](http://www.yiiframework.com/doc-2.0/yii-helpers-basehtml.html#encode()-detail).
     *
     * The following special options are recognized:
     *
     * - `maxlength`: integer|boolean, when `maxlength` is set `true` and the model attribute is validated
     *   by a string validator, the `maxlength` and `length` option both option will take the value of
     *   [\yii\validators\StringValidator::max](http://www.yiiframework.com/doc-2.0/yii-validators-stringvalidator.html#$max-detail).
     * - `showCharacterCounter`: boolean, when this option is set `true` and the `maxlength` option is set accordingly
     *   the Materialize character counter JS plugin is initialized for this field.
     * - autocomplete: string|array, see [[initAutoComplete()]] for details
     *
     * @return $this the field object itself.
     * @see http://materializecss.com/forms.html#character-counter
     * @see http://materializecss.com/forms.html#autocomplete
     * @see https://www.w3.org/TR/html5/forms.html#attr-fe-autocomplete
     */
    public function textarea($options = [])
    {
        $this->initAutoComplete($options);
        Html::addCssClass($options, ['textarea' => 'materialize-textarea']);
        $options = array_merge($this->inputOptions, $options);
        $this->adjustLabelFor($options);
        $this->parts['{input}'] = Html::activeTextarea($this->model, $this->attribute, $options);

        return $this;
    }

    /**
     * Renders a time input.
     * @param array $options the tag options in terms of name-value pairs. These will be rendered as
     * the attributes of the resulting tag. The values will be HTML-encoded using [\yii\helpers\BaseHtml::encode()](http://www.yiiframework.com/doc-2.0/yii-helpers-basehtml.html#encode()-detail).
     *
     * The following special options are recognized:
     *
     * - autocomplete: string|array, see [[initAutoComplete()]] for details.
     * @return ActiveField the field itself.
     */
    public function timeInput($options = [])
    {
        Html::addCssClass($options, ['input' => 'timepicker']);
        $this->initAutoComplete($options);

        return parent::input('time', $options);
    }

    /**
     * Renders an URL input.
     * @param array $options the tag options in terms of name-value pairs. These will be rendered as
     * the attributes of the resulting tag. The values will be HTML-encoded using [\yii\helpers\BaseHtml::encode()](http://www.yiiframework.com/doc-2.0/yii-helpers-basehtml.html#encode()-detail).
     *
     * The following special options are recognized:
     *
     * - autocomplete: string|array, see [[initAutoComplete()]] for details.
     * @return ActiveField the field itself.
     */
    public function urlInput($options = [])
    {
        Html::addCssClass($options, ['input' => 'url']);
        $this->initAutoComplete($options);

        return parent::input('url', $options);
    }

    /**
     * Renders a week input.
     * @param array $options the tag options in terms of name-value pairs. These will be rendered as
     * the attributes of the resulting tag. The values will be HTML-encoded using [\yii\helpers\BaseHtml::encode()](http://www.yiiframework.com/doc-2.0/yii-helpers-basehtml.html#encode()-detail).
     *
     * The following special options are recognized:
     *
     * - autocomplete: string|array, see [[initAutoComplete()]] for details.
     * @return ActiveField the field itself.
     */
    public function weekInput($options = [])
    {
        Html::addCssClass($options, ['input' => 'week']);
        $this->initAutoComplete($options);

        return parent::input('week', $options);
    }
}
