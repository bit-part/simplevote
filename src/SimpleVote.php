<?php

namespace tinybeans\simplevote;

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
        $referer = $_SERVER['HTTP_REFERER'];
        $parseUrl = parse_url($referer);
        if (stristr($parseUrl['host'], $serverName) === false){
            header('HTTP/1.1 400 Bad Request');
            echo 'Invalid access';
            exit();
        }
    }

    public function checkParams(bool $debug): void
    {
        if (!$debug && (empty($_POST['voteId']) && empty($_POST['voteType']) && empty($_POST['getCurrentData']))) {
            header('HTTP/1.1 400 Bad Request');
            echo 'Invalid parameters';
            exit();
        }

        $this->voteId = $_POST['voteId'] ?? $_GET['voteId'] ?? '';
        $this->voteType = $_POST['voteType'] ?? $_GET['voteType'] ?? '';
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
        if (file_put_contents($tempFilename, json_encode($data))) {
            unlink($this->dataFilePath);
            rename($tempFilename, $this->dataFilePath);
        }
        return [ 'count' => $count ];
    }

    public function getData()
    {
        if ($content = file_get_contents($this->dataFilePath)) {
            $data = json_decode($content, true);
        }
        else {
            $data = [];
        }
        return $data;
    }
}
