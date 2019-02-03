<?php
declare(strict_types=1);

namespace SetBased\Exception;

/**
 * Exceptions for failed program executions.
 */
class ProgramExecutionException extends \RuntimeException
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The executed command.
   *
   * @var string
   */
  private $command;

  /**
   * The output of the executed command.
   *
   * @var string[]
   */
  private $output;

  /**
   * The exit status of the executed command.
   *
   * @var int
   */
  private $status;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string        $command The executed command.
   * @param int           $status  The exit status of the executed command.
   * @param null|string[] $output  The output of the executed command.
   *
   * @since 1.0.0
   * @api
   */
  public function __construct(string $command, int $status, ?array $output)
  {
    parent::__construct(self::message($command, $status, $output));

    $this->command = $command;
    $this->status  = $status;
    $this->output  = ($output==null) ? [] : $output;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the executed command.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  public function getCommand(): string
  {
    return $this->command;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the output of the executed command.
   *
   * @return string[]
   *
   * @since 1.0.0
   * @api
   */
  public function getOutput(): array
  {
    return $this->output;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the exits status of the command.
   *
   * @return int
   *
   * @since 1.0.0
   * @api
   */
  public function getStatus(): int
  {
    return $this->status;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Composes the exception message.
   *
   * @param string        $command The executed command.
   * @param int           $status  The exit status of the executed command.
   * @param null|string[] $output  The output of the executed command.
   *
   * @return string
   */
  private function message(string $command, int $status, ?array $output): string
  {
    $message = sprintf('Command below exited with status %s:', $status);
    $message .= PHP_EOL;
    $message .= $command;

    if (!empty($output))
    {
      $message .= PHP_EOL;
      $message .= implode(PHP_EOL, $output);
    }

    return $message;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
