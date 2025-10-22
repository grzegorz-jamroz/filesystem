<h1 align="center">iFrost Filesystem</h1>

<p align="center">
    <strong>A PHP library for handling file and folder operations.</strong>
</p>

<p align="center">
    <img src="https://img.shields.io/badge/php->=8.0-blue?colorB=%238892BF" alt="Code Coverage">  
    <img src="https://img.shields.io/badge/coverage-100%25-brightgreen" alt="Code Coverage">   
    <img src="https://img.shields.io/badge/release-v1.0.0-blue" alt="Code Coverage">   
    <img src="https://img.shields.io/badge/license-MIT-blue?style=flat-square&colorB=darkcyan" alt="Read License">
</p>

## Installation

```
composer require grzegorz-jamroz/filesystem
```

# Development with Docker

### Create .env file:
```shell
cp .env-example .env
```

### Build and run the containers:
1.  Create .env file:
    ```shell
    cp .env-example .env
    ```

2.  Run Docker containers in detached mode:
    ```shell
    docker compose up -d
    ```

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
