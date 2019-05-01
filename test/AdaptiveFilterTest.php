<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-mobile-first
 * @copyright Copyright (c) 2019 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace vxm\test\unit\mobileFirst;

use Yii;

/**
 * Class AdaptiveFilterTest
 *
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
class AdaptiveFilterTest extends TestCase
{

    public function testRedirect()
    {
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (iPhone; CPU iPhone OS 11_4_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/11.0 Mobile/15E148 Safari/604.1';
        Yii::$app->runAction('test/test');

        $this->assertNotNull(Yii::$app->response->headers->get('location', null));
    }

    public function testNotRedirect()
    {
        $_SERVER['HTTP_USER_AGENT'] = null;
        Yii::$app->runAction('test/test');

        $this->assertNull(Yii::$app->response->headers->get('location', null));

        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (iPhone; CPU iPhone OS 11_4_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/11.0 Mobile/15E148 Safari/604.1';
        Yii::$app->request->setHostInfo('http://abc.com');
        Yii::$app->runAction('test/test');

        $this->assertNull(Yii::$app->response->headers->get('location', null));
    }

    public function testRedirectUrlException()
    {
        $this->expectException('\yii\base\InvalidConfigException');
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (iPhone; CPU iPhone OS 11_4_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/11.0 Mobile/15E148 Safari/604.1';
        Yii::$app->getBehavior('mobileFirst')->redirectUrl = null;
        Yii::$app->runAction('test/test');
    }

    public function testRequestMethod()
    {
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (iPhone; CPU iPhone OS 11_4_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/11.0 Mobile/15E148 Safari/604.1';
        $_SERVER['REQUEST_METHOD'] = 'DELETE';
        Yii::$app->runAction('test/test');

        $this->assertNull(Yii::$app->response->headers->get('location', null));

        $_SERVER['REQUEST_METHOD'] = 'GET';
        Yii::$app->runAction('test/test');

        $this->assertNotNull(Yii::$app->response->headers->get('location', null));
    }

}
