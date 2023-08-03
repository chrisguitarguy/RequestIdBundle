# Request ID Bundle

This adds request ID's to your Symfony application. Why? It's a great way to add
some additional information to logs and to present to users. For example, if an
exception is thrown you'll be able to show the user the request ID which they
can pass on to you to locate their specific issue.

## Installation

Use [Composer](https://getcomposer.org/).

```
composer require chrisguitarguy/request-id-bundle
```
And one of the UUIDv4 generator libraries: 
```
composer require ramsey/uuid
```
```
composer require symfony/uid
```


Then enable the bundle in your `AppKernel`.

```php
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            // ...
            new Chrisguitarguy\RequestId\ChrisguitarguyRequestIdBundle(),
        ];

        // ...

        return $bundles;
    }

    // ...
}
```

## Configuration

```yaml
# in app/config/config.yml

chrisguitarguy_request_id:
    # The header which the bundle inspects for the incoming request ID
    # if this is not set an ID will be generated and set at this header
    request_header: Request-Id

    # Whether or not to trust the incoming request header. This is turned
    # on by default. If true a value in the `Request-Id` header in the request
    # will be used as the request ID for the rest of the request. If false
    # those values are ignored.
    trust_request_header: true

    # The header which the bundle will set the request ID to on
    # the response
    response_header: Request-Id

    # The service key of an object that implements
    # Chrisguitarguy\RequestId\RequestIdStorage
    # optional, defaults to `SimpleIdStorage`
    storage_service: ~

    # The service key of an object that implements
    # Chrisguitarguy\RequestId\RequestIdGenerator
    # optional, defaults to a Ramsey's UUID v4 based generator
    generator_service: ~

    # Whether or not to add the monolog process (see below), defaults to true
    enable_monolog: true

    # Whether or not to add the twig extension (see below), defaults to true
    enable_twig: true
```

## How it Works

When a request comes in, it's inspected for the `Request-Id` header. If present,
the value in that header will be used throughout the rest of the bundle. This
lets you use request ID's from somewhere higher up in the stack (like in the web
server itself).

If no request ID is found, one is generated by the `RequestIdGenerator`. The
default generator creates version 4 UUIDs.

On the way out out, the `Request-Id` header is set on the response as well using
the value described above.

The headers are configurable. See the [configuration](#configuration) above.

## Monolog Integration

There's a monolog *Processor* that adds the request ID to `extra` array on the
record. This can be turned off by setting `enable_monolog` to `false` in the
configuration.

To use the request ID in your logs, include `%extra.request_id%` in your
formatter. Here's a configuration example from this bundle's tests.

```yaml
# http://symfony.com/doc/current/cookbook/logging/monolog.html#changing-the-formatter

services:
    request_id_formatter:
        class: Monolog\Formatter\LineFormatter
        arguments:
            - "[%%level_name%% - %%extra.request_id%%] %%message%%"

monolog:
    handlers:
        file:
            type: stream
            level: debug
            formatter: request_id_formatter
```

## Twig Integration

**Important**: Twig ^2.7  or ^3.0 is required for the twig integration to work.

By default this bundle will add a global `request_id` function to your twig
environment. To disable this set `enable_twig` to `false` in the bundle
configuration.

Here's an example of a template.

```html
<!DOCTYPE html>
<html>
    <head>
        <title>Hello, World</title>
    </head>
    <body>
        <h1>{{ request_id() }}</h1>
    </body>
</html>
```

## License

MIT. See the LICENSE file.
