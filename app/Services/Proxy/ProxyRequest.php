<?php

namespace App\Services\Proxy;

use Illuminate\Support\Facades\Log;

/**
 * Class give the ability to get any supplied uri header after crawling
 *
 */
class ProxyRequest
{
    private ProxyLocator $proxyLocator;

    public function __construct(ProxyLocator $proxyLocator)
    {
        $this->proxyLocator = $proxyLocator;
    }

    /**
     * The Functionality to perform the Request to crawl the provided url.
     *
     * @param string|null $url
     * @return array
     */
    public function getResponseFromRequest(?string $url): array
    {
        $proxies = $this->proxyLocator->getProxyScrape();
        // There is possibility there might be more than one proxy
        // we create a response array to keep their curl results.
        $response = [];

        if (!empty($proxies)) {
            foreach ($proxies as $key => $proxy) {
                $curl = curl_init($url);

                curl_setopt($curl, CURLOPT_HEADER, 1);
                curl_setopt($curl, CURLOPT_PROXY, $proxy);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

                curl_exec($curl);
                $result = curl_exec($curl);
                $error = curl_error($curl);
                curl_close($curl);

                $response[] = $result;
                if ($result) {
                    if (!empty($error)) {
                        Log::error($error);
                        echo $key . ' with error code: ' . curl_errno($curl) . "\r\n";
                    }
                }

            }
            return $response;
        }
    }
}
