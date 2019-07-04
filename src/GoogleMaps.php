<?php namespace GoogleMaps;

/**
 * Description of GoogleMaps
 *
 * @author Alexander Pechkarev <alexpechkarev@gmail.com>
 */
class GoogleMaps extends WebService{

    /**
     * Array of classes to handle web service request
     * By default WebService class will be used
     * @var string[]
     */
    protected $webServices = [
        'directions' => Directions::class,
    ];

    /**
     * Bootstrapping Web Service
     *
     * @param string $service
     * @return \GoogleMaps\WebService
     * @throws \ErrorException
     */
    public function load($service) {

        // is overwrite class specified
        $class = array_key_exists($service, $this->webServices)
            ? new $this->webServices[$service]
            : $this;

        $class->build($service);

        return $class;
    }
}
