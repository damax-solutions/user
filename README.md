## Development

Build image:

```bash
$ docker build -t damax-user/php .
```

Fix php coding standards:

```bash
$ docker run --rm --user $(id -u):$(id -g) -v $(pwd):/app -w="/app" herloct/php-cs-fixer fix
```

Update dependencies:

```bash
$ docker run --rm -v $(pwd):/app -w="/app" composer update
```

Running tests:

```bash
$ docker run --rm -v $(pwd):/app -w="/app" damax-user/php ./bin/simple-phpunit
```
