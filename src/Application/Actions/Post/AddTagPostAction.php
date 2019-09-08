<?php
declare(strict_types=1);

namespace App\Application\Actions\Post;

use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class AddTagPostAction extends PostAction 
{
    /**
     * {@inheritDoc}
     */
    public function action() : Response
    {
        $post_id = $this->resolveArg('id');
        
        $input = json_decode($this->request->getBody()->__toString(), true);

        if (!isset($input['tag_name'])) {
            $message = "Param POST `tag_name` invalid";

            $this->logger->error($message);

            throw new HttpBadRequestException($this->request, $message);
        }

        if (!is_string($input['tag_name'])) {
            $message = "Param POST `tag_name` must be string type";

            $this->logger->error($message);

            throw new HttpBadRequestException($this->request, $message);
        }

        $this->postService->addTagToPost($post_id, $input['tag_name']);

        $message = "Tag of name `{$input['tag_name']}` added to Post of ID `${post_id}` with success!";

        return $this->respondWithData($message);
    }
}

?>