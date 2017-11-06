# Robo Digipolis Code Validation

General code validation tasks for Robo Task Runner

[![Latest Stable Version](https://poser.pugx.org/digipolisgent/robo-digipolis-code-validation/v/stable)](https://packagist.org/packages/digipolisgent/robo-digipolis-code-validation)
[![Latest Unstable Version](https://poser.pugx.org/digipolisgent/robo-digipolis-code-validation/v/unstable)](https://packagist.org/packages/digipolisgent/robo-digipolis-code-validation)
[![Total Downloads](https://poser.pugx.org/digipolisgent/robo-digipolis-code-validation/downloads)](https://packagist.org/packages/digipolisgent/robo-digipolis-code-validation)
[![License](https://poser.pugx.org/digipolisgent/robo-digipolis-code-validation/license)](https://packagist.org/packages/digipolisgent/robo-digipolis-code-validation)

[![Build Status](https://travis-ci.org/digipolisgent/robo-digipolis-code-validation.svg?branch=develop)](https://travis-ci.org/digipolisgent/robo-digipolis-code-validation)
[![Maintainability](https://api.codeclimate.com/v1/badges/b861f4f06062f6badd35/maintainability)](https://codeclimate.com/github/digipolisgent/robo-digipolis-code-validation/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/b861f4f06062f6badd35/test_coverage)](https://codeclimate.com/github/digipolisgent/robo-digipolis-code-validation/test_coverage)
[![PHP 7 ready](https://php7ready.timesplinter.ch/digipolisgent/robo-digipolis-code-validation/develop/badge.svg)](https://travis-ci.org/digipolisgent/robo-digipolis-code-validation)

## Commands

This package provides default commands which you can use in your `RoboFile.php`
like so:

```php
class RoboFile extends \Robo\Tasks
{
    use \DigipolisGent\Robo\Task\CodeValidation\Commands\loadCommands;
}
```

### digipolis:php-cs

`vendor/bin/robo digipolis:php-cs [OPTIONS]`

#### Options

##### --dir

The directory containing the code to validate.

##### --standard

The coding standard to validate the code against.

##### --extensions

The file extensions of the files to validate.

##### --ignore

Comma separated list of files/directories to ignore.

##### --report-type

The report type to generate (e.g. 'checkstyle' or 'json').

##### --report-file

The file to output the generated report in.

### digipolis:php-md

`vendor/bin/robo digipolis:php-md [OPTIONS]`

#### Options

##### --dir

The directory containing the code to check.

##### --format

The format to output the code in (xml, html or text).

##### --extensions

The file extensions of the files to check.

##### --ignore

Comma separated list of files/directories to ignore.

##### --minimum-priority

The rule priority threshold; rules with lower priority than this will not be
used.

##### --report-file

The file to output the generated report in.

##### --rulesets

A comma-separated string of ruleset filenames.
