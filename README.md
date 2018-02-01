## Development

Build image:

```bash
$ docker build -t damax-user/php .
```

Install dependencies:

```bash
$ docker run --rm -v $(pwd):/app -w="/app" damax-user/php composer install
```

Fix php coding standards:

```bash
$ docker run --rm -v $(pwd):/app -w="/app" damax-user/php ./vendor/bin/php-cs-fixer fix
```

Running tests:

```bash
$ docker run --rm -v $(pwd):/app -w="/app" damax-user/php ./vendor/bin/simple-phpunit
$ docker run --rm -v $(pwd):/app -w="/app" damax-user/php ./bin/phpunit-coverage
```
