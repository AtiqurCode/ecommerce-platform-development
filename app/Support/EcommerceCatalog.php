<?php

namespace App\Support;

final class EcommerceCatalog
{
    /**
     * @return list<array{name: string, description: string}>
     */
    public static function categories(): array
    {
        return [
            ['name' => 'Electronics', 'description' => 'Phones, laptops, audio gear, and everyday gadgets.'],
            ['name' => 'Fashion', 'description' => 'Clothing and accessories for men and women.'],
            ['name' => 'Home & Living', 'description' => 'Furniture and essentials for modern homes.'],
            ['name' => 'Sports & Outdoors', 'description' => 'Fitness gear, camping kits, and outdoor apparel.'],
            ['name' => 'Beauty & Care', 'description' => 'Skincare, grooming, and personal care products.'],
            ['name' => 'Books & Media', 'description' => 'Bestsellers, study guides, and entertainment media.'],
            ['name' => 'Toys & Games', 'description' => 'Family games, puzzles, and creative play sets.'],
            ['name' => 'Automotive', 'description' => 'Car accessories, tools, and travel essentials.'],
        ];
    }

    /**
     * @return list<array{name: string, description: string}>
     */
    public static function subcategories(): array
    {
        return [
            ['name' => 'Mobile Phones', 'description' => 'Smartphones and mobile accessories.'],
            ['name' => 'Laptops', 'description' => 'Notebooks for work, study, and creative tasks.'],
            ['name' => 'Headphones', 'description' => 'Wired and wireless headphones for music and calls.'],
            ['name' => "Men's Clothing", 'description' => 'Casual and formal wear for men.'],
            ['name' => "Women's Clothing", 'description' => 'Everyday fashion for women.'],
            ['name' => 'Footwear', 'description' => 'Sneakers, boots, and comfortable everyday shoes.'],
            ['name' => 'Furniture', 'description' => 'Desks, chairs, and living room pieces.'],
            ['name' => 'Kitchen', 'description' => 'Cookware, small appliances, and tableware.'],
            ['name' => 'Fitness', 'description' => 'Home workout equipment and sports accessories.'],
            ['name' => 'Camping', 'description' => 'Tents, backpacks, and outdoor travel gear.'],
        ];
    }

