<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-mobile-first
 * @copyright Copyright (c) 2019 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace vxm\mobileFirst;

use Yii;

use yii\base\Behavior;
use yii\base\ViewEvent;
use yii\web\View;

/**
 * Class ViewRenderBehavior support rendering view by device type.
 *
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
class ViewRenderBehavior extends Behavior
{
    use DetectorTrait;

    /**
     * @var array have key's device type and value's directory name store all views of this device.
     * @see [[getDir()]]
     */
    public $dirMap = [
        'mobile' => 'mobile',
        'tablet' => 'tablet'
    ];

    /**
     * @inheritDoc
     */
    public function events()
    {
        return [
            View::EVENT_BEFORE_RENDER => 'beforeRender'
        ];
    }

    /**
     * An event handle when rendering view support render by device type.
     *
     * @param ViewEvent $event triggered.
     * @throws \yii\base\InvalidConfigException
     */
    public function beforeRender(ViewEvent $event)
    {
        if ($event->isValid) {
            /** @var View $view */
            $view = $event->sender;
            $ext = pathinfo($event->viewFile, PATHINFO_EXTENSION);
            $renderer = $view->renderers[$ext] ?? null;
            $view->renderers[$ext] = Yii::createObject([
                'class' => ViewRenderer::class,
                'dir' => $this->getDir(),
                'renderer' => $renderer
            ]);
        }
    }

    /**
     * Get dir by requested device.
     *
     * @return string|null dir store all views of device or null if not match all element in [[$dirMap]].
     * @throws \yii\base\InvalidConfigException
     */
    protected function getDir(): ?string
    {
        $detector = $this->getDetector();
        $dirMap = array_change_key_case($this->dirMap);

        foreach ($dirMap as $device => $dir) {

            if ($device === 'mobile') {
                $match = $detector->isMobile() && (!isset($dirMap['tablet']) || !$detector->isTablet());
            } elseif ($device === 'tablet') {
                $match = $detector->isTablet();
            } else {
                $match = $detector->is($device);
            }

            if ($match) {
                return $dir;
            }

        }

        return null;
    }
}
