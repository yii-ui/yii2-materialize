<?php
namespace yiiui\yii2materialize;

use yii\web\AssetBundle;

class MaterializeAsset extends AssetBundle
{
    public $sourcePath = '@npm/materialize-css/dist';

    public $css = [
        'css/materialize.min.css'
    ];
}
