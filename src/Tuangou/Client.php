<?php

namespace SamplesMeituan\Tuangou;

use SamplesMeituan\Kernel\BaseClient;

/**
 * Class Client.
 *
 */
class Client extends BaseClient
{

    /**
     */
    public function queryShopDeal(int $offset = 1, int $limit = 100)
    {
        $params         = [
                            'offset'            => $offset,
                            'limit'             => $limit,
                        ];
        return $this->httpGet('/tuangou/deal/queryshopdeal', $params);
    }

    public function receiptPrepare(string $receiptCode)
    {
        $receiptCode    = $this->normalizeReceipt($receiptCode);
        $params         = [
                            'receipt_code'      => $receiptCode,
                        ];
        return $this->httpPost('/router/tuangou/receipt/prepare', $params);
    }

    public function queryConsumedReceipt(string $receiptCode)
    {
        $receiptCode    = $this->normalizeReceipt($receiptCode);
        $params         = [
                            'receipt_code'      => $receiptCode,
                        ];
        return $this->httpGet('/router/tuangou/receipt/getconsumed', $params);
    }

    public function consumeReceipt(string $requestId, string $receiptCode, int $count = 1)
    {
        $appKey         = $this->app->config['app_key'];
        $receiptCode    = $this->normalizeReceipt($receiptCode);
        $params         = [
                            'requestid'         => $requestId,
                            'receipt_code'      => $receiptCode,
                            'count'             => $count,

                            'app_shop_account'          => $appKey,
                            'app_shop_accountname'      => $appKey,

                        ];
        return $this->httpPost('/router/tuangou/receipt/consume', $params);
    }

    public function revertReceipt(string $dealId, string $receiptCode, int $count = 1)
    {
        $appKey         = $this->app->config['app_key'];
        $receiptCode    = $this->normalizeReceipt($receiptCode);
        $params         = [
                            'app_deal_id'       => $dealId,
                            'receipt_code'      => $receiptCode,
                            'count'             => $count,

                            'app_shop_account'          => $appKey,
                            'app_shop_accountname'      => $appKey,

                        ];
        return $this->httpPost('/router/tuangou/receipt/reverseconsume', $params);
    }

    public function queryDealList($date = false, int $offset = 1, int $limit = 100)
    {
        if (false === $date) {
            $date   = date('Y-m-d');
        }

        $params         = [
                            'date'              => $date,
                            'offset'            => $offset,
                            'limit'             => $limit,
                        ];
        return $this->httpGet('/router/tuangou/receipt/querylistbydate', $params);
    }

    protected function normalizeReceipt(string $receiptCode) {
        $receiptCode    = trim(str_replace(' ', '', $receiptCode));
        return      $receiptCode;
    }

}
