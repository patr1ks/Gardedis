<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Restaurant;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $restaurants = [
            [
                'title' => 'Sunset Grill',
                'slug' => 'sunset-grill',
                'description' => 'Sunset Grill is a charming seaside restaurant that captures the beauty of the coast with every dish it serves. Guests are welcomed by ocean views, cozy wooden interiors, and the scent of grilled seafood in the air. Our specialty lies in freshly prepared seafood plates such as salmon steaks, calamari, and shrimp skewers, all grilled to perfection. The terrace offers an unbeatable setting to enjoy your meal while watching the sun dip into the water. Whether you are here for a romantic dinner, a family outing, or a peaceful solo lunch, Sunset Grill promises a relaxing and memorable experience. Live acoustic music is often featured on weekends, adding an extra layer of ambiance to your visit.',
                'published' => 0,
                'price' => 7.50,
                'owner' => 2,
                'layout_json' => null,
            ],
            [
                'title' => 'Urban Bites',
                'slug' => 'urban-bites',
                'description' => 'Urban Bites is a buzzing city café located in the heart of downtown, where bold flavors meet street-style comfort. Known for its juicy burgers, loaded fries, and thick milkshakes, the café attracts students, professionals, and tourists alike. Inside, the vibe is energetic with modern industrial decor, upbeat playlists, and a counter bar for quick bites. Everything is made fresh to order using locally sourced ingredients, and there are plenty of vegetarian and vegan options to choose from. The café also hosts themed burger nights and live DJ events on Fridays. Whether you are stopping by for lunch or a late-night snack, Urban Bites is a go-to hangout for casual and flavorful dining.',
                'published' => 0,
                'price' => 4.50,
                'owner' => 3,
                'layout_json' => null,
            ],
            [
                'title' => 'Forest Feast',
                'slug' => 'forest-feast',
                'description' => 'Forest Feast is a rustic woodland-themed restaurant that offers a deep connection to nature through food. Nestled on the edge of a city park, it specializes in dishes inspired by the Latvian countryside — think wild mushrooms, roasted game, root vegetables, and fresh forest herbs. The warm wooden interior, low lighting, and leafy decor make for a relaxing and cozy atmosphere. Perfect for those looking to escape the urban noise, Forest Feast welcomes families, hikers, and couples alike. Seasonal menus ensure that each visit offers something new, and occasional storytelling nights give diners a taste of Latvian folklore. It’s not just a meal, it’s a sensory journey into the heart of the forest.',
                'published' => 0,
                'price' => 3.00,
                'owner' => 4,
                'layout_json' => null,
            ],
            [
                'title' => 'Bella Pasta',
                'slug' => 'bella-pasta',
                'description' => 'Bella Pasta is a family-run Italian eatery that serves traditional pasta dishes crafted from time-honored recipes. Every dish is prepared with love and attention — from hand-rolled gnocchi to slow-cooked sauces and wood-fired lasagna. Guests can enjoy warm hospitality in a charming space filled with vintage decor, candlelight, and the soft hum of Italian music. The open kitchen lets you watch the chefs work their magic, and the staff is always happy to recommend wine pairings. Vegetarian options and gluten-free pasta are available to ensure everyone feels at home. Whether you come for a quick lunch or a romantic dinner, Bella Pasta brings the spirit of Italy to your plate.',
                'published' => 0,
                'price' => 6.50,
                'owner' => 5,
                'layout_json' => null,
            ],
            [
                'title' => 'Ocean Breeze',
                'slug' => 'ocean-breeze',
                'description' => 'Ocean Breeze is an upscale coastal dining experience that combines elegant ambiance with exquisite seafood. Floor-to-ceiling windows provide stunning views of the waterfront, while the interior reflects a modern nautical theme. The menu features fresh oysters, lobster tail, grilled seabass, and daily seafood specials prepared by experienced chefs. Ideal for date nights, anniversaries, and corporate events, the restaurant offers both à la carte and tasting menus. Exceptional service, fine wines, and a calm setting make every visit a special occasion. Be sure to book in advance — Ocean Breeze is a favorite among locals and tourists alike.',
                'published' => 0,
                'price' => 8.00,
                'owner' => 6,
                'layout_json' => null,
            ],
            [
                'title' => 'Mountain Dine',
                'slug' => 'mountain-dine',
                'description' => 'Mountain Dine brings the spirit of alpine comfort to your table with hearty, wholesome meals served in a cozy, lodge-like atmosphere. Guests are welcomed by crackling fireplaces, stone walls, and wood-paneled interiors that resemble a ski cabin. The menu features roasted meats, root vegetable stews, baked cheeses, and warm homemade bread, perfect for cold-weather cravings. Everything is sourced from regional farms and prepared with old-world care. A small but curated beer and wine list complements the rich flavors of the menu. Whether yo are unwinding after a hike or just craving comfort food, Mountain Dine feels like a warm hug in restaurant form.',
                'published' => 0,
                'price' => 5.00,
                'owner' => 7,
                'layout_json' => null,
            ],
            [
                'title' => 'Spice Route',
                'slug' => 'spice-route',
                'description' => 'Spice Route is a colorful culinary journey through the vibrant cuisines of Asia and the Middle East. With dishes like Thai curries, Indian biryani, Lebanese shawarma, and Moroccan tagine, the menu is a celebration of spice and tradition. Each meal is made to order using authentic spices, fresh herbs, and locally sourced ingredients. The restaurant’s bold interior features hand-painted murals, lanterns, and warm lighting that transport you to another world. Guests can choose to sit at traditional low tables or enjoy standard dining. With regular cultural events and food festivals, Spice Route is more than a place to eat — it’s an experience.',
                'published' => 0,
                'price' => 7.00,
                'owner' => 8,
                'layout_json' => null,
            ],
            [
                'title' => 'The Green Fork',
                'slug' => 'the-green-fork',
                'description' => 'The Green Fork is a vibrant plant-based restaurant dedicated to health, sustainability, and great taste. With a focus on locally grown vegetables and organic ingredients, the menu offers colorful salads, creative grain bowls, vegan burgers, and guilt-free desserts. Guests are welcomed into a bright, open space with greenery on the walls and a calm, modern design. The atmosphere encourages mindful eating, friendly conversations, and a connection to nature. A juice bar and smoothie station are also available for those on the go. It’s a favorite spot among vegans, vegetarians, and health-conscious diners.',
                'published' => 0,
                'price' => 3.50,
                'owner' => 9,
                'layout_json' => null,
            ],
            [
                'title' => 'Metro Deli',
                'slug' => 'metro-deli',
                'description' => 'Metro Deli is a fast-paced, fresh food destination in the heart of the city. Known for its stacked sandwiches, warm soups, and crisp salads, it’s the perfect lunch spot for busy professionals and students. The ingredients are fresh, the service is quick, and the prices are reasonable. With both dine-in and takeaway options, it’s built for convenience without sacrificing quality. The minimalistic modern interior offers a clean and efficient environment to grab a bite and recharge. Daily specials and build-your-own sandwich options keep things interesting for regulars.',
                'published' => 0,
                'price' => 2.50,
                'owner' => 10,
                'layout_json' => null,
            ],
            [
                'title' => 'Family Table',
                'slug' => 'family-table',
                'description' => 'Family Table is all about bringing people together over comforting, home-cooked meals. The menu includes everything from meatloaf and mashed potatoes to baked pasta and seasonal vegetable casseroles. The spacious layout includes large group tables, high chairs for toddlers, and a play corner to keep kids entertained. It’s ideal for Sunday dinners, birthday gatherings, and casual midweek outings. Friendly staff, fair prices, and a welcoming vibe keep families coming back. It’s not fancy — it’s just really, really good food served with heart.',
                'published' => 0,
                'price' => 4.00,
                'owner' => 11,
                'layout_json' => null,
            ],
        ];

        foreach ($restaurants as $restaurant) {
            Restaurant::create($restaurant);
        }
    }
}
