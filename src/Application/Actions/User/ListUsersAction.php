<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Domain\User\UserNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpBadRequestException;

class ListUsersAction extends UserAction
{
    private $regex_uri = "/(((sort)=(name|username|email)\.(asc|desc))|((offset|limit)=[0-9]+)|(name|username|email)=[a-zA-z]+)&?/";

    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        /** GET QUERY PARAMS */
        $uri = $this->request->getUri()->getQuery();

        $params = $this->request->getQueryParams();

        /** IF PARAMS IS EMPTY THEN RETURN ALL USERS */
        if (empty($params)) {
            $users = $this->userRepository->findAll();

            $this->logger->info("Users list was viewed");

            return $this->respondWithData($users);
        }

        /** CHECK IF URL IS VALID WITH REGEX*/
        $b = preg_match_all($this->regex_uri, $uri);

        if (sizeof($params) != $b) {
            $this->logger->error("HttpBadRequestException launched");

            throw new HttpBadRequestException($this->request, "Malformed URL.");
        }

        /** SET UP QUERY PARAMS */
        $order = null;

        if (isset($params['sort'])) {
            list($k, $v) = explode('.', $params['sort']);

            $order[$k] = $v;
        }

        $limit = (isset($params['limit'])) ? $params['limit'] : null;

        $offset = (isset($params['offset'])) ? $params['offset'] : null;

        unset($params['sort']);
        unset($params['limit']);
        unset($params['offset']);

        /** GET USERS BY FILTER, SORT AND PAGINATION */
        $users = $this->userRepository->findBy($params, $order, $limit, $offset);

        if (empty($users)) {
            $this->logger->error("UserNotFoundException launched");

            throw new UserNotFoundException("No users found");
        }

        $this->logger->info("Users list with filter, sort and pagination was viewed");

        return $this->respondWithData($users);
    }
}