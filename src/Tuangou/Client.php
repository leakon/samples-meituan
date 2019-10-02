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
    public function queryShopDeal(string $shopUUID, int $offset = 1, int $limit = 100)
    {
        $params         = [
                            'open_shop_uuid'    => $shopUUID,
                            'offset'            => $offset,
                            'limit'             => $limit,
                        ];
        return $this->httpGet('/tuangou/deal/queryshopdeal', $params);
    }

    public function receiptPrepare(string $shopUUID, string $receiptCode)
    {
        $receiptCode    = trim(str_replace(' ', '', $receiptCode));
        $params         = [
                            'open_shop_uuid'    => $shopUUID,
                            'receipt_code'      => $receiptCode,
                        ];
        return $this->httpGet('/router/tuangou/receipt/prepare', $params);
    }

}
