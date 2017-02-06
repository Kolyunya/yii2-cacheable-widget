# Yii2 cacheable widget

## Description
A cacheable widget behaving very much like `yii\filters\PageCache`.

## Usage
### Define a custom cacheable widget 
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
        // Do some heavy stuff.
        $this->doSomeHeavyStuff();
        $this->doSomeOtherHeavyStuff();

        // Render some heavy templates.
        return $this->render('widget',
            'foo' => $this->foo,
            'bar' => $this->bar,
        ]);
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
