<?php

use Phinx\Migration\AbstractMigration;

class PostMigration extends AbstractMigration
{
    public function change()
    {
        //Create posts Table
        $postTable = $this->table('posts');

        $postTable
            ->addColumn('title', 'string', ['limit' => 100])
            ->addColumn('content', 'string', ['limit' => 500])
            ->addColumn('status', 'boolean', ['default' => false])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('published_at', 'timestamp', ['null' => true])
            ->addColumn('username', 'string')
            ->addForeignKey('username', 'users', 'username', ['delete' => 'CASCADE'])
            ->create();
        
        //Create posts_like Table
        $postLikeTable = $this->table('posts_like');

        $postLikeTable
            ->addColumn('is_like', 'boolean')
            ->addColumn('user_username', 'string')
            ->addColumn('post_id', 'integer')
            ->addForeignKey('user_username', 'users', 'username', ['delete' => 'CASCADE'])
            ->addForeignKey('post_id', 'posts', 'id', ['delete' => 'CASCADE'])
            ->create();
        
        //Create Tag Table
        $tagsTable = $this->table('tags');

        $tagsTable
            ->addColumn('name', 'string', ['limit' => 30])
            ->addIndex('name', ['unique' => true])
            ->create();

        $post_tagsTable = $this->table('posts_tags');

        $post_tagsTable
            ->addColumn('post_id', 'integer')
            ->addColumn('tag_id', 'integer')
            ->addForeignKey('post_id', 'posts', 'id', ['delete' => 'CASCADE'])
            ->addForeignKey('tag_id', 'tags', 'id', ['delete' => 'CASCADE'])
            ->create();
    }
}