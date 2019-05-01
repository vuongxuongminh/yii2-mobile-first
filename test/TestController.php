<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-mobile-first
 * @copyright Copyright (c) 2019 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace vxm\test\unit\mobileFirst;

use yii\web\Controller;

/**
 * Class TestController
 *
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
class TestController extends Controller
{

    public $enableCsrfValidation = false;

    public function actionTest()
    {
        return $this->renderPartial('test');
    }

    public function getViewPath()
    {
        return __DIR__ . '/views';
    }
}
