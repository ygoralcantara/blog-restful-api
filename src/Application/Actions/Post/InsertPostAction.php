<?php
declare(strict_types=1);

namespace App\Application\Actions\Post;

use App\Domain\Post\Post;
use Psr\Http\Message\ResponseInterface as Response;

class InsertPostAction extends PostAction 
{
    /**
     * {@inheritDoc}
     */
    public function action() : Response
    {
        $input = json_decode($this->request->getBody()->__toString(), true);
    }
}

?>