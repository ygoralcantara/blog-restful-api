<?php

namespace App\Application\Actions\User;

use App\Domain\DomainException\DomainRecordNotFoundException;
use App\Domain\User\User;
use App\Domain\User\UserValidator;
use Psr\Http\Message\ResponseInterface as Response;

class UpdateUserAction extends UserAction {

    /**
     * {@inheritDoc}
     */
    public function action() : Response
    {
        /** CHECK IF USER EXISTS */
        $username = (string) $this->resolveArg('username');

        $user = $this->userRepository->findByUsername($username);

        if (!isset($user)) {
            $this->logger->error("User of username `${username}` doesn't exists.");

            throw new DomainRecordNotFoundException("User of username `${username}` doesn't exists.");
        }

        /** VALIDATE PARAMS */
        $params = $this->request->getParsedBody();

        //return $this->respondWithData($params);

        $user->setName((isset($params['name']) ? $params['name'] : ''));
        $user->setEmail((isset($params['email']) ? $params['email'] : ''));
        $user->setPassword((isset($params['password']) ? $params['password'] : ''));

        return $this->respondWithData($user);

        $userValidator = new UserValidator($user);

        $check = $userValidator->validate();

        if (!$check) {
            $messages = $userValidator->getMessagesErrors();

            $this->logger->error("UserValidator launched FALSE");

            return $this->respondWithErrors($messages, 400, 'VALIDATION_ERROR', 'POST Params invalid!');
        }

        /** UPDATE USER */
        $user = $this->userRepository->save($user);

        $this->logger->info("User of username `{$user->getUsername()}` updated with success.");

        return $this->respondWithData($user);
    }

}

?>