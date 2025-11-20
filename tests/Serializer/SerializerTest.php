<?php

declare(strict_types=1);

namespace SmsClient\Tests\Serializer;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use SmsClient\Serializer\FormUrlEncodedSerializer;
use SmsClient\Serializer\JsonSerializer;

final class SerializerTest extends TestCase
{
    #[Test]
    public function json_serializer_encodes_data(): void
    {
        $serializer = new JsonSerializer();
        $data = ['key' => 'value', 'jp' => '日本語'];
        
        $this->assertSame('{"key":"value","jp":"日本語"}', $serializer->serialize($data));
        $this->assertSame('application/json', $serializer->getContentType());
    }

    #[Test]
    public function form_url_encoded_serializer_encodes_data(): void
    {
        $serializer = new FormUrlEncodedSerializer();
        $data = ['key' => 'value', 'space' => 'a b'];
        
        $this->assertSame('key=value&space=a%20b', $serializer->serialize($data));
        $this->assertSame('application/x-www-form-urlencoded', $serializer->getContentType());
    }
}
