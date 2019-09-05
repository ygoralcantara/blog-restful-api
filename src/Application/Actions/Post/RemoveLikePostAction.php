<?php
declare(strict_types=1);

namespace App\Application\Actions\Post;

use Psr\Http\Message\ResponseInterface as Response;

class RemoveLikePostAction extends PostAction 
{
    /** 
     * {@inheritDoc}
     */
    public function action() : Response
    {
        $id = $this->resolveArg('id');
        $username = $this->resolveArg('username');

        $this->postService->userRemoveLikePost($id, $username);

        return $this->respondWithData("User of username `${username}` removed like or dislike from Post of ID `${id}` with success");
    }

}

?>