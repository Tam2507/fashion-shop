<?php

namespace App\Contracts;

use App\Models\Product;

interface SearchEngineInterface
{
    /**
     * Index a product for search
     */
    public function indexProduct(Product $product): void;

    /**
     * Remove a product from search index
     */
    public function removeFromIndex(int $productId): void;

    /**
     * Search for products
     */
    public function search(string $query, array $filters = []): array;

    /**
     * Update product in search index
     */
    public function updateProduct(Product $product): void;
}