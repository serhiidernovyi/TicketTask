<?php

namespace Ticket\Contracts\Requests;

interface ListInterface
{
    public function getPerPage(): int;

    public function getPage(): int;

}