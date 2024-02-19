<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index(): void
    {
        $products = Product::factory()->count(3)->create();

        $response = $this->get(route('product.index'));

        $response->assertStatus(200);

        foreach ($products as $product) {
            $response->assertSee($product->name);
        }
    }

    public function test_index_with_page(): void
    {
        $products = Product::factory()->count(12)->create();

        $response = $this->get(route('product.index', ["page" => 2]));

        $response->assertStatus(200);

        for ($i = 9; $i < 12; $i++) {
            $response->assertSee($products[$i]->name);
        }
    }

    public function test_index_with_empty_page(): void
    {
        $products = Product::factory()->count(12)->create();

        $response = $this->get(route('product.index', ["page" => 3]));

        $response->assertStatus(200);
        $response->assertDontSee($products[0]->name);
    }

    public function test_index_with_page_not_defined(): void
    {
        $products = Product::factory()->count(12)->create();

        $response = $this->get(route('product.index'));

        $response->assertStatus(200);

        for ($i = 0; $i < 9; $i++) {
            $response->assertSee($products[$i]->name);
        }
    }

    public function test_index_with_page_not_numeric(): void
    {
        $response = $this->get(route('product.index', ["page" => "not_numeric"]));

        $response->assertStatus(400);
    }

    public function test_index_with_name_filter(): void
    {
        //  C'est peut être un flaky test
        //      Si deux éléments ont le même name, le test ne marchera pas
        $products = Product::factory()->count(3)->create();

        $response = $this->get(route("product.index", "name=" . $products[0]->name));

        $response->assertStatus(200);
        $response->assertSee($products[0]->name);
        $response->assertDontSee($products[1]->name);
        $response->assertDontSee($products[2]->name);
    }

    public function test_show_product_exists(): void
    {
        $product = Product::factory()->create();

        $response = $this->get(route('product.show', ["product" => $product->id]));

        $response->assertStatus(200);
        $response->assertSee($product->name);
    }

    public function test_show_product_not_exists(): void
    {
        $response = $this->get(route('product.show', ["product" => -1]));

        $response->assertStatus(404);
    }
}
