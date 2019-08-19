<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Domain\User\UserNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;

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
            $this->logger->error("UserNotFoundException launched - Request: `${username}`");

            throw new UserNotFoundException("User of username `${username}` doesn't exists.");
        }

        $this->logger->info("User of username `${username}` was viewed.");

        return $this->respondWithData($user);
    }
}
