<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Helper\Test;

use SetBased\Exception\RuntimeException;
use SetBased\Helper\ProgramExecution;

//----------------------------------------------------------------------------------------------------------------------
class ProgramExecutionTest extends \PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with any allowed exit statuses.
   */
  public function testExec1AnyExitStatus1()
  {
    $ret = ProgramExecution::exec1(['false'], null);

    $this->assertSame(1, $ret[1]);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with any allowed exit statuses.
   */
  public function testExec1AnyExitStatus2()
  {
    $ret = ProgramExecution::exec1(['false'], []);

    $this->assertSame(1, $ret[1]);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with array as command.
   */
  public function testExec1BasicUsage()
  {
    list($output, $status) = ProgramExecution::exec1(['echo', 'hello, world!']);

    $this->assertSame(0, $status);
    $this->assertSame($output, ['hello, world!']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with failed command.
   *
   * @expectedException RuntimeException
   */
  public function testExec1ExitStatusFail()
  {
    ProgramExecution::exec1(['cmp', __FILE__, 'foogazy'], [0, 1]);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with ignore STDERR.
   */
  public function testExec1IgnoreStdErr()
  {
    $tmp = ProgramExecution::exec1(['ls', 'foogazy'], null, false);
    $this->assertNotEmpty($tmp[0]);

    $tmp = ProgramExecution::exec1(['ls', 'foogazy'], null, true);
    $this->assertSame([], $tmp[0]);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with multiple allowed exit statuses.
   */
  public function testExec1MultipleExitStatuses()
  {
    $ret = ProgramExecution::exec1(['cmp', __FILE__, __DIR__.'/../bootstrap.php'], [0, 1]);

    $this->assertSame(1, $ret[1]);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with tricky command.
   */
  public function testExec1TrickyCommand()
  {
    $strings = ['; rm -rf .. &', '"', "'"];

    foreach ($strings as $string)
    {
      list($output, $status) = ProgramExecution::exec1(['echo', $string]);

      $this->assertSame(0, $status);
      $this->assertSame($string, $output[0]);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with failed command and any exit status
   */
  public function testExec2AnyExitStatusFail()
  {
    $ret = ProgramExecution::exec2(['cmp', __FILE__, 'foogazy'], null, 'error.txt', null);

    $this->assertNotEquals(0, $ret);
    $this->assertNotEmpty(file_get_contents('error.txt'));

    unlink('error.txt');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with array as command.
   */
  public function testExec2BasicUsage()
  {
    $ret = ProgramExecution::exec2(['echo', 'hello, world!'], 'hello.txt');

    $this->assertSame(0, $ret);
    $this->assertSame('hello, world!'.PHP_EOL, file_get_contents('hello.txt'));

    unlink('hello.txt');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with failed command.
   *
   * @expectedException RuntimeException
   */
  public function testExec2ExitStatusFail()
  {
    ProgramExecution::exec2(['cmp', __FILE__, 'foogazy'], null, '/dev/null');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test with tricky command.
   */
  public function testExec2TrickyCommand()
  {
    $strings = ['; rm -rf .. &', '"', "'"];

    foreach ($strings as $string)
    {
      $ret = ProgramExecution::exec2(['echo', $string], 'echo.txt');

      $this->assertSame(0, $ret);
      $this->assertSame($string.PHP_EOL, file_get_contents('echo.txt'));

      unlink('echo.txt');
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------