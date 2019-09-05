<?php
declare(strict_types=1);

namespace App\Application\Actions\Post;

use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class LikedPostAction extends PostAction 
{
    /** 
     * {@inheritDoc}
     */
    public function action() : Response
    {
        $id = $this->resolveArg('id');
        $username = $this->resolveArg('username');

        $input = json_decode($this->request->getBody()->__toString(), true);

        if (!isset($input['like'])) {
            throw new HttpBadRequestException($this->request, "Param like invalid");
        }

        if (!is_bool($input['like'])) {
            throw new HttpBadRequestException($this->request, "Param like have to be bool type");
        }
 
        $this->postService->userLikePost($id, $username, $input['like']);

        $message = ($input['like']) ? "liked" : "disliked";

         return $this->respondWithData("User of username `${username}` ${message} Post of ID ${id} with success!");
    }

}

?>