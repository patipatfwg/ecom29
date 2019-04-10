<?php

namespace App\Listeners;

use App\Services\Guzzle;
use App\Repositories\CategoryRepository;
use App\Events\CategoryUpdated;
use App\Events\ProductUpdated;
use App\Listeners\ProductUpdatedEventListener;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CategoryUpdatedEventListener
{
	/**
	 * Create the event listener.
	 *
	 * @return void
	 */
	public function __construct(Guzzle $guzzle, CategoryRepository $categoryRepository)
	{
		$this->guzzle = $guzzle;
		$this->categoryRepository = $categoryRepository;
	}

	/**
	 * Handle the event.
	 *
	 * @param  CategoryUpdated $event
	 *
	 * @return void
	 */
	public function handle(CategoryUpdated $event)
	{
		$results = $this->categoryRepository->getContentsByCategory([$event->category_id]);
		$productUpdatedEventListener = new ProductUpdatedEventListener($this->guzzle);

		if (!empty($results['data'])) {
			foreach ($results['data'][0]['contents'] as $content) {
				if ($content['content_type'] == 'product') {
					$productUpdatedEventListener->handle(new ProductUpdated($content['content_id']));
				}
			}
		}
	}
}
