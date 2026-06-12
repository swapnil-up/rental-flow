<template>
    <PublicLayout>
        <Link href="/properties" class="back-link">&larr; Back to Properties</Link>

        <div class="detail">
            <h1>{{ property.name }}</h1>
            <p class="subtitle">{{ capitalize(property.type) }} in {{ property.city }}, {{ property.state }}</p>

            <div class="stats">
                <div class="stat"><span class="stat-label">Bedrooms</span><span>{{ property.bedrooms }}</span></div>
                <div class="stat"><span class="stat-label">Bathrooms</span><span>{{ property.bathrooms }}</span></div>
                <div class="stat"><span class="stat-label">Square Feet</span><span>{{ property.square_feet }}</span></div>
                <div class="stat"><span class="stat-label">Monthly Rent</span><span class="price">${{ formatMoney(property.monthly_rent) }}/mo</span></div>
            </div>

            <div class="section">
                <h3>Address</h3>
                <p>{{ property.address }}<br />{{ property.city }}, {{ property.state }} {{ property.zip_code }}</p>
            </div>

            <div class="section">
                <h3>Description</h3>
                <p>{{ property.description }}</p>
            </div>

            <div class="inquiry-box">
                <h3>Interested? Send us a message</h3>
                <form @submit.prevent="submitInquiry">
                    <div class="field">
                        <input v-model="inquiry.name" placeholder="Your Name" required />
                    </div>
                    <div class="field">
                        <input v-model="inquiry.email" type="email" placeholder="Your Email" required />
                    </div>
                    <div class="field">
                        <textarea v-model="inquiry.message" placeholder="Your message..." rows="4" required></textarea>
                    </div>
                    <p v-if="inquirySuccess" class="success-msg">Message sent! We'll be in touch.</p>
                    <button type="submit" class="btn" :disabled="inquirySubmitting">{{ inquirySubmitting ? 'Sending...' : 'Send Message' }}</button>
                </form>
            </div>
        </div>
    </PublicLayout>
</template>

<script setup>
import { reactive, ref } from 'vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({ property: Object });

const capitalize = (s) => s.charAt(0).toUpperCase() + s.slice(1);
const formatMoney = (c) => (c / 100).toFixed(2);

const inquiry = reactive({ name: '', email: '', message: '' });
const inquirySubmitting = ref(false);
const inquirySuccess = ref(false);

const submitInquiry = () => {
    inquirySubmitting.value = true;
    router.post(`/properties/${props.property.id}/inquiry`, inquiry, {
        onSuccess: () => {
            inquiry.name = '';
            inquiry.email = '';
            inquiry.message = '';
            inquirySuccess.value = true;
            inquirySubmitting.value = false;
        },
        onError: () => {
            inquirySubmitting.value = false;
        },
    });
};
</script>

<style scoped>
.back-link { color: #3490dc; text-decoration: none; font-size: 14px; display: inline-block; margin-bottom: 20px; }
.back-link:hover { text-decoration: underline; }
.detail { max-width: 700px; }
h1 { margin: 0 0 4px; font-size: 28px; }
.subtitle { color: #6b7280; margin: 0 0 24px; }

.stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 30px; }
.stat { background: white; border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px; text-align: center; }
.stat-label { display: block; font-size: 12px; color: #6b7280; margin-bottom: 4px; }
.stat span:last-child { font-size: 18px; font-weight: 600; }
.price { color: #059669; }

.section { margin-bottom: 24px; }
.section h3 { font-size: 16px; margin: 0 0 8px; }
.section p { margin: 0; color: #374151; line-height: 1.6; }

.inquiry-box { background: white; border: 1px solid #e5e7eb; border-radius: 10px; padding: 24px; margin-top: 30px; }
.inquiry-box h3 { margin: 0 0 16px; font-size: 16px; }
.field { margin-bottom: 12px; }
.field input, .field textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; box-sizing: border-box; }
.field textarea { resize: vertical; }
.success-msg { color: #059669; font-size: 14px; font-weight: 600; }
.btn { padding: 10px 24px; background: #3490dc; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; }
.btn:hover:not(:disabled) { background: #2779bd; }
.btn:disabled { opacity: 0.6; cursor: not-allowed; }
</style>
