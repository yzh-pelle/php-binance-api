<?php

use Binance\API;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../php-binance-api.php'; // Adjust path if needed



class BinanceLiveTests extends TestCase
{
    private Binance\API $spotBinance;
    private Binance\API $futuresBinance;

    public function setUp(): void {
        $this->spotBinance = new API('X4BHNSimXOK6RKs2FcKqExquJtHjMxz5hWqF0BBeVnfa5bKFMk7X0wtkfEz0cPrJ', 'x8gLihunpNq0d46F2q0TWJmeCDahX5LMXSlv3lSFNbMI3rujSOpTDKdhbcmPSf2i');
        $this->spotBinance->useTestnet = true;

        $this->futuresBinance = new API('227719da8d8499e8d3461587d19f259c0b39c2b462a77c9b748a6119abd74401', 'b14b935f9cfacc5dec829008733c40da0588051f29a44625c34967b45c11d73c');
        $this->futuresBinance->useTestnet = true;
    }

    // Default values for the tests
    private $symbol = 'ETHUSDT';
    private $quantity = '1.23400000';
    private $price = '1000.00000000';
    private $stopprice = '1100.00000000';
    private $stoplimitprice = '900.00000000';
    private $type = 'LIMIT';
    private $limit = 2;
    private $symbols = ['ETHUSDT','BTCUSDT'];
    private $asset = 'USDT';
    private $assets = ['IOST','AAVE','CHZ'];
    private $address = '0x1234567890abcdef1234567890abcdef12345678';
    private $amount = 10.1;
    private $addressTag = '123456';
    private $addressName = 'MyAddress';
    private $transactionFeeFlag =  true;
    private $network = 'TESTNetwork';
    private $fromSymbol = 'USDT';
    private $toSymbol = 'BNB';
    private $recvWindow = 1000;
    private $side = 'BUY';
    private $test = false;
    private $interval = '15m';
    private $nbrDays = 6;
    private $baseAsset = 'ETH';
    private $quoteAsset = 'USDT';
    private $quoteQty = 10.3;
    private $fromId = 1;
    private $contractType = 'CURRENT_QUARTER';
    private $period =  '15m';
    private $origClientOrderId = 'test client order id';
    private $orderIdList = ['123456', '654321'];
    private $origClientOrderIdList = ['test client order id 1', 'test client order id 2'];
    private $countdownTime = 100;
    private $autoCloseType = 'LIQUIDATION';
    private $marginType = 'CROSSED';
    private $dualSidePosition = true;
    private $leverage = 10;
    private $multiAssetsMarginMode = true;
    private $positionSide = 'SHORT';
    private $incomeType = 'COMMISSION_REBATE';
    private $page = 3;
    private $downloadId = 'testDownloadId';
    private $fromAsset = 'USDT';
    private $toAsset = 'BNB';
    private $fromAmount = '100';
    private $validTime = '10s';
    private $quoteId = 'testQuoteId';
    private $timeInForce = 'GTC';

    private $SPOT_ORDER_PREFIX     = "x-HNA2TXFJ";
	private $CONTRACT_ORDER_PREFIX = "x-Cb7ytekJ";

    public function testPricesSpot()
    {
        $res = $this->spotBinance->prices();
        $this->assertIsArray($res);
        $this->assertArrayHasKey('BTCUSDT', $res);
        $this->assertIsString($res['BTCUSDT']);
    }

    public function testBalanceSpot()
    {
        $res = $this->spotBinance->balances();
        $this->assertIsArray($res);
        // $this->assertArrayHasKey('USDT', $res);
        // $this->assertIsString($res['USDT']['free']);
    }

    public function testBalanceFutures()
    {
        $res = $this->futuresBinance->futuresAccount();
        $this->assertIsArray($res);
        $assets = $res['assets'];
        $first = $assets[0];
        $this->assertArrayHasKey('asset', $first);
        $this->assertArrayHasKey('walletBalance', $first);
    }

