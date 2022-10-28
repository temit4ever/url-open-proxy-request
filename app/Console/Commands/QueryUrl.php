<?php

namespace App\Console\Commands;

use App\Services\Proxy\ProxyRequest;
use Illuminate\Console\Command;

class QueryUrl extends Command
{
    protected $signature = 'query:url {url}';

    protected $description = 'N/A';
    protected ProxyRequest $proxyRequest;

    public function __construct(ProxyRequest $proxyRequest)
    {
        parent::__construct();
        $this->proxyRequest = $proxyRequest;
    }

    public function handle()
    {
        $url = $this->argument('url');
        $responses = $this->proxyRequest->getResponseFromRequest($url);

        foreach ($responses as $response) {
            if ($response) {
                $parts = explode("\r\n\r\n", $response, 2);
                $header = $parts[0];
                $this->line($header);

                // Log this request.
                $now = date('d/m/Y H:i:s');
                file_put_contents(storage_path() . '/logs/results.log', "{$now}: {$url}\r\n", FILE_APPEND);
            }
        }

        return 0;
    }
}
