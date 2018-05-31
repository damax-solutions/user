# Development

Build image:

```bash
$ docker build -t damax-user .
```

Install dependencies:

```bash
$ docker run --rm -v $(pwd):/app -w /app damax-user composer install
```

Fix php coding standards:

```bash
$ docker run --rm -v $(pwd):/app -w /app damax-user ./vendor/bin/php-cs-fixer fix

```

Running tests:

```bash
$ docker run --rm -v $(pwd):/app -w /app damax-user ./vendor/bin/simple-phpunit
$ docker run --rm -v $(pwd):/app -w /app damax-user ./bin/phpunit-coverage
```
