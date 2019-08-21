<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Domain\DomainException\DomainRecordNotFoundException;
use App\Domain\User\UserAlreadyExistsException;
use App\Domain\User\UserNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpForbiddenException;

class ViewUserAction extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $username = (string) $this->resolveArg('username');
        
        $user = $this->userRepository->findByUsername($username);

        if (!isset($user)) {
            $this->logger->error("User of username `${username}` doesn't exists.");

            throw new DomainRecordNotFoundException("User of username `${username}` doesn't exists.");
        }

        $this->logger->info("User of username `${username}` was viewed.");

        return $this->respondWithData($user);
    }
}
