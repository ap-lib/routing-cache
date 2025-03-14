<?php declare(strict_types=1);

namespace AP\Routing\Cache\Tests;

use AP\Routing\Middleware\AfterInterface;
use AP\Routing\Request\Request;
use AP\Routing\Response\Response;
use Attribute;

#[Attribute]
readonly class AfterMiddleware implements AfterInterface
{
    public function __construct(
        private string $if_exist_get_value,
        private string $append_text,
        private bool   $replace,
        private bool   $exit_if_append = false,
    )
    {
    }

    public function after(Request $request, Response &$response): bool
    {
        if (key_exists($this->if_exist_get_value, $request->get)) {
            if ($this->replace) {
                $response = new Response(
                    $response->body . $this->append_text,
                    $response->code
                );
            } else {
                $response->body .= $this->append_text;

            }
            return $this->exit_if_append;
        }
       return false;
    }
}