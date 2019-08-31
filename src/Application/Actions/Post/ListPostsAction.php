<?php
declare(strict_types=1);

namespace App\Application\Actions\Post;

use Psr\Http\Message\ResponseInterface as Response;

class ListPostsAction extends PostAction 
{
    /**
     * {@inheritDoc}
     */
    protected function action() : Response
    {
        $input = $this->request->getQueryParams();

        if (empty($input)) {
            $posts = $this->postRepository->findAll();

            $this->logger->info("Posts list was viewed");

            return $this->respondWithData($posts);
        }

        return $this->respondWithData(null);
    }

}

?>