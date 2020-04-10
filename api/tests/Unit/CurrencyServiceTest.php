<?php

namespace Tests\Unit;

use App\Models\Account;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;
use App\Services\CurrencyService;

class CurrencyServiceTest extends TestCase
{
    /**
     * @var float
     */
    const EURO_CURRENCY = 0.9;

    /**
     * Mocked service
     *
     * @var CurrencyService
     */
    private $service;

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
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    /**
     * @dataProvider getAmountInUSDInfoProvider
     */
    public function testGetAmountInUSDInfoMethod(
        int $accountId,
        float $amount,
        float $expectedResult
    ) {
        $account = Account::find($accountId);

        $data = $this->service->getAmountInUSDInfo($account, $amount);

        $this->assertArrayHasKey('value', $data);
        $this->assertArrayHasKey('current_currency', $data);

        $this->assertEquals($data['value'], $expectedResult);
    }

    /**
     * @return array
     */
    public function getAmountInUSDInfoProvider(): array
    {
        return [
            // account with id 1 has eur currency so it needs to transform to usd
            [1, 1, (float) number_format(1 / self::EURO_CURRENCY, 2, '.', '')],
            // account with id 1 has usd currency so it's not need to transform value
            [2, 100, 100],
            [
                1,
                200,
                (float) number_format(200 / self::EURO_CURRENCY, 2, '.', '')
            ]
        ];
    }

    /**
     * @return void
     */
    private function prepareForTests(): void
    {
        Artisan::call('migrate:fresh --seed');

        $service = $this->getMockBuilder(CurrencyService::class)
            ->onlyMethods(['getCurrentCurrency'])
            ->getMock();

        $service
            ->expects($this->any())
            ->method('getCurrentCurrency')
            ->will(
                $this->returnValue(['rates' => ['EUR' => self::EURO_CURRENCY]])
            );

        $this->service = $service;
    }
}
