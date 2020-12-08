<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%article_tag}}`.
 */
class m201203_160804_create_article_tag_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%article_tag}}', [
            'id' => $this->primaryKey(),
            'article_id' => $this->integer(),
            'tag_id' => $this->integer(),
        ]);

        // создаёт индекс для article_id
        $this->createIndex(
            'tag_article_article_id',
            'article_tag',
            'article_id'
        );

        // связывает article_id тэга с id статьи с которой он связан
        $this->addForeignKey(
            'tag_article_article_id',
            'article_tag',
            'article_id',
            'article',
            'id',
            'CASCADE'
        );

        // создаёт индекс для tag_id
        $this->createIndex(
            'idx_tag_id',
            'article_tag',
            'tag_id'
        );

        // связывает tag_id статьи с id тэга, который с ней связан
        $this->addForeignKey(
            'fk-tag_id',
            'article_tag',
            'tag_id',
            'tag',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%article_tag}}');
    }
}
