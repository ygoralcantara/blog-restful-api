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
        $input = json_decode($this->request->getBody(), true);

        $user->setName((isset($input['name']) ? $input['name'] : ''));
        $user->setEmail((isset($input['email']) ? $input['email'] : ''));
        $user->setPassword((isset($input['password']) ? $input['password'] : ''));

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