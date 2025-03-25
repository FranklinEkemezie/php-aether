<?php

namespace PHPAether\Enums;

enum RequestMethod: string
{

    case GET = 'GET';
    case POST = 'POST';
    case PUT = 'PUT';
    case PATCH = 'PATCH';
    case DELETE = 'DELETE';
}