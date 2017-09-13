<?php
use React\Stream\ReadableStream;
use Legionth\React\LineReader\LineReader;

class LineReaderTest extends TestCase
{
    public function setUp()
    {
        $this->lineReader = new LineReader();

        $this->input = new ReadableStream();
        $this->input->pipe($this->lineReader);
    }

    public function testMethodIsCalled()
    {
        $this->lineReader->on('data', $this->expectCallableOnceWith('hello' . PHP_EOL));
        $this->input->emit('data', array(
            'hello' . PHP_EOL
        ));
    }

    public function testSeperatedData()
    {
        $this->lineReader->on('data', $this->expectCallableOnceWith('helloworld' . PHP_EOL));

        $this->input->emit('data', array(
            'hello'
        ));
        $this->input->emit('data', array(
            'world' . PHP_EOL
        ));
    }

    public function testWithLineBreak()
    {
        $this->lineReader->on('data', $this->expectCallableOnceWith('hello' . PHP_EOL));

        $this->input->emit('data', array(
            'hello' . PHP_EOL . 'world'
        ));
    }

    public function testDoubleLineBreak()
    {
        $expectedValues = array(
            'hello' . PHP_EOL,
            'world' . PHP_EOL
        );
        $this->lineReader->on('data', $this->expectCallableConsecutive(2, $expectedValues));

        $this->input->emit('data', array(
            'hello' . PHP_EOL . 'world' . PHP_EOL
        ));
    }

    public function testPauseStream()
    {
        $parser = new LineReader();
        $parser->pause();
    }

    public function testResumeStream()
    {
        $parser = new LineReader();
        $parser->pause();
        $parser->resume();
    }

    public function testPipeStream()
    {
        $dest = $this->getMockBuilder('React\Stream\WritableStreamInterface')->getMock();

        $ret = $this->lineReader->pipe($dest);

        $this->assertSame($dest, $ret);
    }

    public function testEnd()
    {
        $lineReader = new LineReader();
        $lineReader->end();
    }

    public function testClose()
    {
        $lineReader = new LineReader();
        $lineReader->close();
    }

    public function testIsReadable()
    {
        $lineReader = new LineReader();
        $actual = $lineReader->isReadable();

        $this->assertTrue($actual);
    }

    public function testIsWritable()
    {
        $lineReader = new LineReader();
        $actual = $lineReader->isWritable();

        $this->assertTrue($actual);
    }

    public function testStreamIsAlreadyClosed()
    {
        $lineReader = new LineReader();
        $lineReader->close();
        $lineReader->close();
    }

    private function expectCallableConsecutive($numberOfCalls, array $with)
    {
        $mock = $this->createCallableMock();

        for ($i = 0; $i < $numberOfCalls; $i ++) {
            $mock->expects($this->at($i))
                ->method('__invoke')
                ->with($this->equalTo($with[$i]));
        }

        return $mock;
    }
}
