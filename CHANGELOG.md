# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]
### Added
...

### Changed
- Use stable version of the `console-helpers/prophecy-phpunit` dependency.

### Fixed
...

## [0.3.1] - 2024-04-10
### Added
- Added `ConsoleIO::notify` method for sending BEL symbol to the Terminal.

## [0.3.0] - 2024-03-29
### Added
- Added support for Symfony Console up to 7.x versions.
- Test suite updated to work on PHPUnit 10.

## [0.2.0] - 2023-08-17
### Changed
- Dropped support for PHP 5.4 and PHP 5.5 versions.

## [0.1.2] - 2020-12-20
### Fixed
- Updated dependencies.

## [0.1.1] - 2017-10-12
### Added
- Added `ConfigEditor::getAll` method for getting all config settings at once.

### Changed
- Updated test suite to be compatible with PHPUnit 6.x

## [0.1.0] - 2016-03-19
### Fixed
- During config setting upgrade process all settings were lost.

## [0.0.2] - 2016-03-03
### Fixed
- New config settings were not available, after application upgrade, that previously was executed at least once.

## [0.0.1] - 2015-10-29
### Added
- Initial release.
