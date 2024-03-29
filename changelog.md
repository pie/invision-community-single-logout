# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [0.4.1] 2023-08-10 
### Changed
- Moved from add_rewrite_rule to a hard Redirect in htaccess

## [0.4.0] 2023-08-09 
### Added
- Rewrite rule to catch /community/logout so that .htaccess entry no longer required
### Changed
- Factored community path into a constant
- Factored internal identifier to use sanitised namespace

## [0.3.1] 2023-08-04 
### Fixed
- Improper Changelog updates

## [0.3.0] 2023-08-04 
### Added
- Separate upgrade and installation artifacts

## [0.2.1] 2023-08-04 
### Added
- Functionality to log user out of IPB on a simple WP logout
### Fixed
- Bug causing fatal error in de-activation
- Incorrect require path set
- Incorrect directory structure for deploy artifact

## [0.1.0] 2023-08-04 
### Added
- Support for native WordPress updates
- Added workflow steps to create zip

## [0.0.1]
### Added
- First commit