    public function testBuyTestSpot()
    {
        $res = $this->spotBinance->buyTest($this->symbol, $this->quantity, $this->price, $this->type);
        $this->assertIsArray($res);
    }

    public function testSellTestSpot()
    {
        $res = $this->spotBinance->sellTest($this->symbol, $this->quantity, $this->price, $this->type);
        $this->assertIsArray($res);
    }

    public function testMarketQuoteBuyTestSpot()
    {
        $res = $this->spotBinance->marketQuoteBuyTest($this->symbol, 10);
        $this->assertIsArray($res);
    }

    public function testMarketBuyTestSpot()
    {
        $res = $this->spotBinance->marketBuyTest($this->symbol, $this->quantity);
        $this->assertIsArray($res);
    }

    public function testMarketQuoteSellTestSpot()
    {
        $res = $this->spotBinance->marketQuoteSellTest($this->symbol, 10);
        $this->assertIsArray($res);
    }

    public function testUseServerTimeSpot()
    {
        $this->spotBinance->useServerTime();
        $offset = $this->spotBinance->info['timeOffset'];
        $this->assertTrue(0 !== $offset);
    }

    public function testTimeSpot()
    {
        $res = $this->spotBinance->time();
        $this->assertIsArray($res);
        $this->assertArrayHasKey('serverTime', $res);
        $this->assertIsInt($res['serverTime']);
    }

    public function testExchangeInfoSpot()
    {
        $res = $this->spotBinance->exchangeInfo($this->symbols);
        $this->assertIsArray($res);
        $this->assertArrayHasKey('timezone', $res);
        $this->assertArrayHasKey('serverTime', $res);
        $this->assertIsInt($res['serverTime']);
        $this->assertArrayHasKey('rateLimits', $res);
        $this->assertIsArray($res['rateLimits']);
        $this->assertArrayHasKey('exchangeFilters', $res);
        $this->assertIsArray($res['exchangeFilters']);
        $this->assertArrayHasKey('symbols', $res);
        $this->assertIsArray($res['symbols']);

        // Check if the symbols are present in the exchange info
        $symbol1 = $this->symbols[0];
        $symbol2 = $this->symbols[1];
        $symbolsInfo = $this->spotBinance->exchangeInfo['symbols'];
        $this->assertArrayHasKey($symbol1, $symbolsInfo);
        $this->assertArrayHasKey($symbol2, $symbolsInfo);
        $this->assertIsArray($symbolsInfo[$symbol1]);
        $this->assertIsArray($symbolsInfo[$symbol2]);
    }

    public function testAccountSpot()
    {
        $res = $this->spotBinance->account();
        $this->assertIsArray($res);
        $this->assertArrayHasKey('makerCommission', $res);
        $this->assertArrayHasKey('takerCommission', $res);
        $this->assertArrayHasKey('buyerCommission', $res);
        $this->assertArrayHasKey('sellerCommission', $res);
        $this->assertArrayHasKey('commissionRates', $res);
        $this->assertIsArray($res['commissionRates']);
        $this->assertArrayHasKey('canTrade', $res);
        $this->assertArrayHasKey('canWithdraw', $res);
        $this->assertArrayHasKey('canDeposit', $res);
        $this->assertArrayHasKey('brokered', $res);
        $this->assertArrayHasKey('requireSelfTradePrevention', $res);
        $this->assertArrayHasKey('preventSor', $res);
        $this->assertArrayHasKey('updateTime', $res);
        $this->assertArrayHasKey('accountType', $res);
        $this->assertEquals('SPOT', $res['accountType']);
        $this->assertArrayHasKey('balances', $res);
        $this->assertIsArray($res['balances']);
    }

