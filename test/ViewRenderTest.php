<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-mobile-first
 * @copyright Copyright (c) 2019 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace vxm\test\unit\mobileFirst;

use Yii;

/**
 * Class ViewRenderBehaviorTest
 *
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
class ViewRenderTest extends TestCase
{

    public function testDefaultRender()
    {
        $output = Yii::$app->runAction('test/test');

        $this->assertEquals('default', trim($output));
    }

    public function testMobileRender()
    {
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (iPhone; CPU iPhone OS 11_4_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/11.0 Mobile/15E148 Safari/604.1';
        $output = Yii::$app->runAction('test/test');

        $this->assertEquals('mobile', trim($output));
    }

    public function testTabletRender()
    {
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (iPad; CPU OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5355d Safari/8536.25';
        $output = Yii::$app->runAction('test/test');

        $this->assertEquals('tablet', trim($output));
    }

    public function testRestoreRenderer()
    {
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (iPad; CPU OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5355d Safari/8536.25';
        Yii::$app->runAction('test/test');

        $this->assertEquals(TestRenderer::class, get_class(Yii::$app->getView()->renderers['php']));
    }

    public function mockApplication($config = [], $appClass = '\yii\web\Application'): void
    {
        parent::mockApplication($config, $appClass);

        Yii::$app->getBehavior('mobileFirst')->detach();
    }

}
