<?php
namespace yiiui\yii2materialize;

use yii\helpers\ArrayHelper;

class Collection extends Widget
{
    const ITEM_TYPE_DEFAULT = 'item';
    const ITEM_TYPE_HEADER = 'header';
    const ITEM_TYPE_AVATAR = 'avatar';

    public $items = [];
    public $itemOptions = [];
    public $encodeLabel = true;

    public $tag;

    private $hasLink;
    private $hasHeader;

    public function init()
    {
        parent::init();

        $this->clientOptions = false;

        Html::addCssClass($this->options, ['widget' => 'collection']);
    }

    public function run()
    {
        $tag = 'ul';

        if ($this->tag !== null) {
            $tag = $this->tag;
        } elseif ($this->hasLink) {
            $tag = 'div';
        }

        $this->prepareItems();

        if ($this->hasHeader) {
            Html::addCssClass($this->options, ['header' => 'with-header']);
        }

        return implode("\n", [
            Html::beginTag($tag, $this->options),
            $this->renderItems(),
            Html::endTag($tag)
        ]) . "\n";
    }

    public function prepareItems()
    {
        foreach ($this->items as $key => &$item) {
            if (!is_array($item)) {
                $item = ['label' => $item];
            }

            $item = array_merge($this->itemOptions, $item);
            $options = ArrayHelper::getValue($item, 'options', []);

            if (!array_key_exists('label', $item)) {
                $item['label'] = $key;
            }

            if (array_key_exists('url', $item)) {
                $this->hasLink = true;
            }

            if (!array_key_exists('type', $item)) {
                $item['type'] = self::ITEM_TYPE_DEFAULT;
            }

            if ($item['type'] === self::ITEM_TYPE_HEADER) {
                $this->hasHeader = true;
            }

            if ($item['type'] === self::ITEM_TYPE_AVATAR) {
                Html::addCssClass($options, [self::ITEM_TYPE_DEFAULT => 'collection-'.self::ITEM_TYPE_DEFAULT, 'avatar' => self::ITEM_TYPE_AVATAR]);
            } else {
                Html::addCssClass($options, [$item['type'] => 'collection-'.$item['type']]);
            }

            if (!array_key_exists('encode', $item)) {
                $item['encode'] = $this->encodeLabel;
            }

            $item['options'] = $options;
        }
    }

    public function renderItems()
    {
        $items = [];

        foreach ($this->items as $key => $item) {
            $items[] = $this->renderItem($item);
        }

        return implode("\n", $items);
    }

    public function renderItem($item = [])
    {
        if (!array_key_exists('tag', $item)) {
            $item['tag'] = 'li';

            if (array_key_exists('url', $item)) {
                $item['tag'] = 'a';
            } elseif ($this->hasLink) {
                $item['tag'] = 'div';
            }
        }

        if ($item['type'] === self::ITEM_TYPE_AVATAR) {
            if (array_key_exists('title', $item)) {
                $title = $item['title'];

                if (!array_key_exists('encodeTitle', $item)) {
                    $item['encodeTitle'] = $this->encodeLabel;
                }

                if ($item['encodeTitle']) {
                    $title = Html::encode($title);
                }

                if (!array_key_exists('titleTag', $item)) {
                    $item['titleTag'] = 'span';

                    if (array_key_exists('titleUrl', $item)) {
                        $item['titleTag'] = 'a';
                    }
                }

                $titleOptions = ArrayHelper::getValue($item, 'titleOptions', []);
                $html[] = Html::tag($item['titleTag'], $title, $titleOptions);
            }

            if (array_key_exists('content', $item)) {
                $content = $item['content'];

                if (!array_key_exists('encodeContent', $item)) {
                    $item['encodeContent'] = $this->encodeLabel;
                }

                if ($item['encodeContent']) {
                    $content = Html::encode($content);
                }

                if (!array_key_exists('contentTag', $item)) {
                    $item['contentTag'] = 'p';

                    if (array_key_exists('contentUrl', $item)) {
                        $item['contentTag'] = 'a';
                    }
                }

                $contentOptions = ArrayHelper::getValue($item, 'contentOptions', []);
                $html[] = Html::tag($item['contentTag'], $content, $contentOptions);
            }

            return Html::tag($item['tag'], implode("\n", $html), $item['options']);
        } else {
            $label = $item['label'];

            if ($item['encode']) {
                $label = Html::encode($label);
            }

            return Html::tag($item['tag'], $label, $item['options']);
        }
    }
}
