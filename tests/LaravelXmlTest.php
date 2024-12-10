<?php

namespace PhpJunior\LaravelXml\Tests;

use Illuminate\Support\Arr;
use Orchestra\Testbench\TestCase;
use PhpJunior\LaravelXml\LaravelXmlServiceProvider;

class LaravelXmlTest extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [LaravelXmlServiceProvider::class];
    }

    public function testArrayToXml()
    {
        $data = [
            'name' => 'John Doe'
        ];

        $xml = Arr::toXml($data);
        $this->assertStringContainsString('<name>John Doe</name>', $xml);
    }

    public function testArrayToXmlRootElement()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'johndoe@email.com'
        ];

        $xml = Arr::toXml($data, 'user');
        $this->assertStringContainsString('<user>', $xml);
    }

    public function testArrayToXmlCdata()
    {
        $data = [
            'bio' => 'This is a <strong>bio</strong>'
        ];

        $xml = Arr::toXml($data, 'user', ['bio']);
        $this->assertStringContainsString('<bio><![CDATA[This is a <strong>bio</strong>]]></bio>', $xml);
    }

    public function testArrayToXmlCdataWildcard()
    {
        $data = [
            'bio01' => 'This is a <strong>bio01</strong>',
            'bio02' => 'This is a <strong>bio02</strong>',
            'bio03' => 'This is a <strong>bio03</strong>',
            'bio04' => 'This is a <strong>bio04</strong>',
        ];

        $xml = Arr::toXml($data, 'user', 'bio*');
        $this->assertStringContainsString('<bio01><![CDATA[This is a <strong>bio01</strong>]]></bio01>', $xml);
        $this->assertStringContainsString('<bio02><![CDATA[This is a <strong>bio02</strong>]]></bio02>', $xml);
        $this->assertStringContainsString('<bio03><![CDATA[This is a <strong>bio03</strong>]]></bio03>', $xml);
        $this->assertStringContainsString('<bio04><![CDATA[This is a <strong>bio04</strong>]]></bio04>', $xml);
    }

    public function testArrayToXmlNumericArrayValue()
    {
        $data = [
            'code' => [
                'A001',
                'A002',
            ]
        ];

        $xml = Arr::toXml($data, 'codes');
        $this->assertStringContainsString('<code>A001</code>', $xml);
        $this->assertStringContainsString('<code>A002</code>', $xml);
    }

    public function testXmlToArray()
    {
        $xml = '<?xml version="1.0" encoding="utf-8"?>
            <root>
                <name>John Doe</name>
                <email>johndoe@email.com</email>
            </root>';

        $array = Arr::fromXml($xml);
        $this->assertArrayHasKey('name', $array);
    }
}