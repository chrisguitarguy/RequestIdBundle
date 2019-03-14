# CHANGELOG

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
