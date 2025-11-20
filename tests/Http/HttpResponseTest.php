<?php

declare(strict_types=1);

namespace SmsClient\Tests\Http;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SmsClient\Http\HttpResponse;

final class HttpResponseTest extends TestCase
{
    #[Test]
    public function isSuccessful_returns_true_for_2xx_status_codes(): void
    {
        $this->assertTrue((new HttpResponse(200, ''))->isSuccessful());
        $this->assertTrue((new HttpResponse(201, ''))->isSuccessful());
        $this->assertTrue((new HttpResponse(299, ''))->isSuccessful());
    }

    #[Test]
    public function isSuccessful_returns_false_for_non_2xx_status_codes(): void
    {
        $this->assertFalse((new HttpResponse(100, ''))->isSuccessful());
        $this->assertFalse((new HttpResponse(300, ''))->isSuccessful());
        $this->assertFalse((new HttpResponse(400, ''))->isSuccessful());
        $this->assertFalse((new HttpResponse(500, ''))->isSuccessful());
    }
}
