<script setup>
import NoHeroLayout from "./Layouts/NoHeroLayout.vue";
</script>

<template>
    <NoHeroLayout>
        <div class="max-w-6xl mx-auto px-4 py-12">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold mb-2">About Gardēdis</h1>
                <p class="text-gray-600 text-lg">
                    Discover. Reserve. Experience.
                </p>
            </div>

            <!-- Content Section -->
            <div class="flex flex-col md:flex-row gap-10 items-center">
                <div class="md:w-1/2">
                    <h2 class="text-2xl font-semibold mb-4">Our Story</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Gardēdis is your go-to platform for discovering the best
                        restaurants in your city. Whether you're craving
                        traditional Latvian cuisine or seeking new fusion
                        hotspots, Gardēdis connects food lovers with top-rated
                        restaurants, curated menus, and an easy-to-use
                        reservation system.
                    </p>
                    <p class="text-gray-700 leading-relaxed">
                        We believe dining should be an experience. That’s why we
                        empower users to explore, filter, and reserve with ease,
                        while giving restaurant owners powerful tools to
                        showcase their space and menu.
                    </p>
                </div>
                <div class="md:w-1/2">
                    <img
                        src="../../images/team.jpg"
                        alt="Restaurant"
                        class="rounded-xl shadow-lg w-full object-cover"
                    />
                </div>
            </div>

            <!-- Team Section -->
            <div class="mt-20">
                <h2 class="text-2xl font-semibold text-center mb-8">
                    Meet the Team
                </h2>
                <div class="flex flex-wrap justify-center gap-10">
                    <div class="text-center">
                        <img
                            src="../../images/test.jpg"
                            alt="Team Member"
                            class="w-24 h-24 rounded-full object-cover mx-auto mb-2"
                        />
                        <h3 class="font-medium">Patriks Venens</h3>
                        <p class="text-sm text-gray-500">Founder & Developer</p>
                    </div>
                    <!-- <div class="text-center">
                        <img
                            src="https://source.unsplash.com/100x100/?woman,designer"
                            alt="Team Member"
                            class="w-24 h-24 rounded-full object-cover mx-auto mb-2"
                        />
                        <h3 class="font-medium">Alice D.</h3>
                        <p class="text-sm text-gray-500">UI/UX Designer</p>
                    </div>
                    <div class="text-center">
                        <img
                            src="https://source.unsplash.com/100x100/?man,marketing"
                            alt="Team Member"
                            class="w-24 h-24 rounded-full object-cover mx-auto mb-2"
                        />
                        <h3 class="font-medium">Tom R.</h3>
                        <p class="text-sm text-gray-500">Marketing Lead</p>
                    </div> -->
                </div>
            </div>
        </div>
    </NoHeroLayout>
</template>
