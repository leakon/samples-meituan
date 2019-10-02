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
        $params = [
            'open_shop_uuid'    => $shopUUID,
            'offset'            => $offset,
            'limit'             => $limit,
        ];
        return $this->httpGet('/tuangou/deal/queryshopdeal', $params);
    }

}
