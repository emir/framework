<?php

/**
 * Linna Framework.
 *
 * @author Sebastian Rapetti <sebastian.rapetti@alice.it>
 * @copyright (c) 2017, Sebastian Rapetti
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Linna\Http;

/**
 * Contains shortcuts for methods for route mapping.
 */
trait FastMapTrait
{
    /**
     * Express Requirements by Abstract Methods.
     *
     * @param array $route
     */
    abstract public function map(array $route);

    /**
     * Map a route in to router.
     *
     * @param string   $url
     * @param callable $callback
     * @param array    $options
     */
    public function get(string $url, callable $callback, array $options = [])
    {
        $this->map($this->createRouteArray('GET', $url, $callback, $options));
    }

    /**
     * Map a route in to router.
     *
     * @param string   $url
     * @param callable $callback
     * @param array    $options
     */
    public function post(string $url, callable $callback, array $options = [])
    {
        $this->map($this->createRouteArray('POST', $url, $callback, $options));
    }

    /**
     * Map a route in to router.
     *
     * @param string   $url
     * @param callable $callback
     * @param array    $options
     */
    public function put(string $url, callable $callback, array $options = [])
    {
        $this->map($this->createRouteArray('PUT', $url, $callback, $options));
    }

    /**
     * Map a route in to router.
     *
     * @param string   $url
     * @param callable $callback
     * @param array    $options
     */
    public function patch(string $url, callable $callback, array $options = [])
    {
        $this->map($this->createRouteArray('PATCH', $url, $callback, $options));
    }

    /**
     * Map a route in to router.
     *
     * @param string   $url
     * @param callable $callback
     * @param array    $options
     */
    public function delete(string $url, callable $callback, array $options = [])
    {
        $this->map($this->createRouteArray('DELETE', $url, $callback, $options));
    }

    /**
     * Create route array for previous methods.
     *
     * @param string   $method
     * @param string   $url
     * @param callable $callback
     * @param array    $options
     *
     * @return array
     */
    protected function createRouteArray(string $method, string $url, callable $callback, array $options) : array
    {
        $routeArray = (new Route([
            'method'   => $method,
            'url'      => $url,
            'callback' => $callback,
        ]))->toArray();

        $route = array_replace_recursive($routeArray, $options);

        return $route;
    }
}
