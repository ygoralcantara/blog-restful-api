<?php
declare(strict_types=1);

namespace App\Application\Actions\Post;

use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class RemoveTagPostAction extends PostAction 
{
    /**
     * {@inheritDoc}
     */
    public function action() : Response
    {
        $post_id = $this->resolveArg('id');
        $tag_name = $this->resolveArg('tagname');

        $this->postService->removeTagToPost($post_id, $tag_name);

        $message = "Tag of name `${tag_name}` removed to Post of ID `${post_id}` with success!";

        return $this->respondWithData($message);
    }
}

?>