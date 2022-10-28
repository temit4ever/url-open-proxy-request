<?php

namespace App\Services\Proxy;

/**
 * Class to get open proxy based on any supplied API URL
 *
 */
class ProxyLocator
{
    /**
     * Find any open proxy
     *
     * @return array
     */
    public function getProxyScrape(): array
    {
        $curl = curl_init(config('services.proxy-scrape.uri') . '?request=displayproxies&protocol=http&timeout=10000&country=all&ssl=all&anonymity=all');
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if (!empty($error)) {
            echo $error . ' with error code: ' . curl_errno($curl);
        }

        return explode("\n", $response);
    }

}