    public function testPrevDaySpot()
    {
        $res = $this->spotBinance->prevDay($this->symbol);
        $this->assertIsArray($res);
        $this->assertEquals($this->symbol, $res['symbol']);
        $this->assertArrayHasKey('priceChange', $res);
        $this->assertIsNumeric($res['priceChange']);
        $this->assertArrayHasKey('priceChangePercent', $res);
        $this->assertIsNumeric($res['priceChangePercent']);
        $this->assertArrayHasKey('weightedAvgPrice', $res);
        $this->assertIsNumeric($res['weightedAvgPrice']);
        $this->assertArrayHasKey('prevClosePrice', $res);
        $this->assertIsNumeric($res['prevClosePrice']);
        $this->assertArrayHasKey('lastPrice', $res);
        $this->assertIsNumeric($res['lastPrice']);
        $this->assertArrayHasKey('lastQty', $res);
        $this->assertIsNumeric($res['lastQty']);
        $this->assertArrayHasKey('bidPrice', $res);
        $this->assertIsNumeric($res['bidPrice']);
        $this->assertArrayHasKey('bidQty', $res);
        $this->assertIsNumeric($res['bidQty']);
        $this->assertArrayHasKey('askPrice', $res);
        $this->assertIsNumeric($res['askPrice']);
        $this->assertArrayHasKey('askQty', $res);
        $this->assertIsNumeric($res['askQty']);
        $this->assertArrayHasKey('openPrice', $res);
        $this->assertIsNumeric($res['openPrice']);
        $this->assertArrayHasKey('highPrice', $res);
        $this->assertIsNumeric($res['highPrice']);
        $this->assertArrayHasKey('lowPrice', $res);
        $this->assertIsNumeric($res['lowPrice']);
        $this->assertArrayHasKey('volume', $res);
        $this->assertIsNumeric($res['volume']);
        $this->assertArrayHasKey('quoteVolume', $res);
        $this->assertIsNumeric($res['quoteVolume']);
        $this->assertArrayHasKey('openTime', $res);
        $this->assertIsInt($res['openTime']);
        $this->assertArrayHasKey('closeTime', $res);
        $this->assertIsInt($res['closeTime']);
        $this->assertArrayHasKey('firstId', $res);
        $this->assertIsInt($res['firstId']);
        $this->assertArrayHasKey('lastId', $res);
        $this->assertIsInt($res['lastId']);
        $this->assertArrayHasKey('count', $res);
        $this->assertIsInt($res['count']);
    }

    public function testAggTradesSpot()
    {
        $res = $this->spotBinance->aggTrades($this->symbol);
        $this->assertIsArray($res);
        $this->assertIsArray($res[0]);
        $trade = $res[0];
        $this->assertArrayHasKey('price', $trade);
        $this->assertIsNumeric($trade['price']);
        $this->assertArrayHasKey('quantity', $trade);
        $this->assertIsNumeric($trade['quantity']);
        $this->assertArrayHasKey('timestamp', $trade);
        $this->assertIsInt($trade['timestamp']);
        $this->assertArrayHasKey('maker', $trade);
        $this->assertIsString($trade['maker']);
    }

    public function testHistoricalTradesSpot()
    {
        $res = $this->spotBinance->historicalTrades($this->symbol, $this->limit);
        $this->assertIsArray($res);
        $this->assertIsArray($res[0]);
        $this->assertArrayHasKey('id', $res[0]);
        $this->assertIsNumeric($res[0]['id']);
        $this->assertArrayHasKey('price', $res[0]);
        $this->assertIsNumeric($res[0]['price']);
        $this->assertArrayHasKey('qty', $res[0]);
        $this->assertIsNumeric($res[0]['qty']);
        $this->assertArrayHasKey('time', $res[0]);
        $this->assertIsNumeric($res[0]['time']);
        $this->assertArrayHasKey('isBuyerMaker', $res[0]);
        $this->assertIsBool($res[0]['isBuyerMaker']);
        $this->assertArrayHasKey('isBestMatch', $res[0]);
        $this->assertIsBool($res[0]['isBestMatch']);
    }

    public function testDepthSpot()
    {
        $res = $this->spotBinance->depth($this->symbol, $this->limit);
        $this->assertIsArray($res);

        $this->assertArrayHasKey('bids', $res);
        $this->assertIsArray($res['bids']);
        $this->assertArrayHasKey('asks', $res);
        $this->assertIsArray($res['asks']);
    }

