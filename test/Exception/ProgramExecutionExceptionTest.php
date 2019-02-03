<?php
declare(strict_types=1);

namespace SetBased\Test\Exception;

use PHPUnit\Framework\TestCase;
use SetBased\Exception\ProgramExecutionException;

/**
 * Test cases for ProgramExecutionException.
 */
class ProgramExecutionExceptionTest extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test getters.
   */
  public function test1(): void
  {
    $e = new ProgramExecutionException('command', 123, ['hello', 'world']);

    $this->assertSame('command', $e->getCommand());
    $this->assertSame(123, $e->getStatus());
    $this->assertSame(['hello', 'world'], $e->getOutput());
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
