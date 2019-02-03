<?php
declare(strict_types=1);

namespace SetBased\Helper;

use SetBased\Exception\ProgramExecutionException;

/**
 * A helper class for program execution.
 */
class ProgramExecution
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a popper escaped command.
   *
   * @param string[] $args The command and its arguments.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  public static function escape(array $args): string
  {
    $command = '';
    foreach ($args as $arg)
    {
      if ($command!='') $command .= ' ';
      $command .= escapeshellarg($arg);
    }

    return $command;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Executes an external program and throws an exception if the external program fails.
   *
   * @param string[]   $command       The command that will be executed. This method will compose the command executed
   *                                  by exec with proper escaping.
   * @param int[]|null $statuses      The allowed return statuses. If the return status of the command is not in
   *                                  this array an exception will be thrown. Null will allow all return statuses and an
   *                                  empty array will throw an exception always.
   * @param  bool      $ignoreStdErr  The standard error is normally redirected to standard output. If true standard
   *                                  error is ignored (i.e. redirected to /dev/null).
   *
   * @return array<null|array|integer> An array with two elements: the output of the external program as an array of
   *                                   lines and the return status of the external program.
   *
   * @since 1.0.0
   * @api
   */
  public static function exec1(array $command, ?array $statuses = [0], bool $ignoreStdErr = false): array
  {
    $command = self::escape($command);

    if ($ignoreStdErr)
    {
      $command .= ' 2> /dev/null';
    }
    else
    {
      $command .= ' 2>&1';
    }

    exec($command, $output, $status);

    if (is_array($statuses) && !in_array($status, $statuses))
    {
      throw new ProgramExecutionException($command, $status, $output);
    }

    return [$output, $status];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Executes an external program and throws an exception if the external program fails.
   *
   * @param string[]    $command    The command that will be executed. This method will compose the command
   *                                executed by exec with proper escaping.
   * @param string|null $stdout     The filename to redirect the standard output. If null the standard output is
   *                                redirected to /dev/null.
   * @param string|null $stderr     The filename to redirect the standard error. If null the standard error is
   *                                redirected to the standard output. Use '/dev/null' to ignore standard error.
   * @param int[]|null  $statuses   The allowed return statuses. If the return status of the command is not in
   *                                this array an exception will be thrown. Null will allow all return statuses and an
   *                                empty array will throw an exception always.
   *
   * @return int The exit status of the external program.
   *
   * @since 1.0.0
   * @api
   */
  public static function exec2(array $command,
                               ?string $stdout = null,
                               ?string $stderr = null,
                               ?array $statuses = [0]): int
  {
    $command = self::escape($command);

    if ($stdout===null)
    {
      $command .= ' > /dev/null';
    }
    else
    {
      $command .= ' > ';
      $command .= escapeshellarg($stdout);
    }

    if ($stderr===null)
    {
      $command .= ' 2>&1';
    }
    else
    {
      $command .= ' 2> ';
      $command .= escapeshellarg($stderr);
    }

    exec($command, $output, $status);

    if (is_array($statuses) && !in_array($status, $statuses))
    {
      throw new ProgramExecutionException($command, $status, $output);
    }

    return $status;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
