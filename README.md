# Yii2 cacheable widget

## Description
A cacheable widget for Yii2 framework. Caches an entire rendered widget as a single cache item. Allows you to configure caching once in a widget class and render it in many views leaving them clean from caching business logic, ensuring DRY and KISS principles. 

## Installation
The widget is [composer](https://getcomposer.org/)-enabled. You can aquire the latest available version from the [packagist repository](https://packagist.org/packages/kolyunya/yii2-cacheable-widget).

## Usage
### Define a custom cacheable widget 
Defining a custom cacheable widget is quite simple. Just extend your widget from `Kolyunya\yii2\widgets\CacheableWidget` and you are good to go. You widget will be cached by a default cache (`cache` application component) for a default duration (for one minute) without any dependencies and without any cache key variations.
```php
class MySimpleCacheableWidget extends CacheableWidget
{
    /**
     *
     * @var Foo
     */
    public $foo;

    /**
     *
     * @var Bar
     */
    public $bar;

    /**
     * @inheritdoc
     */
    public function run()
    {
        // Do some resource-expensive work.
        $this->doSomeExpensiveWork();
        $this->doSomeMoreExpensiveWork();

        // Render some heavy templates.
        return $this->render('my-simple-cacheable-widget',
            'foo' => $this->foo,
            'bar' => $this->bar,
        ]);
    }
}
```

In more complex scenarios you can override some protected methods of the base cacheable widget to configure more complex caching stategies.

```php
class MyCacheableWidget extends CacheableWidget
{
    /**
     *
     * @var Foo
     */
    public $foo;

    /**
     *
     * @var Bar
     */
    public $bar;

    /**
     * @inheritdoc
     */
    public function run()
    {
        // Do some resource-expensive work.
        $this->doSomeExpensiveWork();
        $this->doSomeMoreExpensiveWork();

        // Render some heavy templates.
        return $this->render('my-cacheable-widget',
            'foo' => $this->foo,
            'bar' => $this->bar,
        ]);
    }

    /**
     * @inheritdoc
     */
    protected function getCacheComponent()
    {
        return 'myCustomCache';
    }

    /**
     * @inheritdoc
     */
    protected function getCacheDuration()
    {
        return 60 * 60;
    }

    /**
     * @inheritdoc
     */
    protected function getCacheDependency()
    {
        return new MyCustomCacheDependency();
    }

    /**
     * @inheritdoc
     */
    protected function getCacheKeyVariations()
    {
        return [
            $this->foo,
            $this->bar,
        ];
    }
}
```

### Render it in views
```php
<?= MyCacheableWidget::widget(); ?>
```
