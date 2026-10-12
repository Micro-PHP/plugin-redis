# Micro Framework

Documentation is available [here](https://micro-php.net/docs). If not, we will be grateful if you can become its author :)

## Redis authentication

Configure credentials per client (replace `DEFAULT` with the uppercase client name):

```dotenv
REDIS_DEFAULT_AUTH_USERNAME=default
REDIS_DEFAULT_AUTH_PASSWORD=your-redis-password
```

The username defaults to Redis's `default` user. Redis has no built-in default
password: use the password configured on the server with `requirepass` or ACLs.
When only a password is configured, the plugin uses password-only `AUTH`, which
also supports Redis versions before 6. Set a username to authenticate as a named
ACL user on Redis 6 or later.

Leave the password unset for Redis without authentication. An explicitly configured
empty password is sent as an empty password. Authentication runs after connecting
over TCP or a Unix socket, including persistent connections. Invalid credentials
cause client creation to fail.
