<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Elasticsearch\Client;

use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\City;
use App\Models\District;

use App\Http\Resources\OrderResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\UserResource;

class IndexCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Indexes all searchables to Elasticsearch';

    /** @var \Elasticsearch\Client */
    private $elasticsearch;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Client $elasticsearch)
    {
        parent::__construct();
        $this->elasticsearch = $elasticsearch;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Indexing all products. This might take a while...');
        //$this->elasticsearch->indices()->delete(["index" => "products"]); // index silme örneği
        foreach (Product::cursor() as $product)
        {
            $this->elasticsearch->index([
                'index' => $product->getSearchIndex(),
                'type' => $product->getSearchType(),
                'id' => $product->getKey(),
                'body' => $product->toSearchArray(),
            ]);
            $this->output->write('.');
        }

        $this->output->writeln('');
        $this->info('Indexing all orders. This might take a while...');
        //$this->elasticsearch->indices()->delete(["index" => "orders"]);
        foreach (Order::cursor() as $order)
        {
            $this->elasticsearch->index([
                'index' => $order->getSearchIndex(),
                'type' => $order->getSearchType(),
                'id' => $order->getKey(),
                'body' => $order->toSearchArray(),
            ]);
            $this->output->write('.');
        }

        $this->output->writeln('');
        $this->info('Indexing all users. This might take a while...');
        //$this->elasticsearch->indices()->delete(["index" => "users"]);
        foreach (User::cursor() as $user)
        {
            $this->elasticsearch->index([
                'index' => $user->getSearchIndex(),
                'type' => $user->getSearchType(),
                'id' => $user->getKey(),
                'body' => $user->toSearchArray(),
            ]);
            $this->output->write('.');
        }

        $this->output->writeln('');
        $this->info('Indexing all districts. This might take a while...');
        //$this->elasticsearch->indices()->delete(["index" => "districts"]);
        foreach (District::cursor() as $district)
        {
            $this->elasticsearch->index([
                'index' => $district->getSearchIndex(),
                'type' => $district->getSearchType(),
                'id' => $district->getKey(),
                'body' => $district->toSearchArray(),
            ]);
            $this->output->write('.');
        }

        $this->output->writeln('');
        $this->info('Indexing all cities. This might take a while...');
        //$this->elasticsearch->indices()->delete(["index" => "cities"]);
        foreach (City::cursor() as $city)
        {
            $this->elasticsearch->index([
                'index' => $city->getSearchIndex(),
                'type' => $city->getSearchType(),
                'id' => $city->getKey(),
                'body' => $city->toSearchArray(),
            ]);
            $this->output->write('.');
        }

        $this->output->writeln('');
        $this->info('Done!');
    }
}
