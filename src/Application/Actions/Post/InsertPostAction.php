<?php
declare(strict_types=1);

namespace App\Application\Actions\Post;

use App\Domain\Post\Post;
use Psr\Http\Message\ResponseInterface as Response;

class InsertPostAction extends PostAction 
{
    /**
     * {@inheritDoc}
     */
    public function action() : Response
    {
        $input = json_decode($this->request->getBody()->__toString(), true);

        $timestamp = date("Y-m-d H:i:s", time());

        $post = new Post(
            (isset($input['username']) ? $input['username'] : ''),
            (isset($input['title']) ? $input['title'] : ''),
            (isset($input['content']) ? $input['content'] : ''),
            $timestamp
        );

        $post = $this->postService->createPost($post);

        return $this->respondWithData($post);
    }
}

?>