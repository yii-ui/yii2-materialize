<?php
namespace frontend\assets;

use yii\web\AssetBundle;

class MaterializeNoUiSliderAsset extends AssetBundle
{
    public $sourcePath = '@npm/materialize-css/extras/noUiSlider';

    public $css = [
        'nouislider.css'
    ];

    public $js = [
        'nouislider.min.js'
    ];
}
