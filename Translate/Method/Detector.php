<?php

namespace Pasttaga\GoogleTranslateBundle\Translate\Method;

use Pasttaga\GoogleTranslateBundle\Exception\UnableToDetectException;
use Pasttaga\GoogleTranslateBundle\Translate\Method;
use Pasttaga\GoogleTranslateBundle\Translate\MethodInterface;

/**
 * Class Translator.
 *
 * This is the class to detect language used for a given text
 */
class Detector extends Method implements MethodInterface
{
    /**
     * Undefined language Google Translate API detector value constant.
     */
    const UNDEFINED_LANGUAGE = 'und';

    /**
     * @var string Google Translate API detector url
     */
    protected $url = 'https://www.googleapis.com/language/translate/v2/detect';

    /**
     * Detect language used for query text given via the Google Translate API.
     *
     * @param string $query A text to detect language
     *
     * @return string|null
     */
    public function detect($query)
    {
        $options = [
            'key' => $this->apiKey,
            'q' => $query,
        ];

        return $this->process($options);
    }

    /**
     * Process request and retrieve JSON result.
     *
     * @param array $options
     *
     * @throws UnableToDetectException
     *
     * @return string|null
     */
    protected function process(array $options)
    {
        $result = null;

        $client = $this->getClient();

        $event = $this->startProfiling($this->getName(), $client->getConfig('query'));

        $response = $client->request('GET', $this->url, ['query' => $options]);
        $json = json_decode($response->getBody()->getContents(), true);

        if (isset($json['data']['detections'])) {
            $current = current(current($json['data']['detections']));
            $result = $current['language'];

            if (self::UNDEFINED_LANGUAGE == $result) {
                throw new UnableToDetectException('Unable to detect language');
            }
        }

        $this->stopProfiling($event);

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Detector';
    }
}
