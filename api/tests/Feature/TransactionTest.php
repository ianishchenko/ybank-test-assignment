<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

/**
 * Class TransactionTest
 * @package Tests\Feature
 */
class TransactionTest extends TestCase
{
    /**
     * Default preparation for each test
     *
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->prepareForTests();
    }

    /**
     * @dataProvider shallowStoreProvider
     * @param  int  $accountId
     * @param  array  $data
     * @param  int  $expectedResult
     */
    public function testStoreTransactionActionShallow(
        int $accountId,
        array $data,
        int $expectedCode,
        string $expectedArrayKey
    ) {
        $response = $this->json('POST', $this->getURL($accountId), $data);

        $response->assertStatus($expectedCode);
        $this->assertArrayHasKey(
            $expectedArrayKey,
            $response->decodeResponseJson()
        );
    }

    /**
     * @return array
     */
    public function shallowStoreProvider(): array
    {
        return [
            [
                1,
                ['to' => 2, 'amount' => 1, 'details' => 'positive case 1'],
                200,
                'data'
            ],
            [
                2,
                ['to' => 1, 'amount' => 100, 'details' => 'positive case 2'],
                200,
                'data'
            ],
            [
                1,
                [
                    'to' => 1,
                    'amount' => 1,
                    'details' => 'transaction with himself 1'
                ],
                422,
                'errors'
            ],
            [
                2,
                [
                    'to' => 2,
                    'amount' => 100,
                    'details' => 'transaction with himself 2'
                ],
                422,
                'errors'
            ],
            [
                2,
                [
                    'to' => 2,
                    'amount' => 'two',
                    'details' => 'transaction with string "amount" field'
                ],
                422,
                'errors'
            ],
            [
                2,
                [
                    'to' => 1,
                    'details' => 'transaction without required "amount" field'
                ],
                422,
                'errors'
            ],
            [
                2,
                [
                    'to' => 3,
                    'details' => 'transaction with nonexistent "to" account'
                ],
                422,
                'errors'
            ],
            [
                3,
                [
                    'to' => 1,
                    'details' => 'transaction with nonexistent "from" account'
                ],
                422,
                'errors'
            ],
            [
                2,
                [
                    'amount' => 100,
                    'details' => 'transaction without required "to" field'
                ],
                422,
                'errors'
            ],
            // transaction without required 'details' field
            [2, ['to' => 1, 'amount' => 100], 422, 'errors'],
            [
                2,
                [
                    'to' => 1,
                    'amount' => 100001,
                    'details' =>
                        'transaction with too much money for account 2(currency is usd. balance 100000)'
                ],
                422,
                'errors'
            ],
            [
                2,
                [
                    'to' => 1,
                    'amount' => 100000,
                    'details' =>
                        'positive case for account 2(currency is usd. balance 100000)'
                ],
                200,
                'data'
            ],
            [
                1,
                [
                    'to' => 2,
                    'amount' => 13850,
                    'details' =>
                        'transaction with too much money for account 1(currency is eur. 15000 usd(balance from seed) < 13850 eur)'
                ],
                422,
                'errors'
            ]
        ];
    }

    /**
     * For ensure that records is saved and getting back after saving
     *
     * @dataProvider storeProvider
     * @param  int  $accountId
     * @param  array  $data
     * @param  int  $expectedResult
     */
    public function testGetTransactionsAfterStoringAction(
        int $accountId,
        array $data,
        int $expectedId
    ) {
        $this->json('POST', $this->getURL($accountId), $data);
        $response = $this->json(
            'GET',
            $this->getURL($accountId)
        )->decodeResponseJson();

        $this->assertArrayHasKey('data', $response);

        $newTransactionInArray = in_array(
            $expectedId,
            array_column($response['data'], 'id')
        );

        $this->assertTrue($newTransactionInArray);
    }

    public function storeProvider(): array
    {
        return [
            [1, ['to' => 2, 'amount' => 1, 'details' => 'positive case 1'], 4],
            [2, ['to' => 1, 'amount' => 100, 'details' => 'positive case 2'], 4]
        ];
    }

    /**
     * @param  int  $accountId
     * @return string
     */
    private function getURL(int $accountId): string
    {
        return "api/accounts/${accountId}/transactions";
    }

    /**
     * @return void
     */
    private function prepareForTests(): void
    {
        Artisan::call('migrate:fresh --seed');
    }
}
