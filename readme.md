# iPS Test

To get the application running:

```bash
docker-compose up -d
```

Then set `INFUSIONSOFT_REDIRECT_URL` on `.env` file.

To run tests:

```bash
docker-compose exec ips_test_nginx vendor/bin/phpunit
```
