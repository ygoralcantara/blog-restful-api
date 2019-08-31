<?php
declare(strict_types=1);

namespace App\Application\Actions\Post;

use App\Domain\Post\Post;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class UpdatePostAction extends PostAction 
{
    /**
     * {@inheritDoc}
     */
    public function action() : Response
    {
        $id = $this->resolveArg('id');

        $input = json_decode($this->request->getBody()->__toString(), true);

        if (!isset($input['title']) && !isset($input['content'])) {
            throw new HttpBadRequestException($this->request, "Title and Content fields empty");
        }

        $post = $this->postService->changeTitleAndContent($id, $input);

        return $this->respondWithData($post);
    }
}
?>