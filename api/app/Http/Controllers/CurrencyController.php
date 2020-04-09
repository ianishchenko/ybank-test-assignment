<?php

namespace App\Http\Controllers;

use App\Repository\Eloquent\AccountRepository;
use App\Services\CurrencyService;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    /**
     * @var AccountRepository
     */
    protected $currencyService;

    /**
     * CurrencyController constructor.
     * @param  CurrencyService  $currencyService
     */
    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    /**
     * @param  int  $id
     * @return array
     */
    public function index(Request $request): array
    {
        return ['data' => $this->currencyService->getCurrentCurrency()];
    }
}
