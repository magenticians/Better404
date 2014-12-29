<?php
/**
 * Class Inspector
 * A bit of a trick class which extends the Base router to prevent copy/pasting all the protected methods
 * @package Magenticians\Better404\Model
 */

namespace Magenticians\Better404\Model;

use Magento\Core\App\Router\Base;

class Inspector extends Base
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request = null;

    protected $params = [];

    /**
     * Who claims the moduleFrontName currently in use
     * @var array
     */
    protected $claimed = [];

    /**
     * Set up parent class, prepare parsed request $params
     * @param \Magento\Framework\App\Router\ActionList $actionList
     * @param \Magento\Framework\App\ActionFactory $actionFactory
     * @param \Magento\Framework\App\DefaultPathInterface $defaultPath
     * @param \Magento\Framework\App\ResponseFactory $responseFactory
     * @param \Magento\Framework\App\Route\ConfigInterface $routeConfig
     * @param \Magento\Framework\UrlInterface $url
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\Url\SecurityInfoInterface $urlSecurityInfo
     * @param string $routerId
     * @param \Magento\Framework\Code\NameBuilder $nameBuilder
     * @param \Magento\Framework\App\RequestInterface $request
     */
    public function __construct(
        \Magento\Framework\App\Router\ActionList $actionList,
        \Magento\Framework\App\ActionFactory $actionFactory,
        \Magento\Framework\App\DefaultPathInterface $defaultPath,
        \Magento\Framework\App\ResponseFactory $responseFactory,
        \Magento\Framework\App\Route\ConfigInterface $routeConfig,
        \Magento\Framework\UrlInterface $url,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Url\SecurityInfoInterface $urlSecurityInfo,
        $routerId,
        \Magento\Framework\Code\NameBuilder $nameBuilder,
        \Magento\Framework\App\RequestInterface $request
    ) {
        parent::__construct(
            $actionList,
            $actionFactory,
            $defaultPath,
            $responseFactory,
            $routeConfig,
            $url,
            $storeManager,
            $scopeConfig,
            $urlSecurityInfo,
            $routerId,
            $nameBuilder
        );

        $this->request = $request;
        $this->params = $this->parseRequest($request);

        $this->initClaimed();
    }

    /**
     * Prepare the $claimed property with all modules claiming the currently requested moduleFrontName
     */
    protected function initClaimed()
    {
        //$moduleFrontName = $this->matchModuleFrontName($this->request, $this->getParam('moduleFrontName'));
        $this->claimed = $this->_routeConfig->getModulesByFrontName($this->getParam('moduleFrontName'));
    }

    /**
     * Get a param from the parsed request
     * @param $paramName
     * @return string|null
     */
    public function getParam($paramName)
    {
        return isset($this->params[$paramName]) ? $this->params[$paramName] : null;
    }

    /**
     * Get the amount of modules claiming the current moduleFrontName
     * @return int
     */
    public function getClaimedCount()
    {
        return count($this->claimed);
    }

    /**
     * Get the module name from the claimed list at $index
     * @param $index
     * @return string|null
     */
    public function getClaimedName($index)
    {
        return isset($this->claimed[$index]) ? $this->claimed[$index] : null;
    }

    /**
     * Get the most logical class to be resolved for given $actionPath
     * @todo probably wrong
     * @param $actionPath
     * @return string
     */
    public function getMostLogicalClass($actionPath)
    {
        $prefix = $this->pathPrefix ? 'Controller\\' . $this->pathPrefix  : 'Controller';
        return $this->nameBuilder->buildClassName([$this->getClaimedName(0), $prefix, $actionPath]);
    }
}