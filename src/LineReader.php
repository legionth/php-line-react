<?php

namespace Legionth\React\LineReader;

use Evenement\EventEmitter;
use React\Stream\ReadableStreamInterface;
use React\Stream\WritableStreamInterface;
use React\Stream\Util;

/**
 * Read the stream line wise. This class is a duplex stream so a writeable and readable 
 * stream at the same time 
 */
class LineReader extends EventEmitter implements ReadableStreamInterface, WritableStreamInterface
{
    private $closed = false;
    private $buffer = '';
    
    /**
     * Reads the incomg data until the new line delimiter occures, 
     * if there is no delimiter in the chunk the data will be buffered until 
     * the next data chunk comes in
     *
     * @param string $chunk - string entered by the input stream
     */
    public function write($chunk)
    {
        for ($i = 0; $i < strlen($chunk); $i++) {
            $this->buffer .= $chunk[$i];
        
            if ($chunk[$i] == PHP_EOL) {
                $this->emit('data', array($this->buffer));
                $this->buffer = '';
            }
        }
    }

    public function end($data = null)
    {
        $this->close();
    }

    public function isReadable()
    {
        return !$this->closed;
    }

    public function pause()
    {
        return;
    }

    public function resume()
    {
        return;
    }

    public function pipe(WritableStreamInterface $dest, array $options = array())
    {
        Util::pipe($this, $dest, $options);

        return $dest;
    }

    public function close()
    {
        if ($this->closed) {
            return;
        }

        $this->closed = true;
        $this->started = false;

        $this->emit('close');
        $this->removeAllListeners();
    }
    
    public function isWritable()
    {
        return !$this->closed;
    }
}
