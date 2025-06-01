<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Restaurant;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dishOptions = [
            ['Grilled Chicken Salad', 'Fresh greens with grilled chicken, cherry tomatoes, and balsamic dressing.'],
            ['Spaghetti Carbonara', 'Classic pasta with pancetta, egg, cream, and parmesan.'],
            ['Veggie Burger', 'Plant-based burger with lettuce, tomato, and avocado spread.'],
            ['BBQ Ribs', 'Slow-cooked pork ribs with smoky BBQ glaze.'],
            ['Seafood Platter', 'Shrimp, calamari, and fish fillet served with lemon aioli.'],
            ['Chocolate Lava Cake', 'Warm molten chocolate cake with a scoop of vanilla ice cream.'],
            ['Margherita Pizza', 'Wood-fired pizza with tomato, mozzarella, and basil.'],
            ['Sushi Combo', 'Mixed sushi rolls with salmon, tuna, and cucumber.'],
            ['Beef Stroganoff', 'Tender beef with creamy mushroom sauce over noodles.'],
            ['Pumpkin Soup', 'Creamy roasted pumpkin soup topped with seeds.'],
            ['Tacos al Pastor', 'Spicy pork tacos with pineapple and coriander.'],
            ['Pancake Stack', 'Stacked pancakes with maple syrup and fresh berries.'],
            ['Mushroom Risotto', 'Creamy risotto with porcini mushrooms and parmesan.'],
            ['Fish & Chips', 'Beer-battered fish served with fries and tartar sauce.'],
            ['Tom Yum Soup', 'Thai hot and sour soup with shrimp and lemongrass.'],
            ['Chicken Shawarma Wrap', 'Spiced grilled chicken wrapped in flatbread with garlic sauce.'],
            ['Greek Salad', 'Tomatoes, cucumbers, olives, feta, and olive oil.'],
            ['Lentil Curry', 'Spicy lentil stew with rice and naan.'],
            ['Club Sandwich', 'Triple-decker sandwich with turkey, bacon, and egg.'],
            ['Classic Cheeseburger', 'Beef patty with cheddar cheese and pickles.'],
            ['Teriyaki Chicken Bowl', 'Grilled chicken over rice with teriyaki sauce.'],
            ['Spinach & Feta Pie', 'Crispy pastry filled with spinach and feta cheese.'],
            ['Garlic Bread Basket', 'Toasted garlic bread slices with herb butter.'],
            ['Strawberry Cheesecake', 'Creamy cheesecake with fresh strawberry topping.'],
            ['Falafel Plate', 'Falafel with hummus, tabbouleh, and pita bread.'],
            ['Shrimp Alfredo', 'Creamy alfredo pasta with sautéed shrimp.'],
            ['Crispy Chicken Tenders', 'Breaded chicken served with honey mustard sauce.'],
            ['Onion Rings', 'Crispy deep-fried onion rings with dipping sauce.'],
            ['Avocado Toast', 'Toasted sourdough with smashed avocado and egg.'],
            ['Mango Sticky Rice', 'Thai dessert with coconut milk and mango slices.'],
        ];

        $restaurants = Restaurant::all();

        foreach ($restaurants as $restaurant) {
            $specialCount = rand(0, 2);
            $specialIndexes = $specialCount > 0 ? array_rand(range(0, 5), $specialCount) : [];
            if ($specialCount === 1) $specialIndexes = [$specialIndexes];
            if ($specialCount === 2 && !is_array($specialIndexes)) $specialIndexes = [$specialIndexes];

            $dishPool = collect($dishOptions)->shuffle()->take(6);

            foreach ($dishPool->values() as $i => $dish) {
                // Generate a base price between 2.00 and 12.00
                $base = rand(200, 1200);
                // Round down to nearest 10 (e.g., 516 → 510)
                $rounded = floor($base / 10) * 10;
                // Convert to euros
                $price = number_format($rounded / 100, 2, '.', '');

                DB::table('menus')->insert([
                    'restaurant_id' => $restaurant->id,
                    'name' => $dish[0],
                    'description' => $dish[1],
                    'price' => $price,
                    'isSpecial' => in_array($i, $specialIndexes) ? 1 : 0,
                ]);
            }
        }
    }
}
