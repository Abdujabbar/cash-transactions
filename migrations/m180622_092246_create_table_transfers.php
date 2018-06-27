<?php

use yii\db\Migration;

/**
 * Class m180622_092246_create_table_transactions
 */
class m180622_092246_create_table_transfers extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('transfers', [
            'id' => $this->primaryKey(),
            'sender' => $this->integer()->notNull(),
            'receiver' => $this->integer()->notNull(),
            'amount' => $this->float(2)->notNull(),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp(),
        ]);

        $this->addForeignKey('from_user_fk', 'transfers',
                            'sender', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('to_user_fk', 'transfers',
                            'receiver', 'users', 'id',
                                    'CASCADE', 'CASCADE');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('transfers');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180622_092246_create_table_transactions cannot be reverted.\n";

        return false;
    }
    */
}
