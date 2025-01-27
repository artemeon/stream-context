<?php

namespace Artemeon\StreamContext\Context;

enum HttpMethod: string
{
    case GET = 'GET';
    case POST = 'POST';
    case PUT = 'PUT';
}
