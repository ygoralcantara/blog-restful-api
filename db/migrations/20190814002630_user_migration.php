<?php

use Phinx\Migration\AbstractMigration;

class UserMigration extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('users', ['id' => false, 'primary_key' => 'username']);

        $table
            ->addColumn('username', 'string', ['limit' => 20])
            ->addColumn('name', 'string', ['limit' => 100])
            ->addColumn('email', 'string', ['limit' => 50])
            ->addColumn('password', 'string', ['limit' => 64])
            ->addIndex(['username', 'email'], ['unique' => true])
            ->create();
    }
}