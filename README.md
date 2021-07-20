# Artemeon Stream-Contexts

PHP-Interface for the internal PHP stream context's

## Description

Contains several PHP stream context objects to provide an easier interface for the internal 
wrapper based PHP contexts options. Provides a FileStream configuration object, this allows us to transparent 
change the streams without to change the client class.

## Getting Started

### Dependencies

* We use the https://phpseclib.com/ to user SFTP based on custom StreamWrapper provided by this library.
* In most PHP distributions SFTP support is not compiles in.

### Installing
```
"require": {
  "artemeon/stream-context": "^0.1.0"
}
```

### Usage
Basic usage wit all PHP stream aware functions
```
$directory = dir("sftp://ftp.example.com/file.txt", SftpStreamContext::forPasswordAuthentication('password', 'user')->createStreamContext());
```

Usage with the FileObjectFactory and the FileStream object
```
$fileObject = FileObjectFactory::create(FileStream::fromUrl('sftp://test.de/basefolder', SftpStreamContext::forPrivateKeyAuthentication('Aefs566456DG_fgdf')));
$fileObject = FileObjectFactory::create(FileStream::fromUrl('http://test.de/basefolder', HttpStreamContext::forPostUrlencoded(['type' => 'internal'])));
```

Set several Context and FileStream options  
```
$httpContext = HttpStreamContext::forGet();
$httpContext->setTimeout(5.0);

$fileStream = FileStream::fromUrl('http://test.de/basefolder/import.csv', $httpContext);
$fileStream->setMode('r+');
$fileStream->enforceFileExtension('csv');

$file = FileObjectFactory::create($fileStream);

```

## Version History

* 0.1.0 Initial release
 
## License

This project is licensed under the MIT License - see the LICENSE file for details
