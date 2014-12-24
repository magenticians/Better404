<?php
namespace Magenticians\Better404\Model;

use Magento\Framework\App\ResponseInterface;

class Observer
{
	private $_response = null;

	public function __construct(ResponseInterface $response)
	{
		$this->_response = $response;
	}

	public function modifyNoRoutePage(\Magento\Framework\Event\Observer $observer)
	{
		if (! $this->isStatusNotFound()) {
			return false;
		}

		/** @var $layout \Magento\Framework\View\LayoutInterface */
		$layout = $observer->getLayout();

		if (! $layout->hasElement('cms_page')) {
			return true;
		}

		$layout->unsetElement('cms_page');
		$layout->addBlock('Magenticians\Better404\Block\Better404', 'better_404', 'content');

	}

	protected function isStatusNotFound()
	{
		return strpos($this->_response->getHeader('Status')['value'], '404') !== false;
	}
}