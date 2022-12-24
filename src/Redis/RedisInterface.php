<?php

namespace Micro\Plugin\Redis\Redis;

interface RedisInterface
{
    /**
     * Subscribe to channels.
     *
     * Warning: this function will probably change in the future.
     *
     * @param string[]     $channels an array of channels to subscribe
     * @param string|array $callback either a string or an array($instance, 'method_name').
     * The callback function receives 3 parameters: the redis instance, the channel name, and the message.
     *
     * @return mixed|null|RedisInterface Any non-null return value in the callback will be returned to the caller or Redis if in multi mode
     *
     * @link    https://redis.io/commands/subscribe
     * @example
     * <pre>
     * function f($redis, $chan, $msg) {
     *  switch($chan) {
     *      case 'chan-1':
     *          ...
     *          break;
     *
     *      case 'chan-2':
     *                     ...
     *          break;
     *
     *      case 'chan-2':
     *          ...
     *          break;
     *      }
     * }
     *
     * $redis->subscribe(array('chan-1', 'chan-2', 'chan-3'), 'f'); // subscribe to 3 chans
     * </pre>
     */
    public function subscribe(array $channels, array|string|callable $callback): mixed;

    /**
     * @param string[] $channels
     *
     * @return mixed
     */
    public function unsubscribe(array $channels): mixed;

    /**
     * Publish messages to channels.
     *
     * Warning: this function will probably change in the future.
     *
     * @param string $channel a channel to publish to
     * @param string $message string
     *
     * @return int|RedisInterface Number of clients that received the message or Redis if in multi mode
     *
     * @link    https://redis.io/commands/publish
     * @example $redis->publish('chan-1', 'hello, world!'); // send message.
     */
    public function publish(string $channel, string $message): int|RedisInterface;

    /**
     * A command allowing you to get information on the Redis pub/sub system
     *
     * @param string       $keyword    String, which can be: "channels", "numsub", or "numpat"
     * @param string|array $argument   Optional, variant.
     *                                 For the "channels" subcommand, you can pass a string pattern.
     *                                 For "numsub" an array of channel names
     *
     * @return array|int|RedisInterface Either an integer or an array or Redis if in multi mode
     *   - channels  Returns an array where the members are the matching channels.
     *   - numsub    Returns a key/value array where the keys are channel names and
     *               values are their counts.
     *   - numpat    Integer return containing the number active pattern subscriptions
     *
     * @link    https://redis.io/commands/pubsub
     * @example
     * <pre>
     * $redis->pubsub('channels'); // All channels
     * $redis->pubsub('channels', '*pattern*'); // Just channels matching your pattern
     * $redis->pubsub('numsub', array('chan1', 'chan2')); // Get subscriber counts for 'chan1' and 'chan2'
     * $redis->pubsub('numpat'); // Get the number of pattern subscribers
     * </pre>
     */
    public function pubsub(string $keyword, string|array $argument): array|int|RedisInterface;

    /**
     * Set the string value in argument as value of the key, with a time to live.
     *
     * @param string       $key
     * @param int          $expire
     * @param string|mixed $value
     *
     * @return bool|RedisInterface returns Redis if in multi mode
     *
     * @link    https://redis.io/commands/setex
     * @example $redis->setex('key', 3600, 'value'); // sets key → value, with 1h TTL.
     */
    public function setex(string $key, int $expire, mixed $value): bool|RedisInterface;

    /**
     * Set client option
     *
     * @param int   $option option name
     * @param mixed $value  option value
     *
     * @return bool TRUE on success, FALSE on error
     *
     * @example
     * <pre>
     * $redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_NONE);        // don't serialize data
     * $redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);         // use built-in serialize/unserialize
     * $redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_IGBINARY);    // use igBinary serialize/unserialize
     * $redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_MSGPACK);     // Use msgpack serialize/unserialize
     * $redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_JSON);        // Use json serialize/unserialize
     *
     * $redis->setOption(Redis::OPT_PREFIX, 'myAppName:');                      // use custom prefix on all keys
     *
     * // Options for the SCAN family of commands, indicating whether to abstract
     * // empty results from the user.  If set to SCAN_NORETRY (the default), phpredis
     * // will just issue one SCAN command at a time, sometimes returning an empty
     * // array of results.  If set to SCAN_RETRY, phpredis will retry the scan command
     * // until keys come back OR Redis returns an iterator of zero
     * $redis->setOption(Redis::OPT_SCAN, Redis::SCAN_NORETRY);
     * $redis->setOption(Redis::OPT_SCAN, Redis::SCAN_RETRY);
     * </pre>
     */
    public function setOption(int $option, mixed $value): bool;

    /**
     * @param string $key
     * @param mixed $value
     * @param int|null $timeout
     *
     * @return bool|self
     */
    public function set(string $key, mixed $value, int $timeout = null): bool|self;

    /**
     * @param $key1
     * @param ...$otherKeys
     *
     * @return int|self
     */
    public function del($key1, ...$otherKeys): int|self;
}