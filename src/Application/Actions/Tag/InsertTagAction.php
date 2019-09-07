<?php
declare(strict_types=1);

namespace App\Application\Actions\Tag;

use App\Domain\DomainException\DomainRecordAlreadyExistsException;
use App\Domain\DomainException\DomainRecordValidator;
use App\Domain\Tag\Tag;
use App\Domain\Tag\TagValidator;
use Psr\Http\Message\ResponseInterface as Response;

class InsertTagAction extends TagAction 
{
    /** 
     * {@inheritDoc}
     */
    protected function action() : Response
    {
        $input = json_decode($this->request->getBody()->__toString(), true);

        $tag = new Tag(
            (isset($input['name']) ? $input['name'] : '')
        );

        $tagValidator = new TagValidator($tag);

        $check = $tagValidator->validate();

        if (!$check) {
            $this->logger->error("TagValidator launched FALSE");

            $errors = $tagValidator->getErrors();

            throw new DomainRecordValidator($errors);
        }

        $checkTag = $this->tagRepository->findByName($tag->getName());

        if (isset($checkTag)) {
            $message = "Tag of name `{$tag->getName()}` already exists";

            $this->logger->error($message);

            throw new DomainRecordAlreadyExistsException($message);
        }

        $tag = $this->tagRepository->save($tag);

        return $this->respondWithData($tag);
    }
}
?>