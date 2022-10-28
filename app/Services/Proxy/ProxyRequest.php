<?php

namespace App\Services\Proxy;

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
     * @return string|void
     */
    public function getHeaderFrom(?string $url)
    {
        $proxies = $this->proxyLocator->getProxyScrape();
        $response = '';

        if (!empty($proxies)) {
            foreach ($proxies as $proxy) {
                $curl = curl_init($url);

                curl_setopt($curl, CURLOPT_HEADER, 1);
                curl_setopt($curl, CURLOPT_PROXY, $proxy);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

                $response = curl_exec($curl);

                curl_close($curl);

                if (!empty($error)) {
                    echo $error . ' with error code: ' . curl_errno($curl);
                }
            }

            $parts = explode("\r\n\r\n", $response, 2);
            return $parts[0];
        }
    }
}
