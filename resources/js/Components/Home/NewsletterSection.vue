<script setup>
import { ref } from 'vue';
import axios from 'axios';

const email = ref('');
const isSubmitting = ref(false);
const isSubscribed = ref(false);
const error = ref(null);

const validateEmail = (email) => {
    return String(email).toLowerCase().match(/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/);
};

const handleSubscribe = async () => {
    if (!validateEmail(email.value)) {
        error.value = 'Invalid email address.';
        return;
    }
    error.value = null;
    isSubmitting.value = true;
    try {
        await axios.post('/api/newsletter', { email: email.value });
        isSubscribed.value = true;
    } catch (err) {
        error.value = 'Failed to subscribe. Please try again.';
    } finally {
        isSubmitting.value = false;
    }
};
</script>

<template>
    <section class="bg-primary-light py-10 border-t border-border">
        <div class="container max-w-6xl mx-auto px-4">
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="text-center md:text-left">
                    <h2 class="text-lg font-semibold text-text-primary">
                        Get new listings in your inbox
                    </h2>
                    <p class="text-sm text-text-secondary mt-1">
                        Subscribe to our newsletter and stay updated with the latest properties.
                    </p>
                </div>

                <div class="w-full max-w-md">
                    <Transition mode="out-in">
                        <form v-if="!isSubscribed" @submit.prevent="handleSubscribe" class="flex gap-2">
                            <input 
                                v-model="email"
                                type="email" 
                                placeholder="Email address"
                                class="flex-1 h-9 px-4 text-sm border-gray-300 rounded-md focus:ring-primary focus:border-primary"
                                :disabled="isSubmitting"
                            />
                            <button 
                                type="submit"
                                class="h-9 px-5 bg-primary text-white text-sm font-semibold rounded-md hover:bg-primary-hover transition-colors disabled:opacity-50 shadow-sm"
                                :disabled="isSubmitting"
                            >
                                {{ isSubmitting ? '...' : 'Subscribe' }}
                            </button>
                        </form>
                        <div v-else class="h-9 flex items-center text-accent font-semibold text-sm">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                            Success! You're subscribed.
                        </div>
                    </Transition>
                    <p v-if="error" class="mt-2 text-xs text-red-500 font-medium">{{ error }}</p>
                </div>
            </div>
        </div>
    </section>
</template>
