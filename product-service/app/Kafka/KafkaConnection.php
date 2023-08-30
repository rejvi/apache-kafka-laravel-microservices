<?php
/**
 * Created by: rejvi
 * Data: ৩০/৮/২৩
 * Time: ২:১৬ PM
 */

namespace App\Kafka;

use Illuminate\Queue\Connectors\ConnectorInterface;
use RdKafka\Conf;
use RdKafka\KafkaConsumer;
use RdKafka\Producer;

class KafkaConnection implements ConnectorInterface
{
    public function connect(array $config) : KafkaQueue
    {

        $conf = new Conf();

        $conf->set("bootstrap.servers", $config['bootstrap_servers']);
        $conf->set("security.protocol", $config['security_protocol']);
        $conf->set("sasl.mechanisms", $config['sasl_mechanisms']);
        $conf->set("sasl.username", $config['sasl_username']);
        $conf->set("sasl.password", $config['sasl_password']);

        $producer = new Producer($conf);

        $conf->set("group.id", $config['group_id']);
        $conf->set("auto.offset.reset", 'earliest');

        $consumer = new KafkaConsumer($conf);

        return new KafkaQueue($consumer,$producer);
    }
}
