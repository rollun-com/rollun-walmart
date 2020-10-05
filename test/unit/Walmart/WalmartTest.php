<?php


namespace test\unit\Walmart\Sdk;


use PHPUnit\Framework\TestCase;
use rollun\Walmart\Sdk\Orders;
use rollun\Walmart\Walmart;

class WalmartTest extends TestCase
{
    public function testGetAllOrders()
    {
        $orders = $this->createMock(Orders::class);
        $orders->expects($this->any())->method('getAll')->willReturnCallback(function(){
            return $this->orderData();
        });

        $walmart = new Walmart(null, null, null, $orders, null, null);
        $result = $walmart->getAllOrders();

        $this->assertCount(225, $result);
    }

    protected function orderData()
    {
        static $page = 0;
        $limit = 200;
        $total = 225;
        $current = ++$page * $limit;

        $nextCursor = sprintf(
            '?limit=%d&soIndex=%d&poIndex=%d'
            . '&hasMoreElements=true&partnerId=10001042097&sellerId=101022720'
            . '&createdStartDate=2020-04-08T12:10:10.733Z&createdEndDate=2020-10-05T12:09:50.656Z',
            $limit,
            $total,
            $current
        );

        return [
            'list' => [
                'meta' => [
                    'totalCount' => $total,
                    'limit' => $limit,
                    'nextCursor' => $current < $total ? $nextCursor : null
                ],
                'elements' => [
                    'order' =>
                        array_fill(
                            0,
                            $current < $total ? $limit : $total % $limit,
                            array_fill_keys(['purchaseOrderId'], (string) random_int(1000000000000, 9999999999999))
                        )
                        /*[

                            "purchaseOrderId" => "2806493413658",
                            "customerOrderId" => "5502083356360",
                            "customerEmailId" => "40CA3DC242394A028B3CE178A1D88898@relay.walmart.com",
                            "orderDate" => 1601872604000,
                            "shippingInfo" => [],
                            "orderLines" => [
                                "orderLine" => []
                            ]
                        ]*/
                    //]
                ]
            ]
        ];
    }
}