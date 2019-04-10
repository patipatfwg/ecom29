<?php

namespace App\Listeners;

use App\Services\Guzzle;
use App\Events\ProductUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProductUpdatedEventListener
{
	/**
	 * Create the event listener.
	 *
	 * @return void
	 */
	public function __construct(Guzzle $guzzle)
	{
		$this->guzzle = $guzzle;
	}

	/**
	 * Handle the event.
	 *
	 * @param  ProductUpdated $event
	 *
	 * @return void
	 */
	public function handle(ProductUpdated $event)
	{
		$url = config('api.makro_product_sync_api');
		$params = [
			'form_params' => [
				'id'   => $event->product_id,
				'type' => 'product'
			]
		];

		$this->guzzle->curl('POST', $url, $params);
	}
}
