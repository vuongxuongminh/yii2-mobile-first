<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-mobile-first
 * @copyright Copyright (c) 2019 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace vxm\mobileFirst;

use yii\base\ViewRenderer as BaseViewRenderer;
use yii\di\Instance;

/**
 * The renderer support rendering view in sub dir of it.
 *
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
class ViewRenderer extends BaseViewRenderer
{

    /**
     * Origin renderer using to render file if not set [[View::renderPhpFile]] will be use.
     *
     * @var null|ViewRenderer
     */
    public $renderer;

    /**
     * @var string the device dir
     * @see [[getFile()]]
     */
    public $dir;

    /**
     * @inheritDoc
     */
    public function init()
    {
        if ($this->renderer) {

            $this->renderer = Instance::ensure($this->renderer, 'yii\base\ViewRenderer');
        }

        parent::init();
    }

    /**
     * @inheritDoc
     * @throws \Throwable
     */
    public function render($view, $file, $params)
    {
        $file = $this->getFile($file);

        if ($this->renderer) {

            $output = $this->renderer->render($view, $file, $params);
        } else {

            $output = $view->renderPhpFile($file, $params);
        }

        $ext = pathinfo($file, PATHINFO_EXTENSION);

        if ($view->renderers[$ext] === $this) {
            $view->renderers[$ext] = $this->renderer;
        }

        return $output;
    }

    /**
     * Returns the file version of a specified [[$dir]].
     *
     * @param string $baseFile
     * @return string
     */
    protected function getFile(string $baseFile): string
    {
        if ($dir = $this->dir) {

            $basePath = pathinfo($baseFile, PATHINFO_DIRNAME);
            $fileName = pathinfo($baseFile, PATHINFO_BASENAME);
            $desiredFile = $basePath . '/' . $dir . '/' . $fileName;

            return is_file($desiredFile) ? $desiredFile : $baseFile;
        } else {

            return $baseFile;
        }
    }
}
