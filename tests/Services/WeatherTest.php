<?php

/*
 * This file is part of the flower-allure/composer-utils.
 * (c) flower-allure <i@flower-allure.me>
 * This source file is subject to the LGPL license that is bundled.
 */

namespace FlowerAllure\ComposerUtils\Tests\Services;

use Mockery;
use GuzzleHttp\Client;
use Mockery\Matcher\AnyArgs;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\TransferException;
use FlowerAllure\ComposerUtils\App\Services\WeatherServer;
use FlowerAllure\ComposerUtils\App\Exceptions\HttpException;
use FlowerAllure\ComposerUtils\App\Exceptions\InvalidArgumentException;

class WeatherTest extends TestCase
{
    private WeatherServer $weatherServer;

    protected function setUp(): void
    {
        $this->weatherServer = new WeatherServer('mock-key');
        parent::setUp();
    }

    /**
     * 测试天气参数 $format 是 json|xml
     * @throws HttpException
     */
    public function testGetWeatherWithInvalidFormat()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid response format: array');

        $this->weatherServer->getWeather('深圳', 'base', 'array');

        $this->fail('Failed to assert getWeather throw exception with invalid argument.');
    }

    /**
     * 测试天气参数 $type 是 base|all
     * @throws HttpException
     */
    public function testGetWeatherWithInvalidType()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid type value(base/all): foo');

        $this->weatherServer->getWeather('上海', 'foo');

        $this->fail('Failed to assert getWeather throw exception with invalid argument.');
    }

    /**
     * 测试天气HTTP请求-返回JSON
     * @throws GuzzleException
     * @throws InvalidArgumentException
     * @throws HttpException
     */
    public function testGetWeatherJson()
    {
        // 创建模拟接口响应值
        $response = new Response(200, [], '{"success": true}');

        // 创建模拟 HTTP Client
        $client = Mockery::mock(Client::class);

        // 当调用get方法时返回响应
        $client->allows()->get('https://restapi.amap.com/v3/weather/weatherInfo', [
            'query' => [
                'key' => 'mock-key',
                'city' => '深圳',
                'output' => 'json',
                'extensions' => 'base',
            ],
        ])->andReturn($response);

        // 将 getHttpClient 方法替换为上面创建的 HTTP Client 为返回值的模拟方法
        $weather = Mockery::mock(WeatherServer::class, ['mock-key'])->makePartial();
        $weather->allows()->getHttpClient()->andReturn($client);

        $this->assertSame(['success' => true], $weather->getWeather('深圳'));
    }

    /**
     * 测试天气HTTP请求-返回XML
     * @throws GuzzleException
     * @throws HttpException
     * @throws InvalidArgumentException
     */
    public function testGetWeatherXml()
    {
        // 创建模拟接口响应值
        $response = new Response(200, [], '<hello>content</hello>');

        // 创建模拟 HTTP Client
        $client = Mockery::mock(Client::class);

        // 当调用get方法时返回响应
        $client->allows()->get('https://restapi.amap.com/v3/weather/weatherInfo', [
            'query' => [
                'key' => 'mock-key',
                'city' => '深圳',
                'extensions' => 'all',
                'output' => 'xml',
            ],
        ])->andReturn($response);

        // 将 getHttpClient 方法替换为上面创建的 HTTP Client 为返回值的模拟方法
        $weather = Mockery::mock(WeatherServer::class, ['mock-key'])->makePartial();
        $weather->allows()->getHttpClient()->andReturn($client);

        $this->assertSame('<hello>content</hello>', $weather->getWeather('深圳', 'all', 'xml'));
    }

    /**
     * 测试天气请求异常
     * @throws GuzzleException
     * @throws InvalidArgumentException
     */
    public function testGetWeatherWithGuzzleRuntimeException()
    {
        $client = Mockery::mock(Client::class);
        // 当调用get方法时抛出异常
        $client->allows()->get(new AnyArgs())->andThrow(new TransferException('request timeout'));

        $weather = Mockery::mock(WeatherServer::class, ['mock-key'])->makePartial();
        // 当调用getHttpClient方法时返回$client
        $weather->allows()->getHttpClient()->andReturn($client);

        // 断言调用时会产生异常
        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('request timeout');

        $weather->getWeather('深圳');
    }

    /**
     * 测试 getHttpClient 返回结果为 GuzzleHttp\ClientInterface 实例
     * @return void
     */
    public function testGetHttpClient()
    {
        $this->assertInstanceOf(ClientInterface::class, $this->weatherServer->getHttpClient());
    }

    /**
     * 测试天气参数 $year 是有效的
     * @throws HttpException
     */
    public function testGetCityWithInvalidYear()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid year: 1');

        $this->weatherServer->getCity('1');

        $this->fail('Failed to assert getCity throw exception with invalid argument.');
    }

    /**
     * 测试城市HTTP请求
     * @return void
     * @throws GuzzleException
     * @throws HttpException
     */
    public function testGetCity()
    {
        $client = Mockery::mock(Client::class);
        $client->allows()->get('https://www.wenjiangs.com/api/v2/xzqhSimple', [
            'query' => [
                'year' => '2022',
            ],
        ])->andReturn(new Response(200, [], '{"success": true}'));

        $weather = Mockery::mock(WeatherServer::class, ['mock-key'])->makePartial();
        $weather->allows()->getHttpClient()->andReturn($client);

        $this->assertSame(['success' => true], $weather->getCity(2022));
    }

    /**
     * 测试实时天气
     * @throws HttpException
     * @throws InvalidArgumentException
     */
    public function testGetLiveWeather()
    {
        $weather = Mockery::mock(WeatherServer::class, ['mock-key'])->makePartial();
        $weather->expects()->getWeather('深圳', 'base', 'json')->andReturn(['success' => true]);
        $this->assertSame(['success' => true], $weather->getLiveWeather('深圳'));
    }

    /**
     * 测试天气预报
     * @throws HttpException
     * @throws InvalidArgumentException
     */
    public function testGetForecastsWeather()
    {
        $weather = Mockery::mock(WeatherServer::class, ['mock-key'])->makePartial();
        $weather->expects()->getWeather('深圳', 'all', 'json')->andReturn(['success' => true]);
        $this->assertSame(['success' => true], $weather->getForecastsWeather('深圳'));
    }
}
