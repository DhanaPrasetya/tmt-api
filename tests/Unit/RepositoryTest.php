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
        $validtOriginalUrl = 'https://url1.com';
        $validShortUrl = 'thisIsShortUrlTest';

        $shortUrl = $repository->createShortedUrl($validtOriginalUrl, $validShortUrl);
        $this->assertNotNull($shortUrl);
        $this->assertEquals($validShortUrl, $shortUrl->short_url);

        // Invalid original URL case
        $invalidOriginalUrl = 'not-a-valid-url';
        $this->expectException(QueryException::class);
        $repository->createShortedUrl($invalidOriginalUrl, $validShortUrl);

        // Invalid short URL case (too short)
        $invalidShortUrl1 = 'st';
        $this->expectException(QueryException::class);
        $repository->createShortedUrl($invalidOriginalUrl, $invalidShortUrl1);

        // Invalid short URL case (too long)
        $invalidShortUrl2 = 'LoremipsumdolorsitametconsecteturadipiscingelitseddoeiusmodtemporincididuntutlaboreetdoloremagnaaliquaUtenimadminimveniamquisnostrudexercitationullamcolaborisnisiutaliquip';
        $this->expectException(QueryException::class);
        $repository->createShortedUrl($invalidOriginalUrl, $invalidShortUrl2);
    }

    /**
     * 'create shorted url' usecases method test.
     */
}