    public function testCandlesticksSpot()
    {
        $res = $this->spotBinance->candlesticks($this->symbol, $this->interval, $this->limit);
        $this->assertIsArray($res);
        $firstKey = array_key_first($res);
        $this->assertIsNumeric($firstKey);
        $candle = $res[$firstKey];
        $this->assertArrayHasKey('open', $candle);
        $this->assertIsNumeric($candle['open']);
        $this->assertArrayHasKey('high', $candle);
        $this->assertIsNumeric($candle['high']);
        $this->assertArrayHasKey('low', $candle);
        $this->assertIsNumeric($candle['low']);
        $this->assertArrayHasKey('close', $candle);
        $this->assertIsNumeric($candle['close']);
        $this->assertArrayHasKey('volume', $candle);
        $this->assertIsNumeric($candle['volume']);
        $this->assertArrayHasKey('openTime', $candle);
        $this->assertIsInt($candle['openTime']);
        $this->assertArrayHasKey('closeTime', $candle);
        $this->assertIsInt($candle['closeTime']);
        $this->assertArrayHasKey('assetVolume', $candle);
        $this->assertIsNumeric($candle['assetVolume']);
        $this->assertArrayHasKey('baseVolume', $candle);
        $this->assertIsNumeric($candle['baseVolume']);
        $this->assertArrayHasKey('trades', $candle);
        $this->assertIsInt($candle['trades']);
        $this->assertArrayHasKey('assetBuyVolume', $candle);
        $this->assertIsNumeric($candle['assetBuyVolume']);
        $this->assertArrayHasKey('takerBuyVolume', $candle);
        $this->assertIsNumeric($candle['takerBuyVolume']);
    }

    // could throw an error: https://github.com/ccxt/php-binance-api/actions/runs/14491775733/job/40649647274?pr=511
    // public function testSystemStatusSpot()
    // {
    //     $this->spotBinance->useTestnet = false; // set to false for sapi request
    //     $res = $this->spotBinance->systemStatus();
    //     $this->assertIsArray($res);
    //     $this->assertArrayHasKey('api', $res);
    //     $this->assertIsArray($res['api']);
    //     $this->assertArrayHasKey('status', $res['api']);
    //     $this->assertArrayHasKey('fapi', $res);
    //     $this->assertIsArray($res['fapi']);
    //     $this->assertArrayHasKey('status', $res['fapi']);
    //     $this->assertArrayHasKey('sapi', $res);
    //     $this->assertIsArray($res['sapi']);
    //     $this->assertArrayHasKey('status', $res['sapi']);
    //     $this->spotBinance->useTestnet = true; // reset to true for other tests
    // }

    public function testAvgPriceSpot()
    {
        $res = $this->spotBinance->avgPrice($this->symbol);
        $this->assertIsNumeric($res);
    }

    public function testTimeFutures()
    {
        $res = $this->futuresBinance->futuresTime();
        $this->assertIsArray($res);
        $this->assertArrayHasKey('serverTime', $res);
        $this->assertIsInt($res['serverTime']);
    }

    public function testExchangeInfoFutures()
    {
        $res = $this->futuresBinance->futuresExchangeInfo();
        $this->assertIsArray($res);
        $this->assertArrayHasKey('timezone', $res);
        $this->assertArrayHasKey('serverTime', $res);
        $this->assertIsInt($res['serverTime']);
        $this->assertArrayHasKey('futuresType', $res);
        $this->assertArrayHasKey('rateLimits', $res);
        $this->assertIsArray($res['rateLimits']);
        $this->assertArrayHasKey('exchangeFilters', $res);
        $this->assertIsArray($res['exchangeFilters']);
        $this->assertArrayHasKey('assets', $res);
        $this->assertIsArray($res['assets']);
        $this->assertArrayHasKey('symbols', $res);
        $this->assertIsArray($res['symbols']);
    }

