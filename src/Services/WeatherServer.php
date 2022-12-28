<?php

/*
 * This file is part of the flower-allure/composer-utils.
 * (c) flower-allure <i@flower-allure.me>
 * This source file is subject to the LGPL license that is bundled.
 */

namespace FlowerAllure\ComposerUtils\App\Services;

use FlowerAllure\ComposerUtils\App\Exceptions\HttpException;
use FlowerAllure\ComposerUtils\App\Exceptions\InvalidArgumentException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

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
     * 请求天气接口.
     *
     * @param string $city   城市编码或则城市名称
     * @param string $type   类型 base|all
     * @param string $format 编码格式 json|xml
     *
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
     * 请求城市接口.
     *
     * @param string $year 年份
     *
     * @throws InvalidArgumentException
     * @throws HttpException
     */
    public function getCity(string $year): array
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
     * 获取实时天气.
     *
     * @return mixed|string
     *
     * @throws HttpException
     * @throws InvalidArgumentException
     */
    public function getLiveWeather(string $city, string $format = 'json'): mixed
    {
        return $this->getWeather($city, 'base', $format);
    }

    /**
     * 获取天气预报.
     *
     * @param string $city   年份
     * @param string $format 格式，默认json
     *
     * @throws HttpException
     * @throws InvalidArgumentException
     */
    public function getForecastsWeather(string $city, string $format = 'json'): mixed
    {
        return $this->getWeather($city, 'all', $format);
    }
}
