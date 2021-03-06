<?php
/**
 * User: Placecodex
 * Date: 11/26/2020
 * Time: 5:03 AM
 */

namespace Placecodex\Ethereum\Foundation;


use Placecodex\Ethereum\Foundation\Contracts\EventLogBuilderInterface;

class EventLogBuilder implements EventLogBuilderInterface
{
    /**
     * @var ERC20
     */
    private $contract;

    public function build(\stdClass $log): array
    {
        $tx['blockHash']        = $log->blockHash;
        $tx['blockNumber']      = hexdec($log->blockNumber);
         $tx['data']            = sprintf('%f', hexdec($log->data)); 
        $tx['contract']         = $log->address;
        $tx['from']             = $log->topics[1];
        $tx['to']               = (isset($log->topics[2])) ? $log->topics[2]: 'Undefined' ;
        $tx['transactionHash']  = $log->transactionHash;
        $tx['transactionIndex'] = hexdec($log->transactionIndex);
        $tx['type']             = $this->checkTopicFunction($log->topics[0]);

        return $tx;
    }

    public function setContract(ERC20 $contract)
    {
        $this->contract = $contract;
    }

    private function checkTopicFunction($hash)
    {
        return array_search(str_ireplace('0x', '', $hash), $this->contract->getTopics());
    }
}