    public function testDepthFutures()
    {
        $res = $this->futuresBinance->futuresDepth($this->symbol, 5);
        $this->assertIsArray($res);
        $this->assertArrayHasKey('bids', $res);
        $this->assertIsArray($res['bids']);
        $this->assertArrayHasKey('asks', $res);
        $this->assertIsArray($res['asks']);
    }

    public function testRecentTradesFutures()
    {
        $res = $this->futuresBinance->futuresRecentTrades($this->symbol, $this->limit);
        $this->assertIsArray($res);
        $this->assertIsArray($res[0]);
        $this->assertArrayHasKey('id', $res[0]);
        $this->assertIsNumeric($res[0]['id']);
        $this->assertArrayHasKey('price', $res[0]);
        $this->assertIsNumeric($res[0]['price']);
        $this->assertArrayHasKey('qty', $res[0]);
        $this->assertIsNumeric($res[0]['qty']);
        $this->assertArrayHasKey('quoteQty', $res[0]);
        $this->assertIsNumeric($res[0]['quoteQty']);
        $this->assertArrayHasKey('time', $res[0]);
        $this->assertIsNumeric($res[0]['time']);
        $this->assertArrayHasKey('isBuyerMaker', $res[0]);
        $this->assertIsBool($res[0]['isBuyerMaker']);
    }

    public function testHistoricalTradesFutures()
    {
        $res = $this->futuresBinance->futuresHistoricalTrades($this->symbol, $this->limit);
        $this->assertIsArray($res);
        $this->assertIsArray($res[0]);
        $this->assertArrayHasKey('id', $res[0]);
        $this->assertIsNumeric($res[0]['id']);
        $this->assertArrayHasKey('price', $res[0]);
        $this->assertIsNumeric($res[0]['price']);
        $this->assertArrayHasKey('qty', $res[0]);
        $this->assertIsNumeric($res[0]['qty']);
        $this->assertArrayHasKey('quoteQty', $res[0]);
        $this->assertIsNumeric($res[0]['quoteQty']);
        $this->assertArrayHasKey('time', $res[0]);
        $this->assertIsNumeric($res[0]['time']);
        $this->assertArrayHasKey('isBuyerMaker', $res[0]);
        $this->assertIsBool($res[0]['isBuyerMaker']);
    }

    public function testAggTradesFutures()
    {
        $res = $this->futuresBinance->futuresAggTrades($this->symbol);
        $this->assertIsArray($res);
        $this->assertIsArray($res[0]);
        $this->assertArrayHasKey('price', $res[0]);
        $this->assertIsNumeric($res[0]['price']);
        $this->assertArrayHasKey('quantity', $res[0]);
        $this->assertIsNumeric($res[0]['quantity']);
        $this->assertArrayHasKey('timestamp', $res[0]);
        $this->assertIsInt($res[0]['timestamp']);
        $this->assertArrayHasKey('maker', $res[0]);
        $this->assertIsString($res[0]['maker']);
    }

    public function testCandlesticksFutures()
    {
        $res = $this->futuresBinance->futuresCandlesticks($this->symbol, $this->interval, $this->limit);
        $this->assertIsArray($res);
        $firstKey = array_key_first($res);
        $this->assertIsNumeric($firstKey);
        $candle = $res[$firstKey];
        $this->assertArrayHasKey('open', $candle);
        $this->assertIsNumeric($candle['open']);
        $this->assertArrayHasKey('high', $candle);
        $this->assertIsNumeric($candle['high']);
        $this->assertArrayHasKey('low', $candle);
        $this->assertIsNumeric($candle['low']);
        $this->assertArrayHasKey('close', $candle);
        $this->assertIsNumeric($candle['close']);
        $this->assertArrayHasKey('volume', $candle);
        $this->assertIsNumeric($candle['volume']);
        $this->assertArrayHasKey('openTime', $candle);
        $this->assertIsInt($candle['openTime']);
        $this->assertArrayHasKey('closeTime', $candle);
        $this->assertIsInt($candle['closeTime']);
        $this->assertArrayHasKey('assetVolume', $candle);
        $this->assertIsNumeric($candle['assetVolume']);
        $this->assertArrayHasKey('baseVolume', $candle);
        $this->assertIsNumeric($candle['baseVolume']);
        $this->assertArrayHasKey('trades', $candle);
        $this->assertIsInt($candle['trades']);
        $this->assertArrayHasKey('assetBuyVolume', $candle);
        $this->assertIsNumeric($candle['assetBuyVolume']);
        $this->assertArrayHasKey('takerBuyVolume', $candle);
        $this->assertIsNumeric($candle['takerBuyVolume']);
    }

