<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%comment}}`.
 */
class m201203_160719_create_comment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%comment}}', [
            'id' => $this->primaryKey(),
            'text' => $this->string(),
            'user_id' => $this->integer(),
            'article_id' => $this->integer(),
            'status' => $this->integer(),
        ]);

        // создаёт индекс для user_id
        $this->createIndex(
          'idx-post-user_id',
            'comment',
            'user_id'
        );

        // связывает user_id комментария с id его автора
        $this->addForeignKey(
            'fk-post-user_id',
            'comment',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        // создаёт индекс для article_id
        $this->createIndex(
          'idx-article_id',
            'comment',
            'article_id'
        );

        // связывает article_id комментария с id статьи, под которой он оставлен
        $this->addForeignKey(
            'fk-article_id',
            'comment',
            'article_id',
            'article',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%comment}}');
    }
}
