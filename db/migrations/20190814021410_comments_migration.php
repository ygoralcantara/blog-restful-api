<?php

use Phinx\Migration\AbstractMigration;

class CommentsMigration extends AbstractMigration
{
    public function change()
    {
        //Create comments Table
        $commentsTable = $this->table('comments');

        $commentsTable
                ->addColumn('message', 'string', ['limit' => 330])
                ->addColumn('published_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
                ->addColumn('parent_id', 'integer')
                ->addColumn('username', 'string')
                ->addColumn('post_id', 'integer')
                ->addForeignKey('username', 'users', 'username', ['delete' => 'CASCADE'])
                ->addForeignKey('post_id', 'posts', 'id', ['delete' => 'CASCADE'])
                ->create();
        
        //Create comments_like Table
        $commentsLikeTable = $this->table('comments_like');

        $commentsLikeTable
                ->addColumn('is_like', 'integer')
                ->addColumn('username', 'string')
                ->addColumn('comment_id', 'integer')
                ->addForeignKey('username', 'users', 'username', ['delete' => 'CASCADE'])
                ->addForeignKey('comment_id', 'comments', 'id', ['delete' => 'CASCADE'])
                ->create();
    }
}
