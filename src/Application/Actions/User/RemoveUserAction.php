<?php

namespace App\Application\Actions\User;

use App\Domain\DomainException\DomainRecordNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;

class RemoveUserAction extends UserAction {

    /**
     * {@inheritDoc}
     */
    public function action() : Response
    {
        $username = (string) $this->resolveArg('username');

        $user = $this->userRepository->findByUsername($username);

        if (!isset($user)) {
            $this->logger->error("User of username `{$username}` doesn't exists");

            throw new DomainRecordNotFoundException("User of username `{$username}` doesn't exists");
        }

        $this->userRepository->remove($user);

        $this->logger->info("User of username `${username}` removed with success");

        return $this->respondWithData("User of username `${username}` removed with success");
    }
}

?>