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
            $this->command .= " $arg" . null === $val
                ? "=$val"
                : "";
        }

        return $this;
    }

    public function withArg($argument, $value = null) : self
    {
        $this->command .= " $argument" . null === $value
            ? "=$value"
            : "";

        return $this;
    }

    public function getCommand() : string
    {
        return $this->command;
    }

    public function runtime() : CommandProcess
    {
        return new CommandProcess($this);
    }
}