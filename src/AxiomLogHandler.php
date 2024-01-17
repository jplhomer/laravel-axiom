<?php

namespace Jplhomer\Axiom;

use CurlHandle;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;

class AxiomLogHandler extends AbstractProcessingHandler
{
    const AXIOM_ENDPOINT = 'https://api.axiom.co/v1/datasets';

    protected string $dataset;

    protected string $apiToken;

    public function __construct($dataset, $apiToken, int|string|Level $level = Level::Debug, bool $bubble = true)
    {
        $this->dataset = $dataset;
        $this->apiToken = $apiToken;

        parent::__construct($level, $bubble);
    }

    /**
     * Starts a fresh curl session for the given endpoint and returns its handler.
     */
    private function loadCurlHandle(): CurlHandle
    {
        $url = "https://api.axiom.co/v1/datasets/{$this->dataset}/ingest";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer '.$this->apiToken,
            'Content-Type: application/json',
        ]);

        return $ch;
    }

    protected function write(LogRecord $record): void
    {
        $ch = $this->loadCurlHandle();

        $data = $record->toArray();

        $data = $record->context;
        $data['message'] = $record->message;
        $data['level'] = $record->level;
        $data['channel'] = $record->channel;

        // Axiom expects an array of records, so we wrap the record in an array.
        $data = [$data];

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        curl_exec($ch);
        curl_close($ch);
    }
}
