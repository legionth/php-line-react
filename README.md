# legionth/line-react

Line reader stream to forward complete lines to a stream.

**Table of Contents**
* [Usage](#usage)
 * [LineReader](#linereader)
* [License](#license)

## Usage

### LineReader

The center of this project is the `LineReader` class.
This class checks incoming data for an new line delimiter, if the delimeter has been found in the current data chunk the data will be exposed on the stream.
When the new line delimeter isn't found the data chunk,
it will be buffered until a delimiter occures.
The `LineReader` is a duplex stream, so a readable and writable stream at the same time. 

Checkout `examples` how you can use this project.

```
$ cat words.txt | php examples/readLine.php
```

This example makes clear what example this project can be used for.
Every line with a new line delimiter will put into the readable stream.
Every other line without a new line delimiter will be buffered until another occures.

The first parameter of the `LineReader` is the custom delimiter.
The default value of this delimiter is `PHP_EOL`.

## Install

The recommended way to install this library is [through Composer](https://getcomposer.org).
[New to Composer?](https://getcomposer.org/doc/00-intro.md)

This will install the latest supported version:

```bash
$ composer require legionth/line-react:^0.2
```

See also the [CHANGELOG](CHANGELOG.md) for details about version upgrades.

## License

MIT
