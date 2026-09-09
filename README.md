# tribepeer/sdk (PHP)

PHP client for TribePeer — e-campus, organisation training, e-library, and AI-powered learning.

```bash
composer require tribepeer/sdk
```

Until Packagist is live, point Composer at this repo:

```json
{
  "repositories": [{ "type": "vcs", "url": "https://github.com/devadu001/tribepeer-php" }]
}
```

## Keys

1. Create an account — [tribepeer.com/register](https://www.tribepeer.com/register)
2. Become a Tribe Owner — [tribepeer.com/tribe-owner/apply](https://www.tribepeer.com/tribe-owner/apply)
3. Issue keys — [tribepeer.com/tribe-owner/credentials](https://www.tribepeer.com/tribe-owner/credentials)
4. API guide — [tribepeer.com/institutions/docs](https://www.tribepeer.com/institutions/docs)

You get a **client id** (`tp_id_…`) and a **client secret** (`tp_sec_…`). The secret stays on your server.

```env
TP_CLIENT_ID=tp_id_…
TP_CLIENT_SECRET=tp_sec_…
```

```php
$tp = new TribePeer\Client(
    clientId: env('TP_CLIENT_ID'),
    clientSecret: env('TP_CLIENT_SECRET'),
);

$classes = $tp->tribes()->list();
```

PHP 8.1+. MIT.
