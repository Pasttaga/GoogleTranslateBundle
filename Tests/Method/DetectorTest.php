<?php

namespace Pasttaga\GoogleTranslateBundle\Tests\Method;

use Pasttaga\GoogleTranslateBundle\Translate\Method\Detector;

/**
 * Detector class test.
 */
class DetectorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Detector Detector service
     */
    protected $detector;

    /**
     * @var \GuzzleHttp\Message\Response mock
     */
    protected $responseMock;

    /**
     * Set up methods services.
     */
    protected function setUp()
    {
        $this->detector = $this->getMock(
            'Pasttaga\GoogleTranslateBundle\Translate\Method\Detector',
            null,
            ['fakeapikey', $this->getClientMock()]
        );
    }

    /**
     * Test simple detect method.
     */
    public function testSimpleDetect()
    {
        // Given
        $this->responseMock->expects($this->any())->method('json')->will($this->returnValue(
            ['data' => ['detections' => [[['language' => 'en']]]]]
        ));

        // When
        $language = $this->detector->detect('hi');

        // Then
        $this->assertEquals($language, 'en', 'Should return language "en"');
    }

    /**
     * Test exception detect method.
     */
    public function testExceptionDetect()
    {
        $this->responseMock->expects($this->any())->method('json')->will($this->returnValue(
            ['data' => ['detections' => [[['language' => Detector::UNDEFINED_LANGUAGE]]]]]
        ));

        $this->setExpectedException('Pasttaga\GoogleTranslateBundle\Exception\UnableToDetectException');

        $this->detector->detect('undefined');
    }

    /**
     * Returns Guzzle HTTP client mock and sets response mock property.
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getClientMock()
    {
        $clientMock = $this->getMockBuilder('GuzzleHttp\ClientInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $this->responseMock = $this->getMockBuilder('GuzzleHttp\Message\Response')
            ->disableOriginalConstructor()
            ->getMock();

        $clientMock->expects($this->any())->method('get')->will($this->returnValue($this->responseMock));

        return $clientMock;
    }
}
