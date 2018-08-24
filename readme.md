# iPS Test

To get the application running:

```bash
docker-compose up -d
```

To run tests:

```bash
docker-compose exec ips_test_nginx vendor/bin/phpunit
```
