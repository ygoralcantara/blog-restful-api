<?php
declare(strict_types=1);

namespace App\Application\Actions\Tag;

use Psr\Http\Message\ResponseInterface as Response;

class ListTagsAction extends TagAction 
{
    /** 
     * {@inheritDoc}
     */
    protected function action() : Response
    {
        $tags = $this->tagRepository->findAll();

        $this->logger->info("Tags was viewd");

        return $this->respondWithData($tags);
    }
}
?>