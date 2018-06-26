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
            'from' => $this->integer()->notNull(),
            'to' => $this->integer()->notNull(),
            'amount' => $this->float(2)->notNull(),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp(),
        ]);

        $this->addForeignKey('from_user_fk', 'transfers',
                            'from', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('to_user_fk', 'transfers',
                            'to', 'users', 'id',
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