    public function testContinuousCandlesticksFutures()
    {
        $res = $this->futuresBinance->futuresContinuousCandlesticks($this->symbol, $this->interval, $this->limit, null, null, $this->contractType);
        $this->assertIsArray($res);
        $firstKey = array_key_first($res);
        $this->assertIsNumeric($firstKey);
        $candle = $res[$firstKey];
        $this->assertArrayHasKey('open', $candle);
        $this->assertIsNumeric($candle['open']);
        $this->assertArrayHasKey('high', $candle);
        $this->assertIsNumeric($candle['high']);
        $this->assertArrayHasKey('low', $candle);
        $this->assertIsNumeric($candle['low']);
        $this->assertArrayHasKey('close', $candle);
        $this->assertIsNumeric($candle['close']);
        $this->assertArrayHasKey('volume', $candle);
        $this->assertIsNumeric($candle['volume']);
        $this->assertArrayHasKey('openTime', $candle);
        $this->assertIsInt($candle['openTime']);
        $this->assertArrayHasKey('closeTime', $candle);
        $this->assertIsInt($candle['closeTime']);
        $this->assertArrayHasKey('assetVolume', $candle);
        $this->assertIsNumeric($candle['assetVolume']);
        $this->assertArrayHasKey('baseVolume', $candle);
        $this->assertIsNumeric($candle['baseVolume']);
        $this->assertArrayHasKey('trades', $candle);
        $this->assertIsInt($candle['trades']);
        $this->assertArrayHasKey('assetBuyVolume', $candle);
        $this->assertIsNumeric($candle['assetBuyVolume']);
        $this->assertArrayHasKey('takerBuyVolume', $candle);
        $this->assertIsNumeric($candle['takerBuyVolume']);
    }

    public function testIndexPriceCandlesticksFutures()
    {
        $res = $this->futuresBinance->futuresIndexPriceCandlesticks($this->symbol, $this->interval, $this->limit);
        $this->assertIsArray($res);
        $firstKey = array_key_first($res);
        $this->assertIsNumeric($firstKey);
        $candle = $res[$firstKey];
        $this->assertArrayHasKey('open', $candle);
        $this->assertIsNumeric($candle['open']);
        $this->assertArrayHasKey('high', $candle);
        $this->assertIsNumeric($candle['high']);
        $this->assertArrayHasKey('low', $candle);
        $this->assertIsNumeric($candle['low']);
        $this->assertArrayHasKey('close', $candle);
        $this->assertIsNumeric($candle['close']);
        $this->assertArrayHasKey('volume', $candle);
        $this->assertIsNumeric($candle['volume']);
        $this->assertArrayHasKey('openTime', $candle);
        $this->assertIsInt($candle['openTime']);
        $this->assertArrayHasKey('closeTime', $candle);
        $this->assertIsInt($candle['closeTime']);
        $this->assertArrayHasKey('assetVolume', $candle);
        $this->assertIsNumeric($candle['assetVolume']);
        $this->assertArrayHasKey('baseVolume', $candle);
        $this->assertIsNumeric($candle['baseVolume']);
        $this->assertArrayHasKey('trades', $candle);
        $this->assertIsInt($candle['trades']);
        $this->assertArrayHasKey('assetBuyVolume', $candle);
        $this->assertIsNumeric($candle['assetBuyVolume']);
        $this->assertArrayHasKey('takerBuyVolume', $candle);
        $this->assertIsNumeric($candle['takerBuyVolume']);
    }

