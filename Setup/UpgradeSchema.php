<?php

namespace Aimsinfosoft\DeleteAccount\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();
        if (version_compare($context->getVersion(), '1.0.4', '<')) {
            $installer->getConnection()->addColumn(
                $installer->getTable('aims_deleted_customers'),
                'feedback',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => false,
                    'length' => '255',
                    'comment' => 'FeedBack'
                ]
            );
        }
        if (version_compare($context->getVersion(), '1.0.4', '<')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('aims_deleted_customers_feedback'))
                ->addColumn(
                    'id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ],
                    'ID'
                )
                ->addColumn(
                    'customer_id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'nullable' => false,
                        'primary' => false
                    ],
                    'CUSTOMER ID'
                )
                ->addColumn(
                    'feedback',
                    Table::TYPE_TEXT,
                    255,
                    [
                        'nullable' => false,
                        'default' => ''
                    ],
                    'FeedBack'
                );

            $installer->getConnection()->createTable($table);
        }

        $installer->endSetup();
    }
}
