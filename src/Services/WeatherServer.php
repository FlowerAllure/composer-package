<?php

namespace FlowerAllure\ComposerUtils\App\Services;

use GuzzleHttp\Client;
use FlowerAllure\ComposerUtils\App\Exceptions\HttpException;
use GuzzleHttp\Exception\GuzzleException;
use FlowerAllure\ComposerUtils\App\Exceptions\InvalidArgumentException;

class WeatherServer
{
    public function __construct(protected string $key, protected array $guzzleOptions = [])
    {
    }

    public function getHttpClient(): Client
    {
        return new Client($this->guzzleOptions);
    }

    /**
     * 请求天气接口
     * @param string $city 城市编码或则城市名称
     * @param string $type 类型 base|all
     * @param string $format 编码格式 json|xml
     * @throws HttpException
     * @throws InvalidArgumentException
     */
    public function getWeather(string $city, string $type = 'base', string $format = 'json')
    {
        $url = 'https://restapi.amap.com/v3/weather/weatherInfo';

        if (!in_array(strtolower($type), ['base', 'all'])) {
            throw new InvalidArgumentException('Invalid type value(base/all): ' . $type);
        }

        if (!in_array(strtolower($format), ['xml', 'json'])) {
            throw new InvalidArgumentException('Invalid response format: ' . $format);
        }

        $query = array_filter([
            'key' => $this->key,
            'city' => $city,
            'output' => $format,
            'extensions' => $type,
        ]);

        try {
            $response = $this->getHttpClient()->get($url, [
                'query' => $query,
            ])->getBody()->getContents();

            return 'json' === $format ? json_decode($response, true) : $response;
        } catch (GuzzleException $exception) {
            throw new HttpException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * 请求城市接口
     * @return mixed
     * @throws HttpException
     */
    public function getCity(): array
    {
        $url = 'https://www.wenjiangs.com/api/v2/xzqhSimple';
        $query = ['year' => date('Y')];

        try {
            $response = $this->getHttpClient()->get($url, [
                'query' => $query,
            ])->getBody()->getContents();

            return json_decode($response, true);
        } catch (GuzzleException $exception) {
            throw new HttpException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }
}
