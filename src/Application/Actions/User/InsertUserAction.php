<?php

namespace App\Application\Actions\User;

use App\Domain\DomainException\DomainRecordAlreadyExistsException;
use App\Domain\User\User;
use App\Domain\User\UserAlreadyExistsException;
use App\Domain\User\UserValidator;
use Psr\Http\Message\ResponseInterface as Response;

class InsertUserAction extends UserAction {
    
    /**
     * {@inheritDoc}
     */
    public function action() : Response
    {
        $params = $this->request->getParsedBody();

        $user = new User(
            (isset($params['username']) ? $params['username'] : ''),
            (isset($params['name']) ? $params['name'] : ''),
            (isset($params['email']) ? $params['email'] : ''),
            (isset($params['password']) ? $params['password'] : ''),
        );

        $userValidator = new UserValidator($user);

        $check = $userValidator->validate();

        if(!$check) {
            $messages = $userValidator->getMessagesErrors();

            $this->logger->error("UserValidator launched FALSE");

            return $this->respondWithErrors($messages, 400, 'VALIDATION_ERROR', 'POST Params invalid!');
        }

        $checkUser = $this->userRepository->findByUsername($user->getUsername());

        if (!empty($checkUser)) {
            $this->logger->error("User of username `{$user->getUsername()}` already exists.");

            throw new DomainRecordAlreadyExistsException($this->request, "User of username `{$user->getUsername()}` already exists.");
        }

        $user = $this->userRepository->save($user);

        $this->logger->info("User of username `{$user->getUsername()}` saved with success.");

        return $this->respondWithData($user);
    }

}

?>