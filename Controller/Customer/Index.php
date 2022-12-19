<?php
/**
 * Aimsinfosoft
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the aimsinfosoft.com license that is
 * available through the world-wide-web at this URL:
 * https://www.aimsinfosoft.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Aimsinfosoft
 * @package     Aimsinfosoft_DeleteAccount
 * @copyright   Copyright (c) Aimsinfosoft (https://www.aimsinfosoft.com)
 * @license     https://www.aimsinfosoft.com/LICENSE.txt
 */
declare(strict_types=1);

namespace Aimsinfosoft\DeleteAccount\Controller\Customer;  

use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session;
use Magento\Framework\Controller\ResultFactory;
use Magento\Store\Model\StoreManagerInterface;

class Index extends \Magento\Framework\App\Action\Action 
{

	/**
	 * @var Session
	 */
	protected $_customerSession;

	/**
	 * @var ResultFactory
	 */
	protected $resultRedirect;

	/**
	 * @var StoreManagerInterface
	 */
	protected $_storeManager;


	/**
	 * @param Context $context
	 * @param Session $customerSession
	 * @param ResultFactory $result
	 * @param StoreManagerInterface $storeManager
	 */
	public function __construct(
		Context $context,
		Session $customerSession,
		ResultFactory $result,
		StoreManagerInterface $storeManager

	) {
		parent::__construct($context);
		$this->_customerSession = $customerSession;
		$this->resultRedirect = $result;
		$this->_storeManager = $storeManager;

	}
	/*
	 * Customer Frontend Delete Account Page
	 */
	public function execute() 
	{ 
		try{
			//Check if Customer is Not logged in, Redirect to Login Page
			if(!$this->_customerSession->isLoggedIn()) {
				$baseUrl = $this->_storeManager->getStore()->getBaseUrl();
				$resultRedirect = $this->resultRedirectFactory->create();
				$resultRedirect->setUrl($baseUrl . 'customer/account/login');
				return $resultRedirect;
            } else {
				$this->_view->loadLayout(); 
				$this->_view->renderLayout(); 
			}
		}
		catch(Exception $e) {
			echo 'Message: ' .$e->getMessage();
	   }	
	} 
} 
?>