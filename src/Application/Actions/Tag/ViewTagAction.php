<?php
declare(strict_types=1);

namespace App\Application\Actions\Tag;

use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class ViewTagAction extends TagAction 
{
    /** 
     * {@inheritDoc}
     */
    protected function action() : Response
    {
        $name = $this->resolveArg('name');

        $tag = $this->tagRepository->findByName($name);

        if (!isset($tag)) {
            $message = "Tag of name `${name}` doesn't exists";

            $this->logger->error($message);

            throw new HttpBadRequestException($this->request, $message);
        }

        $this->logger->info("Tag of name `${name}` was viewded");

        return $this->respondWithData($tag);
    }
}
?>