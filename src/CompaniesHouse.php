<?php

namespace Ghazanfar\CompaniesHouse;

use Ghazanfar\CompaniesHouse\Exceptions\ApiKeyException;
use GuzzleHttp\Client;


/**
 * Class CompaniesHouse
 * @package Ghazanfar\CompaniesHouse
 */

class CompaniesHouse
{

    /**
     * @var
     */
    public $client;
    /**
     * @var
     */
    protected $base_api = 'https://api.companieshouse.gov.uk/';
    /**
     * @var
     */
    protected $key;

    /**
     * CompaniesHouse constructor.
     * @param $key
     * @throws ApiKeyException
     */

    public function __construct($key)
    {

        if (!empty($key) && $key != '') {
            $this->key = $key;

            $this->client = new Client(array(
                'base_uri' => $this->base_api,
                'auth' => array(
                    $key,
                    ''
                )
            ));

        } else {

            throw new ApiKeyException('Missing Api Key: CompaniesHouse API Key is required. Please visit https://developer.companieshouse.gov.uk/developer/applications');

        }

    }

    /**
     * @param $company
     * @return \Psr\Http\Message\StreamInterface
     */

    public function search($company)
    {

        if (!empty($company) && $company != '') {

            $params = array(
                'query' => array(
                    'q' => $company
                )
            );

            $response = $this->client->request('GET', 'search/companies', $params);

            return $this->response($response->getBody());

        } else {

            throw new \InvalidArgumentException('Missing Search Term: Company name can not be empty, you must provide a company name to search from Companies House.');
        }

    }

    /**
     * @param $number
     * @return array|mixed|null|object
     */

    public function searchByNumber($number)
    {

        if (!empty($number) && $number != '') {
            $response = $this->client->request('GET', 'company/' . $number);

            return $this->response($response->getBody());

        } else {

            throw new \InvalidArgumentException('Missing Search Term: Company number can not be empty, you must provide a company number to get profile.');

        }
    }

    /**
     * extract data from the response
     *
     * @param $response
     * @return array|mixed|null|object
     * @throws \Exception
     */

    private function response($response)
    {

        if (empty($response) || !is_object($response)) {
            throw new \Exception('Invalid response to extract data from.');
        }

        return json_decode($response);
    }

    /**
     * test method
     */

    public function test()
    {
        var_dump('This is the test message');
    }
}