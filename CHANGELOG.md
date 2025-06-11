# CHANGELOG

## 7.0.0

- Support PHP 8.4
- Dropped PHP 8.2 Support
- Dropped Symfony 5.X and <6.4 support

## 6.0.0

- Dropped support for Symfony 4.4.X (thanks @arnedesmedt)
- Made `SimpleIdStorage` Resetable (thanks @tourze)

## 5.1.0

- Added support for Monolog 3.X

## 5.0.0

- Dropped support for Symfony for Symfony 5 less than 5.4
- Added support for Symfony 6.X

## 4.2.1

- Fixed deprecation warnings from `KernelEvent::isMasterRequest` with Symfony 5.3

## 4.2.0

- Dropped PHP 7.3 support
- Added PHP 8.0 support

## 4.1.0 

- Added support for `ramsey/uuid` 4.X
- Dropped PHP 7.2 support
- Added a `conflict` section to `composer.json` to avoid issues with users
  working with unsupported twig versions.

## 4.0.0

- Symfony 3.4 is no longer supported
- Symfony 4.4 and 5.X are now required

## 3.0.2

Bug Fixes

- Fixed deprecation notices from Twig
- Fixed deprecation notices from Symfony in tests

## 3.0.1

Bug Fixes

- Increased the priority of the response listener so it runs before Symfony's
  profiler.
- The bundle now actually uses the configured response header.

## 3.0.0

New Features:

- Support for Symfony 4.X!

BC Breaks:

- Removed support for Symfony 2.X
- Minimum Symfony 3 version is now 3.4 (the latest LTS)
- DI services have been reworked:
    - All services are named after their class names
    - Only the RequestIdStorage and RequestIdGenerator services are public
- Stricter typing everywhere, the two biggest impacts being the public services
    - `RequestIdGenerator` now has return types
    - `RequestIdStorage` also has return types and argument types


## 2.0.0

BC Breaks:

- Removed support for ramsey/uuid 2.X (and rhumsaa/uuid)
- Bumped the minimum required Symfony version to 2.8

Bug Fixes:

- None

New Features:

- None
