<?php
declare(strict_types=1);

namespace App\Application\Actions\Post;

use App\Application\Actions\Action;
use App\Domain\Post\PostRepository;
use Psr\Log\LoggerInterface;

abstract class PostAction extends Action 
{
    /**
     * @var PostRepository
     */
    protected $postRepository;

    /**
     * @param LoggerInterface $logger
     * @param PostRepository  $postRepository
     */
    public function __construct(LoggerInterface $logger, PostRepository $postRepository)
    {
        parent::__construct($logger);
        $this->postRepository = $postRepository;
    }

}

?>