<?php

namespace Tests\Unit;

use App\Infrastructure\Repository\Eloquent\EloquentImplementation;
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
        $custom_alias = 'thisIsShortUrlUniqueTest';

        // Success case
        $firstShortUrl = $repository->createShortedUrl($originalUrl, $custom_alias);
        $this->assertNotNull($firstShortUrl);
        $this->assertEquals($custom_alias, $firstShortUrl->custom_alias);

        // Fail case
        $this->expectException(QueryException::class);
        $repository->createShortedUrl('https://url2.com', $custom_alias);
    }

    public function test_valid_url(): void
    {

        $repository = new EloquentImplementation();

        // Valid both URL case
        $validOriginalUrl = 'https://url1.com';
        $validCustomAlias = 'thisIsShortUrlTest';

        $shortUrl = $repository->createShortedUrl($validOriginalUrl, $validCustomAlias);
        $this->assertNotNull($shortUrl);
        $this->assertEquals($validCustomAlias, $shortUrl->custom_alias);

        // Invalid custom alias case (null data)
        $invalidCustomAlias1 = '';
        $this->expectException(QueryException::class);
        $repository->createShortedUrl($validOriginalUrl, $invalidCustomAlias1);

        // Invalid custom alias case (too long 51 chars), max 50
        $invalidCustomAlias2 = 'testLoremipsumdolorsitametconsecteturadipiscingelit';
        $this->expectException(QueryException::class);
        $repository->createShortedUrl($validOriginalUrl, $invalidCustomAlias2);

        // Invalid original URL case (null data)
        $invalidOriginalUrl = '';
        $this->expectException(QueryException::class);
        $repository->createShortedUrl($invalidOriginalUrl, $validCustomAlias);
    }

    /**
     * 'get original url' usecases method test.
     */

    public function test_get_short_url(): void
    {

        $repository = new EloquentImplementation(); // directly using the Eloquent implementation for testing (instance)

        $originalUrl = 'https://mockurl.com';
        $custom_alias = 'thisIsShortUrlGetTest';

        $repository->createShortedUrl($originalUrl, $custom_alias); // seeding

        // Success case
        $queryResult = $repository->getOriginalUrl($custom_alias);
        $this->assertNotNull($queryResult);
        $this->assertEquals($originalUrl, $queryResult->original_url);

        // Not found case
        $notFoundResult = $repository->getOriginalUrl('non-existent-custom-alias');
        $this->assertEquals(null, $notFoundResult);
    }
}
