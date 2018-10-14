GoogleTranslateBundle
=====================

Features
--------

 * Detect language used for a string
 * Translate a string from a source language to a target one
 * Translate a string into a target language by using language auto-detection (consume 1 more API call)
 * Retrieve all languages available on API and obtain language names in a given language
 * Profile detector / translate / languages list API calls in the Symfony profiler!

Installation
------------

Add the bundle to your `composer.json` file:

```json
{
    "require" :  {
        "pasttaga/googletranslatebundle": "dev-master"
    }
}
```

Add this to app/AppKernel.php

```php
<?php
    public function registerBundles()
    {
        return [
            ...
            Pasttaga\GoogleTranslateBundle\PasttagaGoogleTranslateBundle::class => ['all' => true],
        ];

        ...

        return $bundles;
    }
```

Configuration
-------------

### Edit app/config.yml

The following configuration lines are required:

```yaml
pasttaga_google_translate:
    api_key: <your key api string>
```

Usages
------

### Detect a string language

Retrieve the detector service and call the `detect()` method:

```php
// Use Autowiring
public function myFunction(Detector $detector)
{
    $value = $detector->detect('Hi, this is my string to detect!');
    // This will return 'en'
```

### Translate a string

Retrieve the translator service and call the `translate()` method:

```php
// Use Autowiring
public function myFunction(Translator $translator)
{
    $value = $translator->translate('Hi, this is my text to detect!', 'fr', 'en');
    // This will return 'Salut, ceci est mon texte à détecter!'
```

### Translate a string from unknown language (use detector)

Retrieve the translator service and call the `translate()` method without the source (third) parameter:

```php
$value = $translator->translate('Hi, this is my text to detect!', 'fr');
// This will return 'Salut, ceci est mon texte à détecter!'
```

### Translate multiple strings

Retrieve the translator service and call the `translate()` method with an array of your strings:

```php
$value = $translator->translate(array('Hi', 'This is my second text to detect!'), 'fr', 'en');
// This will return the following array:
// array(
//     0 => 'Salut',
//     1 => 'Ceci est mon second texte à détecter !',
// )
```

Note that you can also use an "economic mode" to translate multiple strings in a single request which is better for your application performances.

Your translations will be concatenated in one single Google request. To use it, simply add `true` to the last argument:

```php
$value = $translator->translate(array('Hi', 'This is my second text to detect!'), 'fr', 'en', true);
// This will return the following array:
// array(
//     0 => 'Salut',
//     1 => 'Ceci est mon second texte à détecter !',
// )
```

### Obtain all languages codes available

Retrieve the languages service and call the `get()` method without any argument:

```php
// Use Autowiring
public function myFunction(Language $language)
{
    $language->get();
    // This will return:
    // array(
    //     array('language' => 'en'),
    //     array('language' => 'fr'),
    //     ...
    // )
```

### Obtain all languages codes available with their names translated

Retrieve the languages service and call the `get()` method with a target language argument:

```php
$language->get('fr');
// This will return:
// array(
//     array('language' => 'en', 'name' => 'Anglais'),
//     array('language' => 'fr', 'name' => 'Français'),
//     ...
// )
```


Notice: this will consume a detector API call.
