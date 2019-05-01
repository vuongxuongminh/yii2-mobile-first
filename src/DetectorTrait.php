<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-mobile-first
 * @copyright Copyright (c) 2019 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace vxm\mobileFirst;

use Yii;

use Detection\MobileDetect;

/**
 * Provide the device detector for classes needed
 *
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
trait DetectorTrait
{
    /**
     * @var string class of mobile detect use to detect device type.
     * @see [[getDetector()]]
     */
    public $detectorClass = MobileDetect::class;

    /**
     * @var MobileDetect
     */
    private $_detector;

    /**
     * Mobile detector
     *
     * @param bool $force weather you need to force the detector
     * @return object|MobileDetect
     * @throws \yii\base\InvalidConfigException
     */
    protected function getDetector(bool $force = false): MobileDetect
    {
        if ($this->_detector === null || $force) {

            return $this->_detector = Yii::createObject($this->detectorClass);
        } else {

            return $this->_detector;
        }
    }
}
