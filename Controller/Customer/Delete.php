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

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\Escaper;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Customer\Model\Session;
use Magento\Store\Model\StoreManagerInterface;
use Aimsinfosoft\DeleteAccount\Helper\RetriveSystemConfig;
use Magento\Framework\Math\Random;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Aimsinfosoft\DeleteAccount\Model\FeedBackFactory;

class Delete extends Action
{

    /**
     * @var ResultFactory
     */
    protected $resultRedirect;

    /**
     * @var StateInterface
     */
    protected $inlineTranslation;

    /**
     * @var Escaper
     */
    protected $escaper;

    /**
     * @var TransportBuilder
     */
    protected $transportBuilder;

    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var RetriveSystemConfig
     */
    protected $systemConfigData;

    /**
     * @var Random
     */
    private $mathRandom;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepositoryInterface;

    /**
     * @var FeedBackFactory
     */
    protected $_feedback;
    /**
     * Delete User Contructors
     * @param Context $context
     * @param ResultFactory $result
     * @param StateInterface $inlineTranslation
     * @param Escaper $escaper
     * @param TransportBuilder $transportBuilder
     * @param Session $customerSession
     * @param StoreManagerInterface $storeManager
     * @param RetriveSystemConfig $systemConfigData
     * @param Random $mathRandom
     * @param CustomerRepositoryInterface $customerRepositoryInterface
     */
    public function __construct(
        Context $context,
        ResultFactory $result,
        StateInterface $inlineTranslation,
        Escaper $escaper,
        TransportBuilder $transportBuilder,
        Session $customerSession,
        StoreManagerInterface $storeManager,
        RetriveSystemConfig $systemConfigData,
        Random $mathRandom,
        CustomerRepositoryInterface $customerRepositoryInterface,
        FeedBackFactory $feedBackFactory
    ) {
        parent::__construct($context);
        $this->resultRedirect = $result;
        $this->inlineTranslation = $inlineTranslation;
        $this->escaper = $escaper;
        $this->transportBuilder = $transportBuilder;
        $this->customerSession = $customerSession;
        $this->_storeManager = $storeManager;
        $this->systemConfigData = $systemConfigData;
        $this->mathRandom = $mathRandom;
        $this->customerRepositoryInterface = $customerRepositoryInterface;
        $this->_feedback = $feedBackFactory;
    }

    /*
     * execute method for delete user controller
     * @return ResultFactory
     */
    public function execute()
    {
        try {
            $resultRedirect = $this->resultRedirect->create(ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setUrl($this->_redirect->getRefererUrl());
            $postData = $this->getRequest()->getParams();
            $customerId = $this->customerSession->getCustomer()->getId();
            $customerName = $this->customerSession->getCustomer()->getName();
            $customerEmail = $this->customerSession->getCustomer()->getEmail();
            $baseUrl = $this->_storeManager->getStore()->getBaseUrl();
            $emailSender = $this->systemConfigData->getEMailSender();
            $emailId = $this->systemConfigData->getEMailId();
            $emailTemplate = $this->systemConfigData->getEmailTemplate();
            $customText = $this->systemConfigData->getCustomText();
            $newCustomerToken = $this->mathRandom->getUniqueHash();
            $customer = $this->customerRepositoryInterface->getById($customerId);
            $customer->setCustomAttribute('token', $newCustomerToken);
            $this->customerRepositoryInterface->save($customer);
            $this->customerSession->setFeedBack($postData['feedback']);
            //Send Email To Customer
            $this->inlineTranslation->suspend();
            $feedback_id = '';
            if ($postData['feedback']) {
                $feedback = $this->_feedback->create();
                $feedback->addData([
                    "customer_id" => $this->customerSession->getCustomer()->getId(),
                    "feedback" => $postData['feedback']
                ]);
                $feedback->save();
                $feedback_id = $feedback->getId();
            }
            $templateVars = array(
                'customer_name' => $customerName,
                'confirm_url'   => $baseUrl . 'deleteaccount/Customer/Confirm?id=' . $customerId . '&&token=' . $newCustomerToken . '&&feedback_id=' . $feedback_id
            );

            $sender = [
                'name' => $this->escaper->escapeHtml($emailSender),
                'email' => $this->escaper->escapeHtml($emailId),
            ];
            $transport = $this->transportBuilder
                ->setTemplateIdentifier($emailTemplate)
                ->setTemplateOptions(
                    [
                        'area' => \Magento\Framework\App\Area::AREA_ADMINHTML,
                        'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                    ]
                )
                ->setTemplateVars($templateVars)
                ->setFrom($sender)
                ->addTo($customerEmail)
                ->getTransport();
            $transport->sendMessage();
            $this->inlineTranslation->resume();
            $this->messageManager->addSuccess(__('Please Check Your Registered Email id to Confirm and Delete Your Account Parmanently'));
            return $resultRedirect;
        } catch (Exception $e) {
            echo 'Message: ' . $e->getMessage();
        }
    }
}
