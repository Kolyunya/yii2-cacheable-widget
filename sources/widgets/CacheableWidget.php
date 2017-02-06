<?php

namespace common\components;

use Yii;
use yii\base\Exception;
use yii\base\Widget;
use yii\caching\Cache;
use yii\caching\Dependency;
use yii\widgets\FragmentCache;

abstract class CacheableWidget extends Widget
{
    /**
     * Widget cache.
     *
     * @var Cache
     */
    private $cache;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->initializeCache();
    }

    /**
     * @inheritdoc
     */
    public function beforeRun()
    {
        parent::beforeRun();

        $config = [
            'view' => $this->view,
            'cache' => $this->cache,
            'id' => $this->getCacheKey(),
            'duration' => $this->getCacheDuration(),
            'dependency' => $this->getCacheDependency(),
        ];

        $fragmentCache = FragmentCache::begin($config);
        if ($fragmentCache->getCachedContent() !== false) {
            FragmentCache::end();
            return false;
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function afterRun($result)
    {
        parent::afterRun($result);

        echo $result;

        FragmentCache::end();
    }

    /**
     * Returns a cache component.
     *
     * @return Cache | string
     */
    protected function getCacheComponent()
    {
        $cacheComponent = 'cache';

        return $cacheComponent;
    }

    /**
     * Returns cache duration in seconds.
     *
     * @return integer
     */
    protected function getCacheDuration()
    {
        $cacheDuration = 60;

        return $cacheDuration;
    }

    /**
     * Returns cache dependency.
     *
     * @return Dependency | null
     */
    protected function getCacheDependency()
    {
        $cacheDependency = null;

        return $cacheDependency;
    }

    /**
     * Returns widget cache.
     *
     * @return string
     */
    protected function getCacheKey()
    {
        $cacheKey = [
            __CLASS__,
        ];

        $cacheKeyVariations = $this->getCacheKeyVariations();
        if (is_array($cacheKeyVariations) === true) {
            foreach ($cacheKeyVariations as $cacheKeyVariation) {
                $cacheKey[] = $cacheKeyVariation;
            }
        }

        return $cacheKey;
    }

    /**
     * Returns an array of cache variations.
     *
     * @return array | null
     */
    protected function getCacheKeyVariations()
    {
        $cacheVariations = null;

        return $cacheVariations;
    }

    /**
     * Initializes widget cache.
     *
     * @return void
     */
    private function initializeCache()
    {
        $cache = $this->getCacheComponent();
        if (is_string($cache) === true) {
            $this->cache = Yii::$app->get($cache);
            if ($this->cache === null) {
                throw new Exception('Invalid cache component.');
            }
        } elseif ($cache instanceof Cache) {
            $this->cache = $cache;
        }
    }
}
