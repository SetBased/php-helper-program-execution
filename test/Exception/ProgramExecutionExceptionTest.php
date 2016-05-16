<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Test\Exception;

use SetBased\Exception\ProgramExecutionException;

//----------------------------------------------------------------------------------------------------------------------
class ProgramExecutionExceptionTest extends \PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test getters.
   */
  public function test1()
  {
    $e = new ProgramExecutionException('command', 123, ['hello', 'world']);

    $this->assertSame('command', $e->getCommand());
    $this->assertSame(123, $e->getStatus());
    $this->assertSame(['hello', 'world'], $e->getOutput());
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
