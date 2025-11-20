<?php

declare(strict_types=1);

namespace SmsClient\Tests\Auth;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SmsClient\Auth\BasicAuthStrategy;
use SmsClient\Auth\BearerTokenStrategy;
use SmsClient\Auth\NoAuthStrategy;

final class AuthTest extends TestCase
{
    #[Test]
    public function basic_auth_adds_authorization_header(): void
    {
        $strategy = new BasicAuthStrategy('user', 'pass');
        $headers = $strategy->applyToHeaders([]);
        
        $this->assertArrayHasKey('Authorization', $headers);
        $this->assertSame('Basic ' . base64_encode('user:pass'), $headers['Authorization']);
    }

    #[Test]
    public function basic_auth_does_not_modify_payload(): void
    {
        $strategy = new BasicAuthStrategy('user', 'pass');
        $payload = ['key' => 'value'];
        
        $this->assertSame($payload, $strategy->applyToPayload($payload));
    }

    #[Test]
    public function bearer_token_auth_adds_authorization_header(): void
    {
        $strategy = new BearerTokenStrategy('my-token');
        $headers = $strategy->applyToHeaders([]);
        
        $this->assertArrayHasKey('Authorization', $headers);
        $this->assertSame('Bearer my-token', $headers['Authorization']);
    }

    #[Test]
    public function bearer_token_auth_does_not_modify_payload(): void
    {
        $strategy = new BearerTokenStrategy('my-token');
        $payload = ['key' => 'value'];
        
        $this->assertSame($payload, $strategy->applyToPayload($payload));
    }

    #[Test]
    public function no_auth_does_not_modify_headers(): void
    {
        $strategy = new NoAuthStrategy();
        $headers = ['Content-Type' => 'application/json'];
        
        $this->assertSame($headers, $strategy->applyToHeaders($headers));
    }

    #[Test]
    public function no_auth_does_not_modify_payload(): void
    {
        $strategy = new NoAuthStrategy();
        $payload = ['key' => 'value'];
        
        $this->assertSame($payload, $strategy->applyToPayload($payload));
    }
}
