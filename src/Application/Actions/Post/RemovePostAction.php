<?php
declare(strict_types=1);

namespace App\Application\Actions\Post;

use Psr\Http\Message\ResponseInterface as Response;

class RemovePostAction extends PostAction {

    /** 
     * {@inheritDoc}
     */
    public function action() : Response
    {
        $id = $this->resolveArg('id');

        $this->postService->removePost($id);

        return $this->respondWithData("Post of ID `{$id}` removed with success.");
    }
}

?>