    /**
     * English product definitions used by factories.
     *
     * @return list<array{name: string, description: string, old_price: float, new_price: float}>
     */
    public static function products(): array
    {
        return [
            [
                'name' => 'iPhone 17 Pro',
                'description' => 'Flagship smartphone with a bright display, advanced camera system, and all-day battery life.',
                'old_price' => 1499.00,
                'new_price' => 1299.00,
            ],
            [
                'name' => 'Samsung Galaxy S26',
                'description' => 'Premium Android phone with a sharp AMOLED display and a versatile multi-lens camera.',
                'old_price' => 1399.00,
                'new_price' => 1199.00,
            ],
            [
                'name' => 'Google Pixel 10',
                'description' => 'Clean Android experience with excellent computational photography and timely software updates.',
                'old_price' => 999.00,
                'new_price' => 899.00,
            ],
            [
                'name' => 'MacBook Pro 14',
                'description' => 'Powerful laptop designed for developers and creators who need reliable performance on the go.',
                'old_price' => 2499.00,
                'new_price' => 2299.00,
            ],
            [
                'name' => 'Dell XPS 15',
                'description' => 'Slim Windows laptop with a vivid display and strong battery life for professional work.',
                'old_price' => 1899.00,
                'new_price' => 1699.00,
            ],
            [
                'name' => 'Lenovo ThinkPad X1',
                'description' => 'Business notebook with a durable build, comfortable keyboard, and enterprise-ready security.',
                'old_price' => 1799.00,
                'new_price' => 1599.00,
            ],
            [
                'name' => 'Sony WH-1000XM5',
                'description' => 'Wireless noise-cancelling headphones with rich sound and long listening sessions.',
                'old_price' => 399.00,
                'new_price' => 349.00,
            ],
            [
                'name' => 'AirPods Pro',
                'description' => 'Compact wireless earbuds with active noise cancellation and clear call quality.',
                'old_price' => 279.00,
                'new_price' => 249.00,
            ],
            [
                'name' => "Men's Oxford Shirt",
                'description' => 'Breathable cotton shirt with a clean fit for office days and weekend wear.',
                'old_price' => 59.00,
                'new_price' => 39.00,
            ],
            [
                'name' => "Men's Chino Pants",
                'description' => 'Versatile chinos with a soft stretch fabric for everyday comfort.',
                'old_price' => 79.00,
                'new_price' => 59.00,
            ],
            [
                'name' => "Women's Linen Blouse",
                'description' => 'Lightweight linen blouse with a relaxed silhouette for warm-weather outfits.',
                'old_price' => 69.00,
                'new_price' => 49.00,
            ],
            [
                'name' => "Women's Midi Dress",
                'description' => 'Flattering midi dress made from soft fabric for work events and casual evenings.',
                'old_price' => 89.00,
                'new_price' => 69.00,
            ],
            [
                'name' => 'Classic Leather Sneakers',
                'description' => 'Minimal leather sneakers with cushioned soles for all-day walking comfort.',
                'old_price' => 129.00,
                'new_price' => 99.00,
            ],
            [
                'name' => 'Trail Running Shoes',
                'description' => 'Grip-focused running shoes built for uneven paths and weekend hikes.',
                'old_price' => 149.00,
                'new_price' => 119.00,
            ],
            [
                'name' => 'Ergonomic Office Chair',
                'description' => 'Adjustable office chair with lumbar support for long work sessions.',
                'old_price' => 299.00,
                'new_price' => 249.00,
            ],
            [
                'name' => 'Walnut Standing Desk',
                'description' => 'Height-adjustable desk with a warm wood finish for focused home offices.',
                'old_price' => 699.00,
                'new_price' => 599.00,
            ],
            [
                'name' => 'Ceramic Dinner Set',
                'description' => 'Twelve-piece ceramic dinner set with a matte glaze for everyday dining.',
                'old_price' => 120.00,
                'new_price' => 89.00,
            ],
            [
                'name' => 'Cast Iron Skillet',
                'description' => 'Pre-seasoned skillet that heats evenly for searing, baking, and stovetop cooking.',
                'old_price' => 55.00,
                'new_price' => 42.00,
            ],
            [
                'name' => 'Adjustable Dumbbell Set',
                'description' => 'Space-saving dumbbells with quick weight changes for home strength training.',
                'old_price' => 349.00,
                'new_price' => 299.00,
            ],
            [
                'name' => 'Yoga Mat Pro',
                'description' => 'Non-slip yoga mat with extra cushioning for studio and home practice.',
                'old_price' => 49.00,
                'new_price' => 35.00,
            ],
            [
                'name' => 'Travel Backpack 30L',
                'description' => 'Water-resistant backpack with padded laptop sleeve and organized pockets.',
                'old_price' => 110.00,
                'new_price' => 89.00,
            ],
            [
                'name' => 'Insulated Water Bottle',
                'description' => 'Stainless steel bottle that keeps drinks cold or hot throughout the day.',
                'old_price' => 35.00,
                'new_price' => 24.00,
            ],
            [
                'name' => 'Smart LED Desk Lamp',
                'description' => 'Dimmable desk lamp with warm and cool light modes for reading and late-night work.',
                'old_price' => 79.00,
                'new_price' => 59.00,
            ],
            [
                'name' => 'Wireless Charging Pad',
                'description' => 'Fast wireless charger compatible with modern smartphones and earbuds.',
                'old_price' => 45.00,
                'new_price' => 29.00,
            ],
            [
                'name' => 'Mechanical Keyboard',
                'description' => 'Tactile mechanical keyboard with quiet switches for productive typing.',
                'old_price' => 159.00,
                'new_price' => 129.00,
            ],
            [
                'name' => '4K Webcam',
                'description' => 'Sharp webcam with auto-focus and clear audio for meetings and streaming.',
                'old_price' => 129.00,
                'new_price' => 99.00,
            ],
            [
                'name' => 'Cotton Crew T-Shirt',
                'description' => 'Soft everyday tee with a classic fit and durable stitched seams.',
                'old_price' => 29.00,
                'new_price' => 19.00,
            ],
            [
                'name' => 'Wool Blend Overcoat',
                'description' => 'Warm overcoat with a tailored cut for colder city weather.',
                'old_price' => 220.00,
                'new_price' => 179.00,
            ],
            [
                'name' => 'Memory Foam Pillow',
                'description' => 'Supportive pillow that cradles the neck for more restful sleep.',
                'old_price' => 60.00,
                'new_price' => 44.00,
            ],
            [
                'name' => 'Aroma Diffuser',
                'description' => 'Quiet ultrasonic diffuser with soft ambient light for bedrooms and desks.',
                'old_price' => 48.00,
                'new_price' => 34.00,
            ],
        ];
    }

