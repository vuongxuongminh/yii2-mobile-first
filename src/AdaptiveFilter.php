<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-mobile-first
 * @copyright Copyright (c) 2019 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace vxm\mobileFirst;

use yii\base\ActionFilter;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii\web\Request;
use yii\web\Response;

/**
 * Class AdaptiveBehavior useful to redirect user to adaptive url.
 *
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
class AdaptiveFilter extends ActionFilter
{

    use DetectorTrait;

    /**
     * @var array list of request methods, which should allow page redirection in case wrong protocol is used.
     * For all not listed request methods `BadRequestHttpException` will be thrown for secure action, while
     * not secure ones will be allowed to be performed via secured protocol.
     */
    public $redirectRequestMethods = ['GET', 'OPTIONS'];

    /**
     * @var integer the HTTP status code, which should be used in redirection.
     */
    public $redirectStatusCode = 301;

    /**
     * @var array|string adaptive redirect url it can be an array like ['https://m.yourdomain.com', 'refer' => 'yourdomain.com']
     * or https://m.yourdomain.com/&refer=yourdomain.com
     */
    public $redirectUrl;

    /**
     * @var bool weather you want to keep url requested path.
     */
    public $keepUrlPath = false;

    /**
     * @var array|string|Request a [[Request]] component id or config of it using for prepare redirect url and checking should be redirect an user to adaptive url.
     */
    public $request = 'request';

    /**
     * @var array|string|Response a [[Response]] component id or config of it using for redirect an user.
     */
    public $response = 'response';

    /**
     * @inheritDoc
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        $this->request = Instance::ensure($this->request, Request::class);
        $this->response = Instance::ensure($this->response, Response::class);

        parent::init();
    }

    /**
     * @inheritDoc
     * @throws InvalidConfigException
     */
    public function beforeAction($action)
    {
        if ($this->shouldRedirect()) {

            $this->response->redirect($this->getRedirectUrl(), $this->redirectStatusCode);

            return false;
        } else {

            return true;
        }
    }

    /**
     * Get a adaptive redirect url
     *
     * @return string url
     * @throws InvalidConfigException
     */
    protected function getRedirectUrl(): string
    {
        if ($this->redirectUrl === null) {

            throw new InvalidConfigException('Property `redirectUrl` must be set!');
        } elseif (is_string($this->redirectUrl)) {

            $url = $this->redirectUrl;
            $params = [];
        } else {

            $params = $this->redirectUrl;
            $url = array_unshift($params);
        }

        if ($this->keepUrlPath) {

            $url = rtrim($url, '/') . '/' . $this->request->getUrl();
        }

        $params = http_build_query($params, '', '&');

        if (strpos($url, '?') !== false) {
            $paramSeparator = '&';
        } else {
            $paramSeparator = '?';
        }

        return $url . $paramSeparator . $params;
    }

    /**
     * Method checking should redirect to adaptive url or not.
     *
     * @return boolean whether current web request method is considered as 'read' type.
     * @throws InvalidConfigException
     */
    protected function shouldRedirect(): bool
    {
        $isMobile = $this->getDetector()->isMobile();
        $isRedirectRequestMethod = in_array($this->request->getMethod(), $this->redirectRequestMethods, true);
        $hostName = $this->request->getHostName();
        $redirectHostName = parse_url($this->getRedirectUrl(), PHP_URL_HOST);

        return strcasecmp($hostName, $redirectHostName) !== 0 && $isMobile && $isRedirectRequestMethod;
    }


}
