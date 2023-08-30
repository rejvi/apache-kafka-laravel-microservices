# Steps to of  Apache Kafka with Laravel

### Required Packages
```
composer require --dev barryvdh/laravel-ide-helper
```

## Step 1: Docker Configuration
```
Create a Dockerfile in your project root directory and paste the following code in it.
Create a docker-compose.yml file in your project root directory and paste the following code in it.

docker-compose up -d
```

### Step 2: ide-helper Configuration
```
php artisan ide-helper:generate

docker-compose exec product sh
php artisan ide:models
    -- output --
    # Do you want to overwrite the existing model files? Choose no to write to _ide_helper_models.php instead (yes/no) [no]:
     > yes
    
    Written new phpDocBlock to /app/app/Models/Product.php
    Written new phpDocBlock to /app/app/Models/User.php
    #
   -- output --
php artisan migrate
```
### Step 3: Kafka Class Configuration

First create a Kafka directory in your project app directory.
Create a Kafka Connection class in your project app/Kafka directory and paste the following code in it.
```
    <?php 
    namespace App\Kafka;
    
    use Illuminate\Queue\Connectors\ConnectorInterface;
    use RdKafka\Conf;
    
    class KafkaConnection implements ConnectorInterface
    {
    public function connect(array $config)
    {
    $conf = new Conf();
    
            $conf->set("bootstrap.servers", $config['bootstrap_servers']);
            $conf->set("security.protocol", $config['security_protocol']);
            $conf->set("sasl.mechanisms", $config['sasl_mechanisms']);
            $conf->set("sasl.username", $config['sasl_username']);
            $conf->set("sasl.password", $config['sasl_password']);
    
            $producer = new \RdKafka\Producer($conf);
    
            $conf->set("group.id", $config['group_id']);
            $conf->set("auto.offset.reset", 'earliest');
    
            $consumer = new \RdKafka\KafkaConsumer($conf);
    
            return new KafkaQueue($consumer,$producer);
        }
    }
```
Also Create a Kafka Queue class in your project app/Kafka directory and paste the following code in it.
```
<?php
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
```
### Step 3: ApacheKafkaServiceProvider Configuration
```
php artisan make:provider ApacheKafkaServiceProvider
```
Add ApacheKafkaServiceProvider app.php file in config directory
Add custom kafka queue connecting in queue.php file in config directory
```
        'kafka' => [
            'driver' => 'kafka',
            'queue'=> env('KAFKA_QUEUE','default'),
            'bootstrap_servers' => env('BOOTSTRAP_SERVERS'),
            'security_protocol' => env('SECURITY_PROTOCOL'),
            'sasl_mechanisms' => env('SASL_MECHANISMS'),
            'sasl_username' => env('SASL_USERNAME',),
            'sasl_password'=> env('SASL_PASSWORD'),
            'group_id' => env('GROUP_ID'),
        ],
```