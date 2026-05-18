<script setup>
const currentYear = new Date().getFullYear();
</script>

<template>
    <footer class="bg-navy text-white pt-12 pb-8">
        <div class="container max-w-6xl mx-auto px-4">
            <!-- Footer Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 pb-12 border-b border-gray-800">
                <!-- Brand -->
                <div class="space-y-4">
                    <div class="flex items-center">
                        <span class="text-lg font-bold text-white">Ocean</span>
                        <span class="text-lg font-bold text-primary">Realtors</span>
                    </div>
                    <p class="text-sm text-text-muted leading-relaxed max-w-xs">
                        Redefining the real estate experience with premium listings and unparalleled service. Your dream home is just a click away.
                    </p>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-6">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="/" class="text-sm text-gray-400 hover:text-white transition-colors">Home</a></li>
                        <li><a href="/properties" class="text-sm text-gray-400 hover:text-white transition-colors">Properties</a></li>
                        <li><a href="/agents" class="text-sm text-gray-400 hover:text-white transition-colors">Agents</a></li>
                        <li><a href="/about" class="text-sm text-gray-400 hover:text-white transition-colors">About Us</a></li>
                    </ul>
                </div>

                <!-- Property Types -->
                <div>
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-6">Properties</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-sm text-gray-400 hover:text-white transition-colors">Apartments for Rent</a></li>
                        <li><a href="#" class="text-sm text-gray-400 hover:text-white transition-colors">Villas for Sale</a></li>
                        <li><a href="#" class="text-sm text-gray-400 hover:text-white transition-colors">Offices for Rent</a></li>
                        <li><a href="#" class="text-sm text-gray-400 hover:text-white transition-colors">Condos for Sale</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-6">Contact</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <span class="text-sm text-gray-400">123 Real Estate Ave, <br /> Luxury City, NY 10001</span>
                        </li>
                        <li class="flex items-center">
                            <span class="text-sm text-gray-400">+1 (234) 567-890</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="pt-8 flex flex-col md:flex-row items-center justify-between text-xs text-gray-500">
                <p>&copy; {{ currentYear }} Ocean Realtors. All rights reserved.</p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="hover:text-white transition-colors">Privacy</a>
                    <a href="#" class="hover:text-white transition-colors">Terms</a>
                    <a href="#" class="hover:text-white transition-colors">Cookies</a>
                </div>
            </div>
        </div>
    </footer>
</template>
