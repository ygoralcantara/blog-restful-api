<?php
declare(strict_types=1);

namespace App\Application\Actions\Tag;

use App\Domain\DomainException\DomainRecordNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;

class RemoveTagAction extends TagAction 
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

            throw new DomainRecordNotFoundException($message);
        }

        $this->tagRepository->remove($tag);

        return $this->respondWithData("Tag of name `${name}` removed with success");
    }
}
?>