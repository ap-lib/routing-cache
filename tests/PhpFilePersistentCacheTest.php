<?php declare(strict_types=1);

namespace AP\Routing\Cache\Tests;

use AP\Routing\Cache\PhpFileRoutingCache;
use AP\Routing\Request\Method;
use AP\Routing\Request\Request;
use AP\Routing\Routing\Endpoint;
use AP\Routing\Routing\Routing\Hashmap\Hashmap;
use PHPUnit\Framework\TestCase;

final class PhpFilePersistentCacheTest extends TestCase
{
    public function testBasic(): void
    {
        $filename = "data.php";

        $routing = new Hashmap();
        $index   = $routing->getIndexMaker();
        $index->addEndpoint(Method::GET, "/handler-and-one-middleware", new Endpoint(
            [self::class, "handler"],
            [[self::class, "middleware"]]
        ));

        $index->addEndpoint(Method::GET, "/one-handler", new Endpoint(
            [PhpFilePersistentCacheTest::class, "handler"]
        ));

        $index->addEndpoint(Method::POST, "/one-handler", new Endpoint(
            [PhpFilePersistentCacheTest::class, "handler"]
        ));
        // Set up the cache object (in this case, it's just a filename)
        $cache = new PhpFileRoutingCache($filename);

        // Store the array in the cache
        $cache->set(
            $index,
            [
                "some additional comment, you can add implementation details related with your project here",
                "second line comment",
            ]
        );

        // Retrieve the array from the cache
        $retrieved_array = $cache->get();

        $file_content = file_get_contents($filename);


        $this->assertEquals(
            file_get_contents("expected_file.php"),
            $file_content
        );

        $this->assertEquals(
            $routing->init($index->make())->getRoute(Method::GET, "/handler-and-one-middleware")->endpoint->serialize(),
            (new Hashmap())->init($retrieved_array)->getRoute(Method::GET, "/handler-and-one-middleware")->endpoint->serialize()
        );

        $this->assertEquals(
            $routing->init($index->make())->getRoute(Method::GET, "/one-handler")->endpoint->serialize(),
            (new Hashmap())->init($retrieved_array)->getRoute(Method::GET, "/one-handler")->endpoint->serialize()
        );

        $this->assertEquals(
            $routing->init($index->make())->getRoute(Method::POST, "/one-handler")->endpoint->serialize(),
            (new Hashmap())->init($retrieved_array)->getRoute(Method::POST, "/one-handler")->endpoint->serialize()
        );

        unlink($filename);
    }

    static public function middleware()
    {
        return new AfterMiddleware(
            "some",
            "append",
            false
        );
    }

    static public function handler(Request $request): string
    {
        return "hello world";
    }
}
