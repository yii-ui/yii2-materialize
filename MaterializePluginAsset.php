<?php
namespace yiiui\yii2materialize;

use yii\web\AssetBundle;

class MaterializePluginAsset extends AssetBundle
{
    public $sourcePath = '@npm/materialize-css/dist';

    public $js = [
        'js/materialize.min.js'
    ];

    public $depends = [
        'yiiui\yii2materialize\MaterializeAsset'
    ];
}
