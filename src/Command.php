<?php declare(strict_types=1);

namespace Folleah\Shellrun;

class Command
{
    private $command;

    public function __construct(string $command)
    {
        $this->command = $command;
    }

    public function withArgs(array $args) : self
    {
        foreach ($args as $arg => $val) {
            $this->command .= " $arg=$val";
        }

        return $this;
    }

    public function withArg($argument, $value) : self
    {
        $this->command .= " $argument=$value";

        return $this;
    }

    public function call()
    {
        $descriptorSpec = [
            'read' => ['pipe', 'r'],
            'write' => ['pipe', 'w'],
            'error' => ['file', '/tmp/error-output.txt', 'a'],
        ];

        $cwd = '/tmp';
        $env = array('some_option' => 'aeiou');
        $process = proc_open($this->command, $descriptorSpec, $pipes, $cwd, $env);

        if (\is_resource($process)) {
            // $pipes теперь выглядит так:
            // 0 => записывающий обработчик, подключенный к дочернему stdin
            // 1 => читающий обработчик, подключенный к дочернему stdout
            // Вывод сообщений об ошибках будет добавляться в /tmp/error-output.txt

            fwrite($pipes['write'], '<?php print_r($_ENV); ?>');
            fclose($pipes['write']);

            echo stream_get_contents($pipes['read']);
            fclose($pipes['read']);

            // Важно закрывать все каналы перед вызовом
            // proc_close во избежание мертвой блокировки
            $return_value = proc_close($process);

            echo "команда вернула $return_value\n";
        }
    }
}