    /**
     * Curated demo products for the seeder, including free Unsplash image URLs.
     *
     * @return list<array{subcategory: string, name: string, description: string, old_price: float, new_price: float, image_url: string}>
     */
    public static function seedProducts(): array
    {
        return [
            [
                'subcategory' => 'Mobile Phones',
                'name' => 'iPhone 17 Pro',
                'description' => 'Flagship smartphone with a bright display, advanced camera system, and all-day battery life.',
                'old_price' => 1499.00,
                'new_price' => 1299.00,
                'image_url' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?auto=format&fit=crop&w=800&h=800&q=80',
            ],
            [
                'subcategory' => 'Mobile Phones',
                'name' => 'Samsung Galaxy S26',
                'description' => 'Premium Android phone with a sharp AMOLED display and a versatile multi-lens camera.',
                'old_price' => 1399.00,
                'new_price' => 1199.00,
                'image_url' => 'https://images.unsplash.com/photo-1610945415295-d9bbf067e59c?auto=format&fit=crop&w=800&h=800&q=80',
            ],
            [
                'subcategory' => 'Mobile Phones',
                'name' => 'Google Pixel 10',
                'description' => 'Clean Android experience with excellent computational photography and timely software updates.',
                'old_price' => 999.00,
                'new_price' => 899.00,
                'image_url' => 'https://images.unsplash.com/photo-1598327105666-5b89351aff93?auto=format&fit=crop&w=800&h=800&q=80',
            ],
            [
                'subcategory' => 'Laptops',
                'name' => 'MacBook Pro 14',
                'description' => 'Powerful laptop designed for developers and creators who need reliable performance on the go.',
                'old_price' => 2499.00,
                'new_price' => 2299.00,
                'image_url' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=800&h=800&q=80',
            ],
            [
                'subcategory' => 'Laptops',
                'name' => 'Dell XPS 15',
                'description' => 'Slim Windows laptop with a vivid display and strong battery life for professional work.',
                'old_price' => 1899.00,
                'new_price' => 1699.00,
                'image_url' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?auto=format&fit=crop&w=800&h=800&q=80',
            ],
            [
                'subcategory' => 'Laptops',
                'name' => 'Lenovo ThinkPad X1',
                'description' => 'Business notebook with a durable build, comfortable keyboard, and enterprise-ready security.',
                'old_price' => 1799.00,
                'new_price' => 1599.00,
                'image_url' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?auto=format&fit=crop&w=800&h=800&q=80',
            ],
            [
                'subcategory' => "Men's Clothing",
                'name' => "Men's Oxford Shirt",
                'description' => 'Breathable cotton shirt with a clean fit for office days and weekend wear.',
                'old_price' => 59.00,
                'new_price' => 39.00,
                'image_url' => 'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?auto=format&fit=crop&w=800&h=800&q=80',
            ],
            [
                'subcategory' => "Men's Clothing",
                'name' => "Men's Chino Pants",
                'description' => 'Versatile chinos with a soft stretch fabric for everyday comfort.',
                'old_price' => 79.00,
                'new_price' => 59.00,
                'image_url' => 'https://images.unsplash.com/photo-1473966968600-fa801b869a1a?auto=format&fit=crop&w=800&h=800&q=80',
            ],
            [
                'subcategory' => "Men's Clothing",
                'name' => 'Cotton Crew T-Shirt',
                'description' => 'Soft everyday tee with a classic fit and durable stitched seams.',
                'old_price' => 29.00,
                'new_price' => 19.00,
                'image_url' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=800&h=800&q=80',
            ],
            [
                'subcategory' => "Women's Clothing",
                'name' => "Women's Linen Blouse",
                'description' => 'Lightweight linen blouse with a relaxed silhouette for warm-weather outfits.',
                'old_price' => 69.00,
                'new_price' => 49.00,
                'image_url' => 'https://images.unsplash.com/photo-1434389677669-e08b4cac3105?auto=format&fit=crop&w=800&h=800&q=80',
            ],
            [
                'subcategory' => "Women's Clothing",
                'name' => "Women's Midi Dress",
                'description' => 'Flattering midi dress made from soft fabric for work events and casual evenings.',
                'old_price' => 89.00,
                'new_price' => 69.00,
                'image_url' => 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?auto=format&fit=crop&w=800&h=800&q=80',
            ],
            [
                'subcategory' => "Women's Clothing",
                'name' => 'Wool Blend Overcoat',
                'description' => 'Warm overcoat with a tailored cut for colder city weather.',
                'old_price' => 220.00,
                'new_price' => 179.00,
                'image_url' => 'https://images.unsplash.com/photo-1539533018447-63fcce2678e3?auto=format&fit=crop&w=800&h=800&q=80',
            ],
            [
                'subcategory' => 'Furniture',
                'name' => 'Ergonomic Office Chair',
                'description' => 'Adjustable office chair with lumbar support for long work sessions.',
                'old_price' => 299.00,
                'new_price' => 249.00,
                'image_url' => 'https://images.unsplash.com/photo-1580480055273-228ff5388ef8?auto=format&fit=crop&w=800&h=800&q=80',
            ],
            [
                'subcategory' => 'Furniture',
                'name' => 'Walnut Standing Desk',
                'description' => 'Height-adjustable desk with a warm wood finish for focused home offices.',
                'old_price' => 699.00,
                'new_price' => 599.00,
                'image_url' => 'https://images.unsplash.com/photo-1593062096033-9a2b17c7c5cf?auto=format&fit=crop&w=800&h=800&q=80',
            ],
            [
                'subcategory' => 'Furniture',
                'name' => 'Minimalist Bookshelf',
                'description' => 'Open wooden bookshelf with clean lines for living rooms and studies.',
                'old_price' => 249.00,
                'new_price' => 199.00,
                'image_url' => 'https://images.unsplash.com/photo-1594620302200-9a762244a156?auto=format&fit=crop&w=800&h=800&q=80',
            ],
        ];
    }
}
