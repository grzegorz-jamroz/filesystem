<h1 align="center">iFrost Filesystem</h1>

<p align="center">
    <strong>A PHP library for handling file and folder operations.</strong>
</p>

<p align="center">
    <img src="https://img.shields.io/badge/php->=8.0-blue?colorB=%238892BF" alt="Code Coverage">  
    <img src="https://img.shields.io/badge/coverage-100%25 files|100%25 lines-brightgreen" alt="Code Coverage">   
    <img src="https://img.shields.io/badge/release-v1.0.1-blue" alt="Code Coverage">   
    <img src="https://img.shields.io/badge/license-MIT-blue?style=flat-square&colorB=darkcyan" alt="Read License">
</p>

## Installation

```
composer require grzegorz-jamroz/filesystem
```

# Development with Docker

### Build and run the containers:
```shell
docker compose up -d
```

*Note:* When working with Docker container - there is not need to create `.env` file with `SUDOER_PASSWORD=password`. 
It could be only required when working on host machine to properly run all tests.

### Copy vendor folder from container to host

```shell
docker compose cp app:/app/vendor ./vendor
```

### Run static analysis

```shell
docker compose exec app bin/fix
```

### Run tests

```shell
docker compose exec app bin/test
```

Run single test file:

```shell
docker compose exec app vendor/bin/phpunit --filter <testMethodName> <path/to/TestFile.php>
docker compose exec app vendor/bin/phpunit --filter testShouldReturnExpectedFloat tests/Unit/TransformNumeric/ToFloatTest.php
```

### Enable xdebug

```shell
docker compose exec app xdebug on
```

### Disable xdebug

```shell
docker compose exec app xdebug off
```
