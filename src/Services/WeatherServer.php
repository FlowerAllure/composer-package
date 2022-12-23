<?php

namespace FlowerAllure\ComposerUtils\App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use FlowerAllure\ComposerUtils\App\Exceptions\HttpException;
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

        if (!in_array($type, ['base', 'all'])) {
            throw new InvalidArgumentException('Invalid type value(base/all): ' . $type);
        }

        if (!in_array($format, ['xml', 'json'])) {
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
     * @param mixed $year
     * @return mixed
     * @throws InvalidArgumentException
     * @throws HttpException
     */
    public function getCity($year): array
    {
        $url = 'https://www.wenjiangs.com/api/v2/xzqhSimple';

        if (!preg_match('/^(19|20)\\d{2}$/', $year)) {
            throw new InvalidArgumentException('Invalid year: ' . $year);
        }

        try {
            $response = $this->getHttpClient()->get($url, [
                'query' => [
                    'year' => $year,
                ],
            ])->getBody()->getContents();

            return json_decode($response, true);
        } catch (GuzzleException $exception) {
            throw new HttpException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    /**
     * 获取实时天气
     * @param string $city
     * @param string $format
     * @return mixed|string
     * @throws HttpException
     * @throws InvalidArgumentException
     */
    public function getLiveWeather(string $city, string $format = 'json'): mixed
    {
        return $this->getWeather($city, 'base', $format);
    }

    /**
     * 获取天气预报
     * @param string $city
     * @param string $format
     * @return mixed|string
     * @throws HttpException
     * @throws InvalidArgumentException
     */
    public function getForecastsWeather(string $city, string $format = 'json'): mixed
    {
        return $this->getWeather($city, 'all', $format);
    }
}
