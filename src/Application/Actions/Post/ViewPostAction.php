<?php
declare(strict_types=1);

namespace App\Application\Actions\Post;

use App\Domain\DomainException\DomainRecordNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;

class ViewPostAction extends PostAction
{
    /** 
     * {@inheritDoc}
     */
    public function action() : Response
    {
        $id = (int) $this->resolveArg('id');

        $post = $this->postService->getPostById($id);

        if (!isset($post)) {
            $this->logger->error("Post of ID `${id}` doesn't exists");

            throw new DomainRecordNotFoundException("Post of ID `${id}` doesn't exists");
        }

        $this->logger->info("Post of ID `${id}` was viewed.");

        return $this->respondWithData($post);
    }
}

?>