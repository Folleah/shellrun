<?php declare(strict_types=1);

namespace Folleah\Shellrun;

class CommandProcess
{
    private $process;
    private $readStream;
    private $writeStream;

    public function __construct(Command $command)
    {
        $descriptorSpec = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['file', 'php://stderr', 'w'],
        ];

        $process = proc_open($command->getCommand(), $descriptorSpec, $pipes);

        if (!\is_resource($process)) {
            throw new \Exception('Failed to init command process.');
        }

//        set_time_limit(60);
//        while (!feof($pipes[1]))
//        {
//            $return_message = fgets($pipes[1], 1024);
//            if ($return_message === '') break;
//            echo $return_message;
//        }

        $this->process = $process;
        $this->readStream = $pipes[0];
        $this->writeStream = $pipes[1];
    }

    public function write(string $data) : void
    {
        fwrite($this->readStream, $data);
    }

    public function read() : string
    {
        $data = stream_get_contents($this->writeStream);

        return $data;
    }

    public function __destruct()
    {
        fclose($this->readStream);
        fclose($this->writeStream);
        proc_close($this->process);
    }
}