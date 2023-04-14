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


use Aimsinfosoft\DeleteAccount\Helper\RetriveSystemConfig;

class Deletecustomer extends \Magento\Framework\View\Element\Template
{
	/**
	 * @var RetriveSystemConfig
	 */
	protected $systemConfigData;

	/*
	 * Delete Customer Block Constructor
	 */
	public function __construct(\Magento\Framework\View\Element\Template\Context $context, RetriveSystemConfig $systemConfigData)
	{
		parent::__construct($context);

		$this->systemConfigData = $systemConfigData;
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

	public function getCustomText()
	{
		return $this->systemConfigData->getCustomText();
	}

	public function getCustomTextRequire()
	{
		return $this->systemConfigData->getCustomTextRequire();
	}
}
