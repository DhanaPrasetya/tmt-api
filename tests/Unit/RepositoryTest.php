<?php

namespace Tests\Unit;

use App\Infrastructure\Repository\Eloquent\EloquentImplementation;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;
use Illuminate\Database\QueryException;

use Illuminate\Foundation\Testing\RefreshDatabase;

class RepositoryTest extends TestCase
{

    use RefreshDatabase; // Handle migratiin & transaction rollback automatically

    /**
     * 'create shorted url' usecases method test.
     */

    public function test_unique_short_url(): void
    {

        $repository = new EloquentImplementation(); // directly using the Eloquent implementation for testing (instance)

        $originalUrl = 'https://url1.com';
        $shortUrl = 'thisIsShortUrlUniqueTest';

        // Success case
        $firstShortUrl = $repository->createShortedUrl($originalUrl, $shortUrl);
        $this->assertNotNull($firstShortUrl);
        $this->assertEquals($shortUrl, $firstShortUrl->short_url);

        // Fail case
        $this->expectException(QueryException::class);
        $repository->createShortedUrl('https://url2.com', $shortUrl);
    }

    public function test_valid_url(): void
    {

        $repository = new EloquentImplementation();

        // Valid both URL case
        $validOriginalUrl = 'https://url1.com';
        $validShortUrl = 'thisIsShortUrlTest';

        $shortUrl = $repository->createShortedUrl($validOriginalUrl, $validShortUrl);
        $this->assertNotNull($shortUrl);
        $this->assertEquals($validShortUrl, $shortUrl->short_url);

        // Invalid short URL case (null data)
        $invalidShortUrl1 = '';
        $this->expectException(QueryException::class);
        $repository->createShortedUrl($validOriginalUrl, $invalidShortUrl1);

        // Invalid short URL case (too long 51 chars), max 50
        $invalidShortUrl2 = 'testLoremipsumdolorsitametconsecteturadipiscingelit';
        $this->expectException(QueryException::class);
        $repository->createShortedUrl($validOriginalUrl, $invalidShortUrl2);

        // Invalid original URL case (null data)
        $invalidOriginalUrl = '';
        $this->expectException(QueryException::class);
        $repository->createShortedUrl($invalidOriginalUrl, $validShortUrl);
    }

    /**
     * 'get original url' usecases method test.
     */

    public function test_get_short_url(): void
    {

        $repository = new EloquentImplementation(); // directly using the Eloquent implementation for testing (instance)

        $originalUrl = 'https://mockurl.com';
        $shortUrl = 'thisIsShortUrlGetTest';

        $repository->createShortedUrl($originalUrl, $shortUrl); // seeding

        // Success case
        $queryResult = $repository->getOriginalUrl($shortUrl);
        $this->assertNotNull($queryResult);
        $this->assertEquals($originalUrl, $queryResult->original_url);

        // Not found case
        $notFoundResult = $repository->getOriginalUrl('non-existent-short-url');
        $this->assertEquals(null, $notFoundResult);
    }
}
