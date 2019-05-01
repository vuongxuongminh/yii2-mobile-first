<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-mobile-first
 * @copyright Copyright (c) 2019 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace vxm\test\unit\mobileFirst;

use yii\base\ViewRenderer;

class TestRenderer extends ViewRenderer
{

    public function render($view, $file, $params)
    {
        return $view->renderPhpFile($file, $params);
    }

}
