# NYPL Patron Service (DEPRECATED)

Note: This repo has been deprecated. See [PatronService V2](https://github.com/NYPL/patron-service-v2/)

[![Build Status](https://travis-ci.org/NYPL/patron-service.svg?branch=master)](https://travis-ci.org/NYPL/patron-service)
[![Coverage Status](https://coveralls.io/repos/github/NYPL/patron-service/badge.svg?branch=travis)](https://coveralls.io/github/NYPL/patron-service?branch=travis)

This package is intended to be used as a Lambda-based Node.js/PHP Patron Service using the 
[NYPL PHP Microservice Starter](https://github.com/NYPL/php-microservice-starter).

This package adheres to [PSR-1](http://www.php-fig.org/psr/psr-1/), 
[PSR-2](http://www.php-fig.org/psr/psr-2/), and [PSR-4](http://www.php-fig.org/psr/psr-4/) 
(using the [Composer](https://getcomposer.org/) autoloader).

## Requirements

* Node.js >=6.0
* PHP >=7.0 
  * [pdo_pdgsql](http://php.net/manual/en/ref.pdo-pgsql.php)

Homebrew is highly recommended for PHP:
  * `brew install php71`
  

## Installation

1. Clone the repo.
2. Install required dependencies.
   * Run `npm install` to install Node.js packages.
   * Run `composer install` to install PHP packages.
   * If you have not already installed `node-lambda` as a global package, run `npm install -g node-lambda`.
3. Setup [configuration files](#configuration).
   * Copy the `.env.sample` file to `.env`.
   * Copy `config/var_env.sample` to `config/var_development.env`.
4. Replace sample values in `.env` and `config/var_development.env`.

## Configuration

Various files are used to configure and deploy the Lambda.

### .env

`.env` is used *locally* for two purposes:

1. By `node-lambda` for deploying to and configuring Lambda in *all* environments. 
   * You should use this file to configure the common settings for the Lambda 
   (e.g. timeout, Node version). 
2. To set local environment variables so the Lambda can be run and tested in a local environment.
   These parameters are ultimately set by the [var environment files](#var_environment) when the Lambda is deployed.

### package.json

Configures `npm run` commands for each environment for deployment and testing. Deployment commands may also set
the proper AWS Lambda VPC, security group, and role.
 
~~~~
"scripts": {
    "deploy-development": "node-lambda deploy -e development -f config/development.env -S config/event_sources_development.json -b {subnet} -sg {security-group}",
    "deploy-qa": "node-lambda deploy -e qa -f config/var_qa.env -S config/event_sources_qa.json -b {subnet} -sg {security-group}",
    "deploy-production": "node-lambda deploy -e production -f config/var_production.env -S config/event_sources_production.json -b {subnet} -sg {security-group}",
    "test-patron": "node-lambda run -j tests/events/patron.json -x tests/events/context.json"
},
~~~~

### config/var_app

Configures environment variables common to *all* environments.

### config/var_*environment*.env

Configures environment variables specific to each environment.

### config/event_sources_*environment*

Configures Lambda event sources (triggers) specific to each environment.

## Usage

### Process a Lambda Event

To use `node-lambda` to process the sample API Gateway event in `event.json`, run:

~~~~
npm run test-patron
~~~~

### Run as a Web Server

To use the PHP internal web server, run:

~~~~
php -S localhost:8888 -t . index.php
~~~~

You can then make a request to the Lambda: `http://localhost:8888/api/v0.1/patrons`.

### Swagger Documentation Generator

Create a Swagger route to generate Swagger specification documentation:

~~~~
$service->get("/swagger", Swagger::class});
~~~~

## Deployment

Before deploying, ensure [configuration files](#configuration) have been properly set up:

1. Copy `config/var_env.sample` to `config/development.env`, `config/var_qa.env`, and `config/var_production.env`.
   *  Verify environment variables are correct.
2. Verify `.env` has correct settings for deployment.
3. Verify `package.json` has correct command-line options for security group, VPC, and role (if applicable).
4. Verify `config/event_sources_development.json`, `config/event_sources_qa.json`, `config/event_sources_production.json` have proper event sources.

To deploy to an environment, run the corresponding command. For example:

~~~~
npm run deploy-development
~~~~
