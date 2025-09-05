<?php

declare(strict_types=1);

namespace Ticket\Contracts\Requests;

interface ListInterface
{
    public function getPerPage(): int;

    public function getPage(): int;

}