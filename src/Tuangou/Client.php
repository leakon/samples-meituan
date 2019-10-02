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
     * Upload image.
     *
     * @param string $path
     *
     */
    public function queryShopDeal(int $offset = 1, int $limit = 100)
    {
        $params = [
            'offset'    => $offset,
            'limit'     => $limit,
        ];
        return $this->httpGet('/tuangou/deal/queryshopdeal', $params);
    }

}
