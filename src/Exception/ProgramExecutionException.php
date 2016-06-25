<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Exception;

//----------------------------------------------------------------------------------------------------------------------
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
   * @param string   $command The executed command.
   * @param int      $status  The exit status of the executed command.
   * @param string[] $output  The output of the executed command.
   *
   * @api
   */
  public function __construct($command, $status, $output)
  {
    parent::__construct(self::message($command, $status, $output));

    $this->command = $command;
    $this->status  = $status;
    $this->output  = $output;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the executed command.
   *
   * @return string
   *
   * @api
   */
  public function getCommand()
  {
    return $this->command;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the output of the executed command.
   *
   * @return \string[]
   *
   * @api
   */
  public function getOutput()
  {
    return $this->output;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the exits status of the command.
   *
   * @return int
   *
   * @api
   */
  public function getStatus()
  {
    return $this->status;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Composes the exception message.
   *
   * @param string   $command The executed command.
   * @param int      $status  The exit status of the executed command.
   * @param string[] $output  The output of the executed command.
   *
   * @return string
   */
  private function message($command, $status, $output)
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
