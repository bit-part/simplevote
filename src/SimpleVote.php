<?php

namespace bitpart\simplevote;

class SimpleVote
{
    public string $dataDirPath = '';
    public string $dataFilePath = '';
    public string $voteId = '';
    public string $voteType = '';
    public bool $getCurrentData = false;

    public function __construct(string $filename = 'vote', bool $debug = false)
    {
        $this->dataDirPath = getcwd() . DIRECTORY_SEPARATOR . 'data';
        if (!file_exists($this->dataDirPath)) {
            mkdir($this->dataDirPath);
            chmod($this->dataDirPath, 0755);
        }

        $this->dataFilePath = $this->dataDirPath . DIRECTORY_SEPARATOR . "{$filename}.json";

        $this->checkHostname();
        $this->checkParams($debug);
    }

    public function checkHostname(): void
    {
        $serverName = $_SERVER['SERVER_NAME'];
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';

        // `HTTP_ORIGIN` が空でないかつ `SERVER_NAME` と一致しない場合にエラーを返す
        if ($origin !== '' && parse_url($origin, PHP_URL_HOST) !== $serverName) {
            header('HTTP/1.1 400 Bad Request');
            echo 'Invalid origin';
            exit();
        }
    }

    public function checkParams(bool $debug): void
    {
        if (!$debug && (empty($_POST['voteId']) && empty($_POST['voteType']) && empty($_POST['getCurrentData']))) {
            error_log('Invalid parameters: Missing voteId, voteType, or getCurrentData in request.');
            header('HTTP/1.1 400 Bad Request');
            echo 'Invalid parameters';
            exit();
        }

        $this->voteId = filter_input(INPUT_POST, 'voteId', FILTER_SANITIZE_STRING) ?? filter_input(INPUT_GET, 'voteId', FILTER_SANITIZE_STRING) ?? '';
        $this->voteType = filter_input(INPUT_POST, 'voteType', FILTER_SANITIZE_STRING) ?? filter_input(INPUT_GET, 'voteType', FILTER_SANITIZE_STRING) ?? '';
        $this->getCurrentData = empty($_POST['getCurrentData']) === false;

        if ($this->getCurrentData === false && $this->voteType !== 'inc' && $this->voteType !== 'dec') {
            header('HTTP/1.1 400 Bad Request');
            echo 'Invalid voteType';
            exit();
        }
    }

    /**
     * @param string $voteType
     * @param int $count
     * @return int
     */
    public function vote(int $count): int
    {
        $voteType = $this->voteType;
        if ($voteType == 'inc') {
            $count++;
        }
        elseif ($voteType == 'dec') {
            $count--;
        }
        if ($count < 0) {
            $count = 0;
        }
        return $count;
    }

    public function exec()
    {
        $key = $this->voteId;
        $data = $this->getData();
        if ($this->getCurrentData) {
            return $data;
        }
        $count = $data[$key] ?? 0;
        $count = $this->vote($count);
        $data[$key] = $count;
        $tempFilename = tempnam($this->dataDirPath, 'simpleVote');
        if ($tempFilename === false) {
            header('HTTP/1.1 500 Internal Server Error');
            echo 'Failed to create temporary file';
            exit();
        }

        if (file_put_contents($tempFilename, json_encode($data)) === false) {
            unlink($tempFilename);
            header('HTTP/1.1 500 Internal Server Error');
            echo 'Failed to write data to temporary file';
            exit();
        }

        if (!unlink($this->dataFilePath) || !rename($tempFilename, $this->dataFilePath)) {
            unlink($tempFilename);
            header('HTTP/1.1 500 Internal Server Error');
            echo 'Failed to update data file';
            exit();
        }

        return [ 'count' => $count ];
    }

    public function getData(): array
    {
        if (!file_exists($this->dataFilePath) || !is_readable($this->dataFilePath)) {
            return [];
        }

        $content = file_get_contents($this->dataFilePath);
        if ($content === false) {
            error_log('Failed to read data file: ' . $this->dataFilePath);
            return [];
        }

        $data = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log('Failed to decode JSON from data file: ' . json_last_error_msg());
            return [];
        }

        return $data;
    }
}
