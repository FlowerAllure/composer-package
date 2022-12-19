<?php

namespace FlowerAllure\ComposerUtils\Tests\Services;

use Mockery;
use GuzzleHttp\Client;
use Mockery\Matcher\AnyArgs;
use FlowerAllure\ComposerUtils\App\Exceptions\Exception;
use GuzzleHttp\Psr7\Response;
use App\Services\WeatherServer;
use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;
use FlowerAllure\ComposerUtils\App\Exceptions\HttpException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\TransferException;
use FlowerAllure\ComposerUtils\App\Exceptions\InvalidArgumentException;

class WeatherTest extends TestCase
{
    private WeatherServer $weatherServer;

    protected function setUp(): void
    {
        $this->weatherServer = new WeatherServer('353dcf19d90f33f3ccd62e50105410f2');
        parent::setUp();
    }

    /**
     * 测试天气接口
     * @return void
     */
    public function testWeather(): void
    {
        try {
            print_r($this->weatherServer->getWeather('上海'));
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            if ($exception instanceof InvalidArgumentException) {
                $message = '参数异常：' . $message;
            } elseif ($exception instanceof HttpException) {
                $message = '接口异常：' . $message;
            }

            var_dump('调用天气扩展时出现了异常：' . $message);
        }
        $this->assertTrue(true);
    }

    /**
     * 测试城市API
     */
    public function testCity()
    {
        try {
            $this->weatherServer->getCity();
        } catch (HttpException $exception) {
            $message = $exception->getMessage();

            var_dump('调用城市API时出现了异常：' . $message);
        }
        $this->assertTrue(true);
    }

    /**
     * 测试HTTP请求-返回JSON
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
     * 测试HTTP请求-返回XML
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
     * 测试请求异常
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
     * 断言 $type 是 base|all
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
     * 断言 $format 是 json|xml
     * @throws HttpException
     */
    public function testGetWeatherWithInvalidFormat()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid response format: array');

        $this->weatherServer->getWeather('深圳', 'base', 'array');

        $this->fail('Failed to assert getWeather throw exception with invalid argument.');
    }

    // 断言返回结果为 GuzzleHttp\ClientInterface 实例
    public function testGetHttpClient()
    {
        $this->assertInstanceOf(ClientInterface::class, $this->weatherServer->getHttpClient());
    }
}
