<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Helper;

use SetBased\Exception\ProgramExecutionException;

//----------------------------------------------------------------------------------------------------------------------
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
   * @api
   */
  public static function escape($args)
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
   * @param int[]|null $returnVars    The allowed return statuses. If the return status of the command is not in
   *                                  this array an exception will be thrown. Null will allow all return statuses and an
   *                                  empty array will throw an exception always.
   * @param  bool      $ignoreStdErr  The standard error is normally redirected to standard output. If true standard
   *                                  error is ignored (i.e. redirected to /dev/null).
   *
   * @return array<null|array|integer> An array with two elements: the output of the external program as an array of
   *                                   lines and the return status of the external program.
   *
   * @api
   */
  public static function exec1($command, $returnVars = [0], $ignoreStdErr = false)
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

    exec($command, $output, $return_var);

    if (is_array($returnVars) && !in_array($return_var, $returnVars))
    {
      throw new ProgramExecutionException($command, $return_var, $output);
    }

    return [$output, $return_var];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Executes an external program and throws an exception if the external program fails.
   *
   * @param string[]    $command    The command that will be executed. This method will compose the command
   *                                executed by exec with proper escaping.
   * @param null|string $stdout     The filename to redirect the standard output. If null the standard output is
   *                                redirected to /dev/null.
   * @param null|string $stderr     The filename to redirect the standard error. If null the standard error is
   *                                redirected to the standard output. Use '/dev/null' to ignore standard error.
   * @param null|int[]  $returnVars The allowed return statuses. If the return status of the command is not in
   *                                this array an exception will be thrown. Null will allow all return statuses and an
   *                                empty array will throw an exception always.
   *
   * @return int The exit status of the external program.
   *
   * @api
   */
  public static function exec2($command, $stdout = null, $stderr = null, $returnVars = [0])
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

    exec($command, $output, $return_var);

    if (is_array($returnVars) && !in_array($return_var, $returnVars))
    {
      throw new ProgramExecutionException($command, $return_var, $output);
    }

    return (int)$return_var;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
