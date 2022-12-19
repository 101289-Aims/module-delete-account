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

namespace Aimsinfosoft\DeleteAccount\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface;

class RetriveSystemConfig extends AbstractHelper
{
    /**
     * @var EncryptorInterface
     */
    protected $encryptor;

    /**
     * @param Context $context
     * @param EncryptorInterface $encryptor
     */
    public function __construct(
        Context $context,
        EncryptorInterface $encryptor
    )
    {
        parent::__construct($context);
        $this->encryptor = $encryptor;
    }

    /*
     * @return string
     */
    public function getEMailSender($scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT)
    {
        $senderIndent = $this->scopeConfig->getValue('deleteaccount/general/sendername',$scope);

        $selectedIndentUrl = 'trans_email/ident_' . $senderIndent . '/name';

        return $this->scopeConfig->getValue($selectedIndentUrl,\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /*
     * @return string
     */
    public function getEMailId($scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT)
    {
        $emailIndent = $this->scopeConfig->getValue('deleteaccount/general/sendername',$scope);

        $emailIndentUrl = 'trans_email/ident_' . $emailIndent . '/email';

        return $this->scopeConfig->getValue($emailIndentUrl,\Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    } 

    /*
     * @return string
     */
    public function getEmailTemplate($scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT)
    {
        return $this->scopeConfig->getValue(
            'deleteaccount/general/template',
            $scope
        );
    }

}