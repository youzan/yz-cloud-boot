<?php

namespace YouzanCloudBootTests\Util;

use DateTime;
use ReflectionException;
use ReflectionMethod;
use stdClass;
use YouzanCloudBoot\Exception\CommonException;
use YouzanCloudBoot\Exception\ExtensionPointHandleException;
use YouzanCloudBoot\Util\ObjectBuilder;
use YouzanCloudBootTests\Base\BaseTestCase;
use YouzanCloudBootTests\Stub\ExtensionPoint\BizTestService;
use YouzanCloudBootTests\Stub\ExtensionPoint\Param\BizTestData;
use YouzanCloudBootTests\Stub\ExtensionPoint\Param\BizTestRequest;
use YouzanCloudBootTests\Stub\ExtensionPoint\Param\BizTestSecondaryData;

class ObjectBuilderTest extends BaseTestCase
{

    public function mockRequestData()
    {
        $fakeRequest = [
            'requestId' => 1234,
            'data' => [
                'number' => 1024,
                'content' => 'hello, world',
                'secondaryData' => [
                    'boolean' => true,
                    'string' => 'test string 1',
                    'integer' => 1234,
                    'double' => 3.1415926,
                    'anonymousObject' => ['a' => 'b', 'c' => 'd'],
                    'listOfString' => ['aaa', 'bbb', 'ccc'],
                    'listOfAnonymousObject' => [
                        ['a' => 'b', 'c' => 'd'],
                        ['e' => 'f', 'g' => 'h']
                    ],
                    'testDate' => '1234567890'
                ],
                'dateRange' => [1234567890, 'Thu, 21 Dec 2000 16:01:07', '2012-12-12 00:00:00'],
                'secondaryDataList' => [
                    [
                        'boolean' => false,
                        'string' => 'test string 2',
                        'integer' => 2345,
                        'double' => 2.7182818,
                        'anonymousObject' => ['e' => 'f', 'g' => 'h'],
                        'listOfString' => ['ddd', 'eee', 'fff'],
                        'listOfAnonymousObject' => [
                            ['e' => 'f', 'h' => 'i'],
                            ['g' => 'h', 'i' => 'j']
                        ],
                        'testDate' => '2019-12-12 00:00:00'
                    ],
                    [
                        'boolean' => false,
                        'string' => 'test string 3',
                        'integer' => 3456,
                        'double' => 2.7182818284,
                        'anonymousObject' => ['i' => 'j', 'k' => 'l'],
                        'listOfString' => ['ggg', 'hhh', 'iii'],
                        'listOfAnonymousObject' => [
                            ['k' => 'l', 'm' => 'n'],
                            ['o' => 'p', 'q' => 'r']
                        ],
                        'testDate' => 'Thu, 21 Dec 2000 16:01:07'
                    ],
                ],
                'multiLevelList' => [
                    [
                        [
                            [
                                'boolean' => true,
                                'string' => 'test string 4',
                                'integer' => 9876,
                                'double' => 2.7182818,
                                'anonymousObject' => ['e' => 'f', 'g' => 'h'],
                                'listOfString' => ['ddd', 'eee', 'fff'],
                                'listOfAnonymousObject' => [
                                    ['xy' => 'xy', 'xx' => 'xx'],
                                ],
                                'testDate' => '2019-12-12 00:00:00'
                            ],
                            [
                                'boolean' => true,
                                'string' => 'test string 5',
                                'integer' => 6789,
                                'double' => 2.7182818284,
                                'anonymousObject' => ['i' => 'j', 'k' => 'l'],
                                'listOfString' => ['ggg', 'hhh', 'iii'],
                                'listOfAnonymousObject' => [
                                    ['xy' => 'xy', 'xx' => 'xx'],
                                ],
                                'testDate' => '2019-12-12 00:00:00'
                            ],
                        ],
                        [
                            [
                                'boolean' => false,
                                'string' => 'test string 6',
                                'integer' => 1024,
                                'double' => 2.7182818,
                                'anonymousObject' => ['e' => 'f', 'g' => 'h'],
                                'listOfString' => ['ddd', 'eee', 'fff'],
                                'listOfAnonymousObject' => [
                                    ['a' => 'c', 'e' => 'g'],
                                    ['b' => 'd', 'f' => 'h']
                                ],
                                'testDate' => '2019-12-12 00:00:00'
                            ],
                            [
                                'boolean' => false,
                                'string' => 'test string 7',
                                'integer' => 2048,
                                'double' => 2.7182818284,
                                'anonymousObject' => ['i' => 'j', 'k' => 'l'],
                                'listOfString' => ['ggg', 'hhh', 'iii'],
                                'listOfAnonymousObject' => [
                                    ['i' => 'k', 'm' => 'o'],
                                    ['j' => 'l', 'n' => 'p']
                                ],
                                'testDate' => '2019-12-12 00:00:00'
                            ],
                        ],
                    ],
                    [
                        [
                            [
                                'boolean' => false,
                                'string' => 'test string 8',
                                'integer' => 9876,
                                'double' => 2.7182818,
                                'anonymousObject' => ['e' => 'f', 'g' => 'h'],
                                'listOfString' => ['ddd', 'eee', 'fff'],
                                'listOfAnonymousObject' => [
                                    ['xy' => 'xy', 'xx' => 'xx'],
                                ],
                                'testDate' => '2019-12-12 00:00:00'
                            ],
                            [
                                'boolean' => false,
                                'string' => 'test string 9',
                                'integer' => 6789,
                                'double' => 2.7182818284,
                                'anonymousObject' => ['i' => 'j', 'k' => 'l'],
                                'listOfString' => ['ggg', 'hhh', 'iii'],
                                'listOfAnonymousObject' => [
                                    ['xy' => 'xy', 'xx' => 'xx'],
                                ],
                                'testDate' => '2019-12-12 00:00:00'
                            ],
                        ],
                        [
                            [
                                'boolean' => true,
                                'string' => 'test string 10',
                                'integer' => 1024,
                                'double' => 2.7182818,
                                'anonymousObject' => ['e' => 'f', 'g' => 'h'],
                                'listOfString' => ['ddd', 'eee', 'fff'],
                                'listOfAnonymousObject' => [
                                    ['a' => 'c', 'e' => 'g'],
                                    ['b' => 'd', 'f' => 'h']
                                ],
                                'testDate' => '2019-12-12 00:00:00'
                            ],
                            [
                                'boolean' => true,
                                'string' => 'test string 11',
                                'integer' => 2048,
                                'double' => 2.7182818284,
                                'anonymousObject' => ['i' => 'j', 'k' => 'l'],
                                'listOfString' => ['ggg', 'hhh', 'iii'],
                                'listOfAnonymousObject' => [
                                    ['i' => 'k', 'm' => 'o'],
                                    ['j' => 'l', 'n' => 'p']
                                ],
                                'testDate' => '2019-12-12 00:00:00'
                            ],
                        ],
                    ]
                ]
            ]
        ];

        return [
            [json_encode($fakeRequest)]
        ];
    }

