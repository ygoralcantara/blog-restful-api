<?php
declare(strict_types=1);

namespace App\Application\Actions\Post;

use App\Application\Actions\Action;
use App\Domain\Post\PostService;
use Psr\Log\LoggerInterface;

abstract class PostAction extends Action 
{
    /**
     * @var PostService
     */
    protected $postService;

    /**
     * @param LoggerInterface $logger
     * @param PostService $postService
     */
    public function __construct(LoggerInterface $logger, PostService $postService)
    {
        parent::__construct($logger);
        $this->postService = $postService;
    }

}

?>