    public function testMarkPriceCandlesticksFutures()
    {
        $res = $this->futuresBinance->futuresMarkPriceCandlesticks($this->symbol, $this->interval, $this->limit);
        $this->assertIsArray($res);
        $firstKey = array_key_first($res);
        $this->assertIsNumeric($firstKey);
        $candle = $res[$firstKey];
        $this->assertArrayHasKey('open', $candle);
        $this->assertIsNumeric($candle['open']);
        $this->assertArrayHasKey('high', $candle);
        $this->assertIsNumeric($candle['high']);
        $this->assertArrayHasKey('low', $candle);
        $this->assertIsNumeric($candle['low']);
        $this->assertArrayHasKey('close', $candle);
        $this->assertIsNumeric($candle['close']);
        $this->assertArrayHasKey('volume', $candle);
        $this->assertIsNumeric($candle['volume']);
        $this->assertArrayHasKey('openTime', $candle);
        $this->assertIsInt($candle['openTime']);
        $this->assertArrayHasKey('closeTime', $candle);
        $this->assertIsInt($candle['closeTime']);
        $this->assertArrayHasKey('assetVolume', $candle);
        $this->assertIsNumeric($candle['assetVolume']);
        $this->assertArrayHasKey('baseVolume', $candle);
        $this->assertIsNumeric($candle['baseVolume']);
        $this->assertArrayHasKey('trades', $candle);
        $this->assertIsInt($candle['trades']);
        $this->assertArrayHasKey('assetBuyVolume', $candle);
        $this->assertIsNumeric($candle['assetBuyVolume']);
        $this->assertArrayHasKey('takerBuyVolume', $candle);
        $this->assertIsNumeric($candle['takerBuyVolume']);
    }

    public function testPremiumIndexCandlesticksFutures()
    {
        $res = $this->futuresBinance->futuresPremiumIndexCandlesticks($this->symbol, $this->interval, $this->limit);
        $this->assertIsArray($res);
        $firstKey = array_key_first($res);
        $this->assertIsNumeric($firstKey);
        $candle = $res[$firstKey];
        $this->assertArrayHasKey('open', $candle);
        $this->assertIsNumeric($candle['open']);
        $this->assertArrayHasKey('high', $candle);
        $this->assertIsNumeric($candle['high']);
        $this->assertArrayHasKey('low', $candle);
        $this->assertIsNumeric($candle['low']);
        $this->assertArrayHasKey('close', $candle);
        $this->assertIsNumeric($candle['close']);
        $this->assertArrayHasKey('volume', $candle);
        $this->assertIsNumeric($candle['volume']);
        $this->assertArrayHasKey('openTime', $candle);
        $this->assertIsInt($candle['openTime']);
        $this->assertArrayHasKey('closeTime', $candle);
        $this->assertIsInt($candle['closeTime']);
        $this->assertArrayHasKey('assetVolume', $candle);
        $this->assertIsNumeric($candle['assetVolume']);
        $this->assertArrayHasKey('baseVolume', $candle);
        $this->assertIsNumeric($candle['baseVolume']);
        $this->assertArrayHasKey('trades', $candle);
        $this->assertIsInt($candle['trades']);
        $this->assertArrayHasKey('assetBuyVolume', $candle);
        $this->assertIsNumeric($candle['assetBuyVolume']);
        $this->assertArrayHasKey('takerBuyVolume', $candle);
        $this->assertIsNumeric($candle['takerBuyVolume']);
    }

    public function testMarkPriceFutures()
    {
        $res = $this->futuresBinance->futuresMarkPrice($this->symbol);
        $this->assertIsArray($res);
        $this->assertEquals($this->symbol, $res['symbol']);
        $this->assertArrayHasKey('markPrice', $res);
        $this->assertIsNumeric($res['markPrice']);
        $this->assertArrayHasKey('indexPrice', $res);
        $this->assertIsNumeric($res['indexPrice']);
        $this->assertArrayHasKey('estimatedSettlePrice', $res);
        $this->assertIsNumeric($res['estimatedSettlePrice']);
        $this->assertArrayHasKey('lastFundingRate', $res);
        $this->assertIsNumeric($res['lastFundingRate']);
        $this->assertArrayHasKey('interestRate', $res);
        $this->assertIsNumeric($res['interestRate']);
        $this->assertArrayHasKey('nextFundingTime', $res);
        $this->assertIsInt($res['nextFundingTime']);
        $this->assertArrayHasKey('time', $res);
        $this->assertIsInt($res['time']);
    }