    /**
     * @param $input
     * @throws ReflectionException
     * @throws CommonException
     * @throws ExtensionPointHandleException
     * @dataProvider mockRequestData
     */
    public function testObjectBuilder($input)
    {
        $data = json_decode($input, true);

        /** @var ObjectBuilder $driver */
        $driver = $this->getApp()->getContainer()->get('objectBuilder');

        /** @var BizTestRequest $r */
        $r = $driver->convertArrayToMethodExclusiveParam(new ReflectionMethod(BizTestService::class, 'invoke'), $data);

        $this->assertInstanceOf(BizTestRequest::class, $r);
        $this->assertEquals(1234, $r->getRequestId());
        $this->assertInstanceOf(BizTestData::class, $r->getData());
        $this->assertInstanceOf(BizTestSecondaryData::class, $r->getData()->getSecondaryData());
        $this->assertIsArray($r->getData()->getDateRange());
        $this->assertInstanceOf(DateTime::class, $r->getData()->getDateRange()[0]);

        foreach ($r->getData()->getSecondaryDataList() as $item) {
            $this->assertStringContainsString('test string', $item->getString());
            $this->assertInstanceOf(stdClass::class, $item->getAnonymousObject());
            $this->assertIsArray($item->getListOfAnonymousObject());
            $this->assertInstanceOf(stdClass::class, $item->getListOfAnonymousObject()[0]);
            $this->assertInstanceOf(stdClass::class, $item->getListOfAnonymousObject()[1]);
            $this->assertInstanceOf(DateTime::class, $item->getTestDate());
        }

        foreach ($r->getData()->getMultiLevelList() as $level0) {
            foreach ($level0 as $level1) {
                foreach ($level1 as $item) {
                    $this->assertStringContainsString('test string', $item->getString());
                    $this->assertInstanceOf(stdClass::class, $item->getAnonymousObject());
                    $this->assertIsArray($item->getListOfAnonymousObject());
                    $this->assertInstanceOf(stdClass::class, $item->getListOfAnonymousObject()[0]);
                }
            }
        }
    }

}