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

namespace Aimsinfosoft\DeleteAccount\Block;

class deletecustomer extends \Magento\Framework\View\Element\Template
{
	/*
	 * Delete Customer Block Constructor
	 */
	public function __construct(\Magento\Framework\View\Element\Template\Context $context)
	{
		parent::__construct($context);
	}

	/*
	 * Get Page Title to Show on Delete Account Page in Customer Section
	 * @return string
	 */
	public function getPageTitle()
	{
		return __('Delete Customer');
	}

	/*
	 * Get Base URL
	 * @return string
	 */
	public function getBaseUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl();
    }

}