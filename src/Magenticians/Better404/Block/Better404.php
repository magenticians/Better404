<?php
namespace Magenticians\Better404\Block;

use Magenticians\Better404\Model\Inspector;
use \Magento\Framework\View\Element;

class Better404 extends Element\Template
{
	protected $_template = '404.phtml';

    /**
     * @var Inspector
     */
    protected $inspector = null;

    /**
     * Constructor
     *
     * @param Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        Element\Template\Context $context,
        Inspector $inspector,
        array $data = []
    ) {
        $this->inspector = $inspector;

        parent::__construct($context, $data);
    }

    public function getClaimedCount()
    {
        return $this->inspector->getClaimedCount();
    }

    public function getClaimedName($index)
    {
        return $this->inspector->getClaimedName($index);
    }

    public function getModuleFrontName()
    {
        return $this->inspector->getParam('moduleFrontName');
    }

    public function getActionPath()
    {
        return $this->inspector->getParam('actionPath');
    }

    public function getActionClassName()
    {
        return $this->inspector->getActionClassName($this->getModuleFrontName(), $this->getActionPath());
    }

    public function getMostLogicalClass()
    {
        return $this->inspector->getMostLogicalClass($this->getActionPath());
    }
}