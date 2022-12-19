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

namespace Aimsinfosoft\DeleteAccount\Setup;

use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{

    public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        if (!$installer->tableExists('aims_deleted_customers')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('aims_deleted_customers')
            )
            ->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity'=>true,
                    'unsigned'=>true,
                    'nullable'=>false,
                    'primary'=>true
                ],
                'ID'
                )
            ->addColumn(
                'name',
                Table::TYPE_TEXT,
                255,
                [
                    'nullable'=>false,
                    'default'=>''
                ],
                'Name'
                )
            ->addColumn(
                'email',
                Table::TYPE_TEXT,
                '2M',
                [
                    'nullbale'=>false,
                    'default'=>''
                ],
                'Email'
                )    
            ->addColumn(
                'group',
                Table::TYPE_TEXT,
                '2M',
                [
                    'nullbale'=>false,
                    'default'=>''
                ],
                'Group'
                )
            ->addColumn(
                'customer_since',
                Table::TYPE_TIMESTAMP,
                null,
                [
                    'nullable' => false, 
                    'default' => ''
                ],
                'Customer Since'
                )
            ->addColumn(
                'website',
                Table::TYPE_TEXT,
                '2M',
                [
                    'nullbale'=>false,
                    'default'=>''
                ],
                'Website'
                )
            ->addColumn(
                'account_created_in',
                Table::TYPE_TEXT,
                '2M',
                [
                    'nullbale'=>false,
                    'default'=>''
                ],
                'Account Created In'
                )
            ->addColumn(
                'deleted_at',
                Table::TYPE_TIMESTAMP,
                null,
                [
                    'nullable' => false, 
                    'default' => Table::TIMESTAMP_INIT
                ],
                'Deleted At'
                )
            ->setOption('charset','utf8');
            $installer->getConnection()->createTable($table);
        }
        $installer->endSetup();
    }
}
?>