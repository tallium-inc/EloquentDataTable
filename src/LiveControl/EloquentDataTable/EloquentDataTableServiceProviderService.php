<?php
namespace LiveControl\EloquentDataTable;

class EloquentDataTableServiceProviderService extends \Illuminate\Auth\AuthManager
{
    public function createUserProvider($provider)
    {
        $config = $this->app['config']['auth.providers.'.$provider];

        if (isset($this->customProviderCreators[$config['driver']])) {
            return call_user_func(
                $this->customProviderCreators[$config['driver']], $this->app, $config
            );
        }

        switch ($config['driver']) {
            case 'database':
                return $this->createDatabaseProvider($config);
            case 'eloquent':
                return new EloquentUserProviderProvider($this->app['hash'], $config['model']);
            default:
                throw new InvalidArgumentException("Authentication user provider [{$config['driver']}] is not defined.");
        }
    }
}