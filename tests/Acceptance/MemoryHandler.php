<?php
declare(strict_types=1);

namespace DR\SymfonyRequestId\Tests\Acceptance;

use Countable;
use DR\Utils\Assert;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\LogRecord;

use function count;

final class MemoryHandler extends AbstractProcessingHandler implements Countable
{
    /** @var string[] */
    private array $logs = [];

    protected function write(LogRecord $record): void
    {
        $this->logs[] = Assert::string($record->formatted);
    }

    public function count(): int
    {
        return count($this->logs);
    }

    /**
     * @return string[]
     */
    public function getLogs(): array
    {
        return $this->logs;
    }
}
