<?php
declare(strict_types=1);

namespace App\Application\Actions\Tag;

use App\Application\Actions\Action;
use App\Domain\Tag\TagRepository;
use Psr\Log\LoggerInterface;

abstract class TagAction extends Action 
{
    /** 
     * @var TagRepository
     */
    protected $tagRepository;

    /**
     * @param LoggerInterface $logger
     * @param TagRepository $tagRepository
     */
    public function __construct(LoggerInterface $logger, TagRepository $tagRepository)
    {
        parent::__construct($logger);
        $this->tagRepository = $tagRepository;
    }    
}


?>