# Yii2 Mobile First

[![Latest Stable Version](https://poser.pugx.org/vxm/yii2-mobile-first/v/stable)](https://packagist.org/packages/vxm/yii2-mobile-first)
[![Total Downloads](https://poser.pugx.org/vxm/yii2-mobile-first/downloads)](https://packagist.org/packages/vxm/yii2-mobile-first)
[![Build Status](https://travis-ci.org/vuongxuongminh/yii2-mobile-first.svg?branch=master)](https://travis-ci.org/vuongxuongminh/yii2-mobile-first)
[![Code Coverage](https://scrutinizer-ci.com/g/vuongxuongminh/yii2-mobile-first/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/vuongxuongminh/yii2-mobile-first/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/vuongxuongminh/yii2-mobile-first/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/vuongxuongminh/yii2-mobile-first/?branch=master)
[![Yii2](https://img.shields.io/badge/Powered_by-Yii_Framework-green.svg?style=flat)](http://www.yiiframework.com/)

## About it

An extension provide an easy way to implementing mobile-first principle base on [Mobile Detect](https://github.com/serbanghita/Mobile-Detect) wrapper for Yii2 application.

## Requirements

* [PHP >= 7.1](http://php.net)
* [yiisoft/yii2 >= 2.0.13](https://github.com/yiisoft/yii2)

## Installation

Require Yii2 Mobile First using [Composer](https://getcomposer.org):

```bash
composer require vxm/yii2-mobile-first
```

## Usage

This extension give you two features:

+ **Adaptive Filter** support you redirect user to mobile site when detected user using mobile or tablet.
+ **View Render Behavior** support you rendering view file by device type (mobile, tablet, iOs, android...).

### Adaptive Filter

To use this filter you just add it to an application config file:

```php
[
    'id' => 'test',
    'basePath' => __DIR__,
    'vendorPath' => dirname(__DIR__) . '/vendor',
    'components' => [

    ],
    'as adaptiveFilter' => [
        'class' => 'vxm\mobileFirst\AdaptiveFilter',
        'redirectUrl' => ['https://m.yoursite.com', 'getParam1' => '1']
    ]
]
```

If user go to your site with url: `yoursite.com/product?sort=price` and you want to keep `/product?sort=price` url path 
when redirect user to mobile site: `m.yoursite.com/product?sort=price` you just config:

```php
[
    'id' => 'test',
    'basePath' => __DIR__,
    'vendorPath' => dirname(__DIR__) . '/vendor',
    'components' => [

    ],
    'as adaptiveFilter' => [
        'class' => 'vxm\mobileFirst\AdaptiveFilter',
        'redirectUrl' => ['https://m.yoursite.com', 'getParam1' => '1'],
        'keepUrlPath' => true
    ]
]
```

### View Render Behavior

It is a way to replace a set of views with another by user device without the need of touching the original view rendering code. 
You can use it to systematically change the look and feel of an application depend on user device.
For example, when call `$this->render('about')` in SiteController, 
you will be rendering the view file `@app/views/site/about.php`, if user use mobile device, 
the view file `@app/views/site/mobile/about.php` will be rendered, instead. 

To use it, you need to attach it to the view application component in configure file:

```php
[
    'id' => 'test',
    'basePath' => __DIR__,
    'vendorPath' => dirname(__DIR__) . '/vendor',
    'components' => [
        'view' => [
            'as mobileFirst' => [
                'class' => 'vxm\mobileFirst\ViewRenderBehavior',
                'dirMap' => [
                    'mobile' => 'mobile',
                    'tablet' => 'tablet'
                ]
            ]
        ]
    ]
]
```

The `dirMap` property governs how view files should be replaced by user device. 
It takes an array of key-value pairs, where the keys are the device types and the values are the corresponding view sub-directory. 
The replacement is based on user device: if user device match with any key in the `dirMap` array, a view path will be added with the corresponding sub-directory value. 
Using the above configuration example, when user using `mobile` device because it match the key `mobile`, a view path will be added `mobile` look like `@app/views/site/mobile/about.php`.
Of course you can change the value or add more cases:

```php
[
    'id' => 'test',
    'basePath' => __DIR__,
    'vendorPath' => dirname(__DIR__) . '/vendor',
    'components' => [
        'view' => [
            'as mobileFirst' => [
                'class' => 'vxm\mobileFirst\ViewRenderBehavior',
                'dirMap' => [
                    'mobile' => 'mobile-tablet',
                    'tablet' => 'mobile-tablet',
                    'ios' => 'ios',
                    'android' => 'android'
                ]
            ]
        ]
    ]
]
```

The above configuration if user using mobile or tablet device, the view path will be added `mobile-tablet`.
