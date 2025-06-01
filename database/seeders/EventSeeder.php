<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\Restaurant;
use Illuminate\Support\Carbon;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $eventTitles = [
            'Live Jazz Evening',
            'Taco Tuesday Fiesta',
            'Pasta Night Extravaganza',
            'Family Game Night',
            'Vegan Delights Pop-Up',
            'Wine & Cheese Tasting',
            'Summer Grill Party',
            'Cultural Food Showcase',
            'Dessert Lover’s Weekend',
            'Local Artist Showcase'
        ];

        $eventDescriptions = [
            'Enjoy live jazz performances by talented local musicians in a relaxed, candle-lit atmosphere. The evening will feature a specially curated dinner menu that pairs beautifully with the music. Guests are invited to sit back, sip on a glass of wine, and soak in the soothing sounds. This is the perfect setting for a romantic night or a cozy evening with friends. Reserve your table early, as space is limited.',
            
            'Celebrate Taco Tuesday with an explosion of flavors and festive energy. Our chefs are serving a variety of tacos, including chicken, beef, fish, and vegetarian options — all with unique salsas and toppings. The bar will offer 2-for-1 margaritas, and the entire restaurant will be decorated in vibrant colors. Join in on our trivia contest to win free meals and merch. Bring your appetite and your party spirit!',
            
            'Join us for Pasta Night Extravaganza, where you’ll enjoy unlimited servings of freshly made pasta dishes. Choose from creamy Alfredo, spicy arrabbiata, savory Bolognese, and more — all served with homemade bread and salad. The event will also feature a pasta-making demo from our head chef. Families are welcome, and kids under 10 eat free. A true celebration of Italian comfort food awaits!',
            
            'Looking for a fun and affordable night with the family? Come to our Family Game Night, where we combine delicious meals with interactive entertainment. Board games and card games will be set up across the restaurant, and our team will host a trivia challenge every hour. Special kid-friendly meals and desserts will be available, along with parent specials on drinks. Expect laughter, fun, and great food for all ages.',
            
            'Discover a unique dining experience during our Vegan Delights Pop-Up evening. The kitchen will feature a special menu of plant-based dishes, ranging from hearty mains to indulgent vegan desserts. Our guest chef will be doing a live cooking session and sharing insights on creating flavorful vegan meals at home. The atmosphere will be casual and welcoming for everyone, vegan or not. Come try something deliciously different.',
            
            'Unwind and indulge at our Wine & Cheese Tasting night, a sophisticated evening for food and wine lovers. Our sommelier has selected a variety of fine wines, each paired with artisanal cheeses from local and international producers. There will be guided tastings, pairing tips, and a chance to purchase exclusive wine bundles. The candle-lit ambiance sets the perfect tone for a relaxed, upscale experience. Seating is limited — don’t miss it!',
            
            'Our Summer Grill Party is here to kick off the warm season with delicious flair. We’ll be grilling juicy steaks, marinated chicken, fresh veggies, and more right on our outdoor patio. Live music from a local DJ will keep the vibe lively while you sip on craft beers and cocktails. Bring your friends, your sunglasses, and your appetite. The perfect event to celebrate sunny evenings!',
            
            'Travel the world in one evening at our Cultural Food Showcase. We’re offering a buffet of handcrafted dishes inspired by cuisines from Asia, Europe, the Middle East, and Africa. Each station will feature music, decor, and cultural insights from that region. A must-visit for foodies and curious palates alike. No passport needed — just an open mind and an empty stomach!',
            
            'Calling all dessert lovers! Our weekend dessert event features an exclusive menu of sweets you won’t find on our regular list. From creamy cheesecakes and fruit tarts to a flowing chocolate fountain, it’s a sugar-lover’s dream. We’ll also be offering specialty coffees and dessert cocktails to complete your experience. Perfect for couples, families, and anyone with a sweet tooth. Arrive early before the best treats sell out!',
            
            'Support creativity in your community at our Local Artist Showcase. We’re transforming our restaurant into a gallery for one night only, featuring paintings, photography, and live acoustic music. You’ll also get to try our limited-time event menu, inspired by local flavors and artistry. Meet the creators behind the works and discover hidden talents. Entry is free — just come hungry and curious.'
        ];

        $restaurants = Restaurant::all();

        foreach ($restaurants as $index => $restaurant) {
            Event::create([
                'restaurant_id' => $restaurant->id,
                'title' => $eventTitles[$index % count($eventTitles)],
                'description' => $eventDescriptions[$index % count($eventDescriptions)],
                'event_date' => Carbon::now()->addDays(rand(3, 21))->format('Y-m-d'),
            ]);
        }
    }
}