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

use Aimsinfosoft\DeleteAccount\Model\DeletedCustomerFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\UrlInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\Group;
use Magento\Framework\Registry;
use Magento\Customer\Model\Session;

class Confirm extends \Magento\Framework\App\Action\Action
{
	/**
	 * @var DeletedCustomerFactory
	 */
	protected $_deletedCustomer;

	/**
	 * @var ResultFactory
	 */
	protected $resultRedirect;
    
	/**
	 * @var StoreManagerInterface
	 */
	protected $_storeManager;
    
	/**
	 * @var UrlInterface
	 */
	protected $urlInterface;
	
	/**
	 * @var CustomerFactory
	 */
	protected $customerFactory;

	/**
	 * @var Group
	 */	
	protected $_customerGroupCollection;
	
	/**
	 * @var Registry
	 */
	protected $registry;

	/**
	 * @var Session 
	 */
	protected $customerSession;


	/**
     * Delete User Contructors
     * @param Context $context
	 * @param DeletedCustomerFactory $deletedCustomer
     * @param ResultFactory $result
     * @param StoreManagerInterface $storeManager
     * @param UrlInterface $urlInterface
     * @param CustomerFactory $customerFactory
	 * @param Group $customerGroupCollection
	 * @param Session $customerSession
     */
	public function __construct(
		Context $context,
		DeletedCustomerFactory $deletedCustomer,
		ResultFactory $result,
		StoreManagerInterface $storeManager,
		UrlInterface $urlInterface,
		CustomerFactory $customerFactory,
		Group $customerGroupCollection,
		Registry $registry,
		Session $customerSession
	)
	{
		parent::__construct($context);
		$this->resultRedirect = $result;
		$this->_storeManager = $storeManager;
		$this->urlInterface = $urlInterface;
		$this->customerFactory = $customerFactory;
		$this->_deletedCustomer = $deletedCustomer;
		$this->_customerGroupCollection = $customerGroupCollection;
		$this->registry = $registry;
		$this->customerSession = $customerSession;
		$this->redirect = $context->getRedirect();
    }

	/*
	 * Execute Method For Account Deletion Confirmation
	 * Save Customer Data to Custom Table
	 * Logout Customer Current Session
	 * Remove Customer From Customer Entity
	 * 
	 * @return ResultFactory
	 */
    public function execute()
	{   
	    try{
			//Get ID and token from URL
			$customerId = $this->getRequest()->getParam('id', false);
			$token = $this->getRequest()->getParam('token', false);

			//Get data from customer Factory
			$customer = $this->customerFactory->create();
			$customerData = $customer->load($customerId);
			$customerName = $customerData->getData('firstname') . " " . $customerData->getData('lastname');
			$customerEmail = $customerData->getData('email');
			$customerSince = $customerData->getData('created_at');
			$customerWebsiteName = $this->_storeManager->getWebsite()->getName();
			$customerInStoreView = $customerData->getData('created_in');
			$customerToken = $customerData->getData('token');
			$groupCollection = $this->_customerGroupCollection->load($customerData->getData('group_id'));
        	$customerGroup = $groupCollection->getCustomerGroupCode();
			$baseUrl = $this->_storeManager->getStore()->getBaseUrl();
			$resultRedirect = $this->resultRedirectFactory->create();
			$custId = $this->customerSession->getId();

			//match the token for particular customer condition
			if($token === $customerToken){
				$model = $this->_deletedCustomer->create();
				$model->addData([
					"name" => $customerName,
					"email" => $customerEmail,
					"group" => $customerGroup,
					"customer_since" => $customerSince,
					"website" => $customerWebsiteName,
					"account_created_in" => $customerInStoreView
					]);
				$model->save();
				 
				//Logout Customer Session
				if($custId) {
					$this->customerSession->logout()
						->setBeforeAuthUrl($this->redirect->getRefererUrl())
						->setLastCustomerId($custId);
				}

				//Delete Customer From Customer Entity
				$this->registry->register('isSecureArea', true);
				$customer = $this->customerFactory->create()->load($customerId);
				$customer->delete();

				//Redirect User to Login Page With Success Message
				$resultRedirect->setUrl($baseUrl . 'customer/account/login');
				$this->messageManager->addSuccess( __('Your Account Has Been Deleted. Create an Account to Login Again') );
			}else {
				
				//Redirect to Homepage With Error Message
				$resultRedirect->setUrl($baseUrl);
				$this->messageManager->addError( __('Ooops, Something went wrong') );
			}	
			return $resultRedirect;
		}
		catch(Exception $e) {
 			echo 'Message: ' .$e->getMessage();
		}
	}	
}    