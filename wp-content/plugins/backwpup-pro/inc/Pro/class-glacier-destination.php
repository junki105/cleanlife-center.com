<?php

/**
 * Class BackWPup_Pro_Glacier_Destination
 */
class BackWPup_Pro_Glacier_Destination
{

    /**
     * @var array
     */
    private $options;

    /**
     * BackWPup_Pro_Glacier_Destination constructor.
     *
     * @param array $options
     */
    private function __construct(array $options)
    {
        $defaults = array(
            'label' => __('Custom Glacier destination', 'backwpup'),
            'region' => '',
            'version' => 'latest',
            'signature' => 'v4',
        );

        $this->options = array_merge($defaults, $options);
    }

    /**
     * Get list of S3 destinations.
     *
     * This list can be extended by using the `backwpup_glacier_destination` filter.
     *
     * @return array
     */
    public static function options()
    {
        $options = BackWPup_S3_Destination::options();
        foreach ($options as $id => $option) {
            if ( ! empty($option['endpoint'])) {
                continue;
            }
            $options[$id] = array(
                'label' => $option['label'],
                'region' => $option['region'],
            );
        }

        return apply_filters('backwpup_glacier_destination', $options);
    }

    /**
     * Get the AWS destination of the passed id or base url.
     *
     * @param string $id Destination id
     *
     * @return self
     */
    public static function fromOption($id)
    {
        $destinations = self::options();
        if (array_key_exists($id, $destinations)) {
            return new self($destinations[$id]);
        }

        return new self(array('region' => $id));
    }


    /**
     * Get the Amazon Glacier Client
     *
     * @param $accessKey
     * @param $secretKey
     *
     * @return \Aws\Glacier\GlacierClient
     */
    public function client($accessKey, $secretKey)
    {

        $glacierOptions = array(
            'signature' => $this->signature(),
            'credentials' => array(
                'key' => $accessKey,
                'secret' => BackWPup_Encryption::decrypt($secretKey),
            ),
            'region' => $this->region(),
            'http' => array(
                'verify' => BackWPup::get_plugin_data('cacert'),
            ),
            'version' => $this->version(),
        );

        $glacierOptions = apply_filters('backwpup_glacier_client_options', $glacierOptions);

        return new \Aws\Glacier\GlacierClient($glacierOptions);
    }


    /**
     * The label of the destination
     * @return string
     */
    public function label()
    {
        return $this->options['label'];
    }

    /**
     * The region of the destination
     * @return string
     */
    public function region()
    {
        return $this->options['region'];
    }

    /**
     * The s3 version for the api like '2006-03-01'
     * @return string
     */
    public function version()
    {
        return $this->options['version'];
    }

    /**
     * The signature for the api like 'v4'
     * @return string
     */
    public function signature()
    {
        return $this->options['signature'];
    }
}