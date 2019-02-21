# Upgrade from 2.X to 3.X

Version 2 and 3 are *functionally the same*, the only new things brought to 3.X
are support for symfony 4.X and bumped dependency requirements.

## PHP Version Requirement Bumped to 7.2+

Stick with version 2.X should PHP 5 support still be required.

## Dropped Support for Symfony 2.X

Stick with v2 of this bundle should symfony 2 support still be required

## Only Symfony ~3.4 and ~4.0 Supported

3.4 is the latest long term service release. Stick with v2 of this bundle
should support for symfony 3.0 through 3.3 still be required.

## DI Services Renamed

- `chrisguitarguy.requestid.generator` renamed to `Chrisguitarguy\RequestId\RequestIdGenerator`
- `chrisguitarguy.requestid.storage` renamed to `Chrisguitarguy\RequestId\RequestIdStorage`

Those are the only two public services available for this bundle. Additionally,
services registered with `storage_service` or `generator_service` in the bundle
configuration are also available at the namespaced `RequestIdStorage` and
`RequestIdGenerator` aliases as well.

These change should be autowiring work with this bundle's services.

## Stricter Typing

The `RequestIdGenerator` and `RequestIdStorage` interfaces are now more strict.

#### `RequestIdGenerator`

```diff
use Chrisguitarguy\RequestId\RequestIdGenerator;

class SomeGenerator implements RequestIdGenerator
{
-   public function generate()
+   public function generate() : string
    {
        // ...
    }
}
```

#### `RequestIdStorage`

```diff
use Chrisguitarguy\RequestId\RequestIdStorage;

class SomeStorage implements RequestIdStorage
{
-   public function getRequestId()
+   public function getRequestId() : ?string
    {
        // ...
    }

-   public function setRequestId($id)
+   public function setRequestId(?string $id) : void
    {
        // ...
    }
}
```
