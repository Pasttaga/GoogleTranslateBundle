<?php

namespace Pasttaga\GoogleTranslateBundle\Translate\Method;

use Pasttaga\GoogleTranslateBundle\Translate\Method;
use Pasttaga\GoogleTranslateBundle\Translate\MethodInterface;

/**
 * Class Languages.
 *
 * This is the class to list all language availables
 */
class Languages extends Method implements MethodInterface
{
    /**
     * @var string Google Translate API languages base url
     */
    protected $url = 'https://www.googleapis.com/language/translate/v2/languages?key={key}';

    /**
     * Retrieves all languages availables with Google Translate API
     * If a target language is specified, returns languages name translated in target language.
     *
     * @param string $target A target language to translate languages names
     *
     * @return array
     */
    public function get($target = null)
    {
        $options = ['key' => $this->apiKey];

        if (null !== $target) {
            $this->url = sprintf('%s&target={target}', $this->url);

            $options['target'] = $target;
        }

        return $this->process($options);
    }

    /**
     * Process request and retrieve JSON result.
     *
     * @param array $options
     *
     * @return array
     */
    protected function process(array $options)
    {
        $event = $this->startProfiling($this->getName(), 'get');

        $response = $this->getClient()->request('GET', $this->url, ['query' => $options]);
        $json = json_decode($response->getBody()->getContents(), true);

        $result = $json['data']['languages'] ?? [];

        $this->stopProfiling($event);

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Languages';
    }
}
