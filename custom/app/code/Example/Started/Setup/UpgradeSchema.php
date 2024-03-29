<?php

namespace Example\Started\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.1.2', '<')) {
            $this->createPostTable($setup);
        }

        $setup->endSetup();
    }

    /**
     * @param SchemaSetupInterface $setup
     * @return void
     */
    public function createPostTable(SchemaSetupInterface $setup): void
    {
        $tableName = 'post';

        if ($setup->tableExists($tableName)) {
            echo 'Table exists!';
            return;
        }
        echo 'Creating table.';

        $table = $setup->getConnection()
            ->newTable($tableName)
            ->addColumn(
                'post_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary' => true,
                    'unsigned' => true,
                ],
                'Post ID'
            )
            ->addColumn(
                'name',
                Table::TYPE_TEXT,
                255,
                ['nullable => false'],
                'Post Name'
            )
            ->addColumn(
                'url_key',
                Table::TYPE_TEXT,
                255,
                [],
                'Post URL Key'
            )
            ->addColumn(
                'post_content',
                Table::TYPE_TEXT,
                '64k',
                [],
                'Post Post Content'
            )
            ->addColumn(
                'tags',
                Table::TYPE_TEXT, // 等同于 varchar(255)
                255,
                [],
                'Post Tags'
            )
            ->addColumn(
                'status',
                Table::TYPE_INTEGER,
                1,
                [],
                'Post Status'
            )
            ->addColumn(
                'featured_image',
                Table::TYPE_TEXT, // 等同于 varchar(255)
                255,
                [],
                'Post Featured Image'
            )
            ->addColumn(
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                'Created At'
            )->addColumn(
                'updated_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
                'Updated At')
            ->setComment('Post Table');

        $setup->getConnection()->createTable($table);

        /**
         * 适合做索引的字段特征:
         * 1. 唯一性强，例如UID、Email、phone
         * 2. 字段类型是可控的、较短的，text 类型就明显不行
         * 3. 内容有意义的字段
         */
        $setup->getConnection()->addIndex(
            $tableName,
            $setup->getIdxName(
                $tableName,
                ['name', 'url_key', 'tags'],
                AdapterInterface::INDEX_TYPE_INDEX
            ),
            ['name', 'url_key', 'tags']
        );
    }
}