    public function testFundingRateHistoryFutures()
    {
        $res = $this->futuresBinance->futuresFundingRateHistory($this->symbol, $this->limit);
        $this->assertIsArray($res);
        $this->assertIsArray($res[0]);
        $entry = $res[0];
        $this->assertArrayHasKey('symbol', $entry);
        $this->assertEquals($this->symbol, $entry['symbol']);
        $this->assertArrayHasKey('fundingTime', $entry);
        $this->assertIsInt($entry['fundingTime']);
        $this->assertArrayHasKey('fundingRate', $entry);
        $this->assertIsNumeric($entry['fundingRate']);
        $this->assertArrayHasKey('markPrice', $entry);
        $this->assertIsNumeric($entry['markPrice']);
    }

    public function testPrevDayFutures()
    {
        $res = $this->futuresBinance->futuresPrevDay($this->symbol);
        $this->assertIsArray($res);
        $this->assertEquals($this->symbol, $res['symbol']);
        $this->assertArrayHasKey('priceChange', $res);
        $this->assertIsNumeric($res['priceChange']);
        $this->assertArrayHasKey('priceChangePercent', $res);
        $this->assertIsNumeric($res['priceChangePercent']);
        $this->assertArrayHasKey('weightedAvgPrice', $res);
        $this->assertIsNumeric($res['weightedAvgPrice']);
        $this->assertArrayHasKey('lastPrice', $res);
        $this->assertIsNumeric($res['lastPrice']);
        $this->assertArrayHasKey('lastQty', $res);
        $this->assertIsNumeric($res['lastQty']);
        $this->assertArrayHasKey('openPrice', $res);
        $this->assertIsNumeric($res['openPrice']);
        $this->assertArrayHasKey('highPrice', $res);
        $this->assertIsNumeric($res['highPrice']);
        $this->assertArrayHasKey('lowPrice', $res);
        $this->assertIsNumeric($res['lowPrice']);
        $this->assertArrayHasKey('volume', $res);
        $this->assertIsNumeric($res['volume']);
        $this->assertArrayHasKey('quoteVolume', $res);
        $this->assertIsNumeric($res['quoteVolume']);
        $this->assertArrayHasKey('openTime', $res);
        $this->assertIsInt($res['openTime']);
        $this->assertArrayHasKey('closeTime', $res);
        $this->assertIsInt($res['closeTime']);
        $this->assertArrayHasKey('firstId', $res);
        $this->assertIsInt($res['firstId']);
        $this->assertArrayHasKey('lastId', $res);
        $this->assertIsInt($res['lastId']);
        $this->assertArrayHasKey('count', $res);
        $this->assertIsInt($res['count']);
    }

    public function testPriceFutures()
    {
        $res = $this->futuresBinance->futuresPrice($this->symbol);
        $this->assertIsNumeric($res);
    }

    public function testPricesFutures()
    {
        $res = $this->futuresBinance->futuresPrices();
        $this->assertIsArray($res);
        $this->assertArrayHasKey($this->symbol, $res);
        $this->assertIsNumeric($res[$this->symbol]);
    }

    public function testPriceV2Futures()
    {
        $res = $this->futuresBinance->futuresPriceV2($this->symbol);
        $this->assertIsNumeric($res);
    }

    public function testPricesV2Futures()
    {
        $res = $this->futuresBinance->futuresPricesV2();
        $this->assertIsArray($res);
        $this->assertArrayHasKey($this->symbol, $res);
        $this->assertIsNumeric($res[$this->symbol]);
    